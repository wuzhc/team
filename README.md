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

安装数据库（暂时没有设计数据库，此阶段可以忽略）：
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

演示网址：  
- [http://wuzhc.top](http://wuzhc.top)

如果本地网址正常访问，表示安装成功

### 代码规范说明：
- [代码规范说明](https://github.com/wuzhc/team/blob/master/docs/note/%E5%91%BD%E5%90%8D%E8%A7%84%E8%8C%83%E8%AF%B4%E6%98%8E.md)

### 网站功能：
- [功能需求](https://github.com/wuzhc/manage/blob/master/docs/note/%E5%8A%9F%E8%83%BD%E9%9C%80%E6%B1%82.md)  
- [页面功能说明](https://github.com/wuzhc/manage/blob/master/docs/note/%E9%A1%B5%E9%9D%A2%E5%8A%9F%E8%83%BD%E8%AF%B4%E6%98%8E.md)  

### 数据库设计：
- [数据库设计及字典说明](https://github.com/wuzhc/manage/blob/master/docs/note/%E6%95%B0%E6%8D%AE%E5%BA%93%E8%AE%BE%E8%AE%A1%E5%8F%8A%E5%AD%97%E5%85%B8%E8%AF%B4%E6%98%8E.md)  

### 技术说明：
- [实现技术说明](https://github.com/wuzhc/manage/blob/master/docs/note/%E5%AE%9E%E7%8E%B0%E6%8A%80%E6%9C%AF%E8%AF%B4%E6%98%8E.md)  

### 效果图：  
![首页](https://github.com/wuzhc/manage/blob/master/frontend/web/images/index.png)
![动态页](https://github.com/wuzhc/manage/blob/master/frontend/web/images/dymanic.png)
![聊天页](https://github.com/wuzhc/manage/blob/master/frontend/web/images/chat.png)
![团队页](https://github.com/wuzhc/manage/blob/master/frontend/web/images/team.png)


