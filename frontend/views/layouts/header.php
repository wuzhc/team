<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use common\services\UserService;
use frontend\assets\AppAsset;

$this->registerJsFile('//cdn.bootcss.com/socket.io/1.3.7/socket.io.js', [
    AppAsset::className(),
    'depends' => 'frontend\assets\AppAsset'
]);

?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">T</span><span class="logo-lg">' .
        Yii::$app->name .
        '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">
        <div id="socket-content"></div>
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <ul class="nav navbar-nav">
            <li><a href="<?=Url::to(['default/index'])?>">首页</a></li>
        </ul>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->
                <!--操作消息通知-->
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-danger msg-handle-num">0</span>
                    </a>
                    <ul class="dropdown-menu" style="width: 300px">
                        <li class="header">你有&nbsp;<span class="msg-handle-num">0</span>&nbsp;消息</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu" id="msg-handle">
                            </ul>
                        </li>
                        <li class="footer"><a href="#">查看所有</a></li>
                    </ul>
                </li>
                <!--操作消息通知-->
<!--                <li class="dropdown notifications-menu">-->
<!--                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">-->
<!--                        <i class="fa fa-envelope-o"></i>-->
<!--                        <span class="label label-warning">10</span>-->
<!--                    </a>-->
<!--                    <ul class="dropdown-menu" style="width: 300px">-->
<!--                        <li class="header">You have 10 notifications</li>-->
<!--                        <li>-->
<!--                            <!-- inner menu: contains the actual data -->
<!--                            <ul class="menu">-->
<!--                                <li>-->
<!--                                    <a href="#">-->
<!--                                        <i class="fa fa-users text-aqua"></i> 5 new members joined today-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <a href="#">-->
<!--                                        <i class="fa fa-warning text-yellow"></i> Very long description here that may-->
<!--                                        not fit into the page and may cause design problems-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <a href="#">-->
<!--                                        <i class="fa fa-users text-red"></i> 5 new members joined-->
<!--                                    </a>-->
<!--                                </li>-->
<!---->
<!--                                <li>-->
<!--                                    <a href="#">-->
<!--                                        <i class="fa fa-shopping-cart text-green"></i> 25 sales made-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <a href="#">-->
<!--                                        <i class="fa fa-user text-red"></i> You changed your username-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                            </ul>-->
<!--                        </li>-->
<!--                        <li class="footer"><a href="#">View all</a></li>-->
<!--                    </ul>-->
<!--                </li>-->
                <!-- Tasks: style can be found in dropdown.less -->
<!--                <li class="dropdown tasks-menu">-->
<!--                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">-->
<!--                        <i class="fa fa-flag-o"></i>-->
<!--                        <span class="label label-primary">9</span>-->
<!--                    </a>-->
<!--                    <ul class="dropdown-menu">-->
<!--                        <li class="header">You have 9 tasks</li>-->
<!--                        <li>-->
<!--                            <!-- inner menu: contains the actual data -->
<!--                            <ul class="menu">-->
<!--                                <li><!-- Task item -->
<!--                                    <a href="#">-->
<!--                                        <h3>-->
<!--                                            Design some buttons-->
<!--                                            <small class="pull-right">20%</small>-->
<!--                                        </h3>-->
<!--                                        <div class="progress xs">-->
<!--                                            <div class="progress-bar progress-bar-aqua" style="width: 20%"-->
<!--                                                 role="progressbar" aria-valuenow="20" aria-valuemin="0"-->
<!--                                                 aria-valuemax="100">-->
<!--                                                <span class="sr-only">20% Complete</span>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <!-- end task item -->
<!--                                <li><!-- Task item -->
<!--                                    <a href="#">-->
<!--                                        <h3>-->
<!--                                            Create a nice theme-->
<!--                                            <small class="pull-right">40%</small>-->
<!--                                        </h3>-->
<!--                                        <div class="progress xs">-->
<!--                                            <div class="progress-bar progress-bar-green" style="width: 40%"-->
<!--                                                 role="progressbar" aria-valuenow="20" aria-valuemin="0"-->
<!--                                                 aria-valuemax="100">-->
<!--                                                <span class="sr-only">40% Complete</span>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <!-- end task item -->
<!--                                <li><!-- Task item -->
<!--                                    <a href="#">-->
<!--                                        <h3>-->
<!--                                            Some task I need to do-->
<!--                                            <small class="pull-right">60%</small>-->
<!--                                        </h3>-->
<!--                                        <div class="progress xs">-->
<!--                                            <div class="progress-bar progress-bar-red" style="width: 60%"-->
<!--                                                 role="progressbar" aria-valuenow="20" aria-valuemin="0"-->
<!--                                                 aria-valuemax="100">-->
<!--                                                <span class="sr-only">60% Complete</span>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <!-- end task item -->
<!--                                <li><!-- Task item -->
<!--                                    <a href="#">-->
<!--                                        <h3>-->
<!--                                            Make beautiful transitions-->
<!--                                            <small class="pull-right">80%</small>-->
<!--                                        </h3>-->
<!--                                        <div class="progress xs">-->
<!--                                            <div class="progress-bar progress-bar-yellow" style="width: 80%"-->
<!--                                                 role="progressbar" aria-valuenow="20" aria-valuemin="0"-->
<!--                                                 aria-valuemax="100">-->
<!--                                                <span class="sr-only">80% Complete</span>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <!-- end task item -->
<!--                            </ul>-->
<!--                        </li>-->
<!--                        <li class="footer">-->
<!--                            <a href="#">View all tasks</a>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </li>-->
                <!-- User Account: style can be found in dropdown.less -->

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= UserService::factory()->getUserPortrait(Yii::$app->user->identity) ?>"
                             class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?= Yii::$app->user->identity->fdName ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= UserService::factory()->getUserPortrait(Yii::$app->user->identity) ?>"
                                 class="img-circle" alt="User Image"/>
                            <p>
                                <?= Yii::$app->user->identity->fdPosition ?>
                                <small>加入时间 <?= Yii::$app->user->identity->fdVerify ?></small>
                            </p>
                        </li>
                        <!-- Menu Body -->
