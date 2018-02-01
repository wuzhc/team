#### add by wuzhc 2018-01-14
[http://www.yiichina.com/doc/guide/2.0/security-authorization](http://www.yiichina.com/doc/guide/2.0/security-authorization)


### 角色：
- 超级管理员
- 普通管理员
- 成员
- 游客

### 权限分配：
![rbac](https://github.com/wuzhc/manage/blob/master/frontend/web/images/rbac.png)
#### 说明：
- 第一个注册用户默认赋予超级管理员身份，其他成员都由超级管理员通过平台导入
- 超级管理员可以设置成员为管理员身份，管理员身份有权限导入其他成员
- 导入的新成员默认都为普通成员身份
- 当成员离职或其他原因离开时，可以设置为游客身份或直接删除

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
const ROLE_GUEST = 0; // 游客
const ROLE_SUPER = 1; // 超级管理员
const ROLE_ADMIN = 2; // 普通管理员
const ROLE_MEMBER = 3; // 普通成员
```

### 初始化脚本
```bash
./yii rbac/init
```
如果添加了新的权限，可以执行如下命令：
```bash
./yii rbac/reset
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