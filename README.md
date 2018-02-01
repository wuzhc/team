#### 网址
- http://wuzhc.top

#### 环境要求
- php5.6+
- mongodb扩展

### 安装方法
下载：
```bash
git clone https://github.com/wuzhc/team.git
```

安装（-vvv用于查看详细安装过程，国外源可能会很慢）：
```bash
composer intall -vvv
```

初始化
```bash
./init # window环境执行init.bat
```

安装数据库：
- 新建数据库
```bash
CREATE DATABASE team DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
```
- 配置数据库组件 main.php
```bash
'db' => [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=team',
    'username' => '数据库用户',
    'password' => '数据库密码',
    'charset' => 'utf8',
    'tablePrefix' => 'tb',
],
```
- 初始化数据库表
```bash
./yii migrate/up all                
```
![数据库表迁移](https://github.com/wuzhc/manage/blob/master/docs/images/dbInit.png)

- 初始化权限
```bash
./yii rbac/init 
```
![权限初始化](https://github.com/wuzhc/manage/blob/master/docs/images/rbacInit.png)

本地网址：
> 最好是自己配置虚拟域名，然后team/frontend/web作为root目录
```bash
http://localhost/team/frontend/web/index.php
```

演示网址：  
- [http://wuzhc.top](http://wuzhc.top)

如果本地网址正常访问，表示安装成功

### 代码规范说明：
- [代码规范说明](https://github.com/wuzhc/team/blob/master/docs/%E5%91%BD%E5%90%8D%E8%A7%84%E8%8C%83%E8%AF%B4%E6%98%8E.md)

### 网站功能：
- [功能需求](https://github.com/wuzhc/manage/blob/master/docs/%E5%8A%9F%E8%83%BD%E9%9C%80%E6%B1%82.md)  

### 数据库设计：
- [数据库设计及字典说明](https://github.com/wuzhc/manage/blob/master/docs/%E6%95%B0%E6%8D%AE%E5%BA%93%E8%AE%BE%E8%AE%A1%E5%8F%8A%E5%AD%97%E5%85%B8%E8%AF%B4%E6%98%8E.md)  

### 技术说明：
- [即时消息推送](https://github.com/wuzhc/team/blob/master/docs/%E6%B6%88%E6%81%AF%E6%8E%A8%E9%80%81.md)
- [RBAC权限功能](https://github.com/wuzhc/team/blob/master/docs/%E5%8A%9F%E8%83%BD%E9%9C%80%E6%B1%82.md)  

### 效果图：  
![首页](https://github.com/wuzhc/manage/blob/master/docs/images/index.png)
![动态页](https://github.com/wuzhc/manage/blob/master/docs/images/dymanic.png)
![团队页](https://github.com/wuzhc/manage/blob/master/docs/images/team.png)
![用户页](https://github.com/wuzhc/manage/blob/master/docs/images/user.png)
![设置用户页](https://github.com/wuzhc/manage/blob/master/docs/images/set-user.png)
![权限列表](https://github.com/wuzhc/manage/blob/master/docs/images/rbac-list.png)


