### 安装方法
下载：
```bash
git clone https://github.com/wuzhc/manage.git
```

安装：
```bash
composer intall -vvv
```

安装数据库：
```bash
./yii migrate/down                 Downgrades the application by reverting old migrations.
./yii migrate/history              Displays the migration history.
./yii migrate/redo                 Redoes the last few migrations.
./yii migrate/up (default)         Upgrades the application by applying new migrations.
```

本地网址：
```bash
http://localhost/manage/frontend/web/index.php
```

如果本地网址正常访问，表示安装成功

### 网站功能：
[功能需求](https://github.com/wuzhc/manage/blob/master/docs/note/%E5%8A%9F%E8%83%BD%E9%9C%80%E6%B1%82.md)
[页面功能说明](https://github.com/wuzhc/manage/blob/master/docs/note/%E9%A1%B5%E9%9D%A2%E5%8A%9F%E8%83%BD%E8%AF%B4%E6%98%8E.md)

### 数据库设计
[数据库设计及字典说明]()

### 效果图：  
![首页](https://github.com/wuzhc/manage/blob/master/frontend/web/images/index.png)
![动态页](https://github.com/wuzhc/manage/blob/master/frontend/web/images/dynamic.png)


