#### add by wuzhc 2018-01-14
[http://www.yiichina.com/doc/guide/2.0/security-authorization](http://www.yiichina.com/doc/guide/2.0/security-authorization)

- 一个用户是超级管理员，就是第一个创建公司的人
- 所有的操作都需要检测是否在自己公司内
- 操作需要检测是否为项目成员

### 对象：
- 超级管理员
- 项目人员
- 团队人员
- 项目
- 任务

### 对象关系：
- 超级管理员
    - 创建项目
        - 设置项目管理人员
            - 邀请项目普通成员（从团队选取人员加入到项目，只有在该项目的人员才能操作该项目内容）
        - 项目任务（所有项目人员都可以自由创建）
    - 创建团队

### 权限分配：
- 一个项目为一个角色(只要在这个角色的用户才能操作这个项目内容)
- 项目负责人角色(拥有管理项目人员操作权限)
- 普通项目人员(拥有操作项目的权限)


### 过程：
- 定义角色和权限；
- 建立角色和权限的关系；
- 定义规则；
- 将规则与角色和权限作关联；
- 指派角色给用户。

### 数据库表
- tbItemTable： 该表存放授权条目
- tbItemChildTable： 该表存放授权条目的层次关系。
- tbAssignmentTable： 该表存放授权条目对用户的指派情况。
- tbRuleTable： 该表存放规则。

### 常量定义 team/common/config/Conf.php
```bash
const ROLE_SUPER = 0; // 超级管理员
const ROLE_ADMIN = 1; // 普通管理员
const ROLE_MEMBER = 2; // 普通成员
```

### 初始化脚本
```bash
./yii rbac/init
```

### 使用：
用户是否有权限用Yii::$app->user->can($permissionName, $params = [], $allowCaching = true)判断，如下
```php
<?php
/**
 * @inheritdoc
 */
public function behaviors()
{
    return [
        'access' => [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'matchCallback' => function ($rule, $action) {
                        // 登录检测
                        if (Yii::$app->user->isGuest) {
                            return false;
                        }
                        
                        // 超级管理员检测
                        if (!Yii::$app->user->can('super')) {
                            return false;
                        }
                        
                        return true;
                    }
                ],
            ],
        ],
        'verbs' => [
            'class'   => VerbFilter::className(),
            'actions' => [
                'delete' => ['POST'],
            ],
        ],
    ];
}
```