<!--                        <li class="user-body">-->
<!--                            <div class="col-xs-4 text-center">-->
<!--                                <a href="#">Followers</a>-->
<!--                            </div>-->
<!--                            <div class="col-xs-4 text-center">-->
<!--                                <a href="#">Sales</a>-->
<!--                            </div>-->
<!--                            <div class="col-xs-4 text-center">-->
<!--                                <a href="#">Friends</a>-->
<!--                            </div>-->
<!--                        </li>-->
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?= \yii\helpers\Url::to(['user/profile']) ?>"
                                   class="btn btn-default btn-flat">资料</a>
                            </div>
                            <div class="pull-right">
                                <?= Html::a('退出', ['/user/logout'], [
                                    'data-method' => 'post',
                                    'class'       => 'btn btn-default btn-flat'
                                ]) ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<script>
    <?php $this->beginBlock('team-header') ?>
    $(function () {
        // 初始化io对象
        var socket = io('http://' + document.domain + ':2120');
        // uid 可以为网站用户的uid，作为例子这里用session_id代替
        var uid = '<?php echo session_id();?>';
        // 当socket连接后发送登录请求
        socket.on('connect', function () {
            socket.emit('login', {
                uid: "<?=Yii::$app->user->id?>",
                companyID: "<?=Yii::$app->user->identity->fdCompanyID?>"
            });
        });
        // 当服务端推送来消息时触发，这里简单的aler出来，用户可做成自己的展示效果
        socket.on('new_msg', function (msg) {
            if (msg.typeID == '<?= \common\config\Conf::MSG_HANDLE?>') {
                var html = '<li>' +
                    '<a href="' + msg.url + '">' +
                    '<div class="pull-left">' +
                    '<img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>' +
                    '</div>' +
                    '<h4 style="overflow:hidden;text-overflow:ellipsis;">' + msg.title + '</h4>' +
                    '<p style="overflow:hidden;text-overflow:ellipsis;">' + msg.content + '</p>' +
                    '</a>' +
                    '</li>';
                $('#msg-handle').prepend(html);

                var num = $('.msg-handle-num').eq(1).text() * 1;
                $('.msg-handle-num').text(num + 1);
            }
        });
        // 当服务端推送来消息时触发，这里简单的aler出来，用户可做成自己的展示效果
        socket.on('update_dynamic', function (msg) {
            var html = '<li>' +
                '<i class="portrait">' +
                '<img class="img-circle img-bordered-sm portrait-img" src="' + msg.portrait + '" alt="User Image"></i>' +
                '<div class="timeline-item">' +
                '<span class="time">' +
                '<i class="fa fa-clock-o"></i>' + msg.date + '</span>' +
                '<h3 class="timeline-header">' +
                '<a href="' + msg.url + '">' + msg.operator + '</a>' +
                '<span class="desc">' + msg.title + '</span>' +
                '</h3>' +
                '<div class="timeline-body">' + msg.content + '</div>' +
                '</div>' +
                '</li>';
            $('#dynamic-list').prepend(html);
        });
        // 页面统计
        socket.on('update_online_count', function (msg) {
            $('#online-people').html(msg);
        });


        (function () {
            $.ajax({
                url: "<?= Url::to(['default/get-msg-handle'])?>",
                method: 'get',
                dataType: 'json'
            }).done(function (res) {
                var data = res.data || [];
                var total = res.total || 0;
                var html = '';

                for (var k in data) {
                    html += '<li>' +
                        '<a href="' + data[k].url + '">' +
                        '<div class="pull-left">' +
                        '<img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>' +
                        '</div>' +
                        '<h4 style="overflow:hidden;text-overflow:ellipsis;">' + data[k].title + '</h4>' +
                        '<p style="overflow:hidden;text-overflow:ellipsis;">' + data[k].content + '</p>' +
                        '</a>' +
                        '</li>';
                }
                $('#msg-handle').html(html);
                $('.msg-handle-num').text(total);
            });
        })();

    });
    <?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['team-header'], \yii\web\View::POS_END); ?>
