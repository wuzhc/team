<?php

/* @var $this yii\web\View */
/* @var $projects array */
/* @var $totalMap array */
/* @var $finishMap array */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '主页';
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
?>
<!--<section class="invoice">-->
<?php if (!empty($projects)) { ?>
    <div class="row">
        <?php foreach ($projects as $k => $project) { ?>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box <?= Yii::$app->params['bgColor'][$k % 12]?>">
                <div class="inner">
                    <h4><?= Html::encode($project->fdName) ?></h4>
                    <p>
                        <?= !empty($finishMap[$project->id]) ? $finishMap[$project->id] : 0 ?>
                        /
                        <?= !empty($totalMap[$project->id]) ? $totalMap[$project->id] : 0 ?></p>
                </div>
                <div class="icon">
                    <i class="fa <?= Yii::$app->params['transportationIcon'][$k%13] ?>"></i>
                </div>
                <a href="<?= Url::to(['task/index', 'projectID' => $project->id])?>" class="small-box-footer">
                    进入 <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <?php } ?>
        <!-- ./col -->
    </div>
<?php } else { ?>
    <div class="jumbotron">
        <h1>欢迎使用Team!</h1>
        <p>
            <?php if (Yii::$app->user->can('createProject')) { ?>
                <p class="lead">现在还没有项目，点击创建新项目吧.</p>
                <a class="btn btn-lg btn-success" href="<?= \yii\helpers\Url::to(['project/create'])?>">新建项目</a>
            <?php } ?>
            <?php if (Yii::$app->user->can('importUser')) { ?>
                <a class="btn btn-lg btn-success" href="<?= \yii\helpers\Url::to(['user/import'])?>">导入成员</a>
            <?php } ?>
        </p>
    </div>
    <?php } ?>
<!-- title row -->

<div class="row">
    <div class="col-lg-4">
        <h3>功能说明</h3>
        <ul class="list-group">
            <li class="list-group-item"><a target="_blank" href="https://github.com/wuzhc/team/blob/master/docs/%E5%8A%9F%E8%83%BD%E9%9C%80%E6%B1%82.md" class="text-black"><i class="fa fa-car"></i>&nbsp;&nbsp;项目管理</a></li>
            <li class="list-group-item"><a target="_blank" href="https://github.com/wuzhc/team/blob/master/docs/%E5%8A%9F%E8%83%BD%E9%9C%80%E6%B1%82.md" class="text-black"><i class="fa fa-car"></i>&nbsp;&nbsp;任务管理</a></li>
            <li class="list-group-item"><a target="_blank" href="https://github.com/wuzhc/team/blob/master/docs/%E5%8A%9F%E8%83%BD%E9%9C%80%E6%B1%82.md" class="text-black"><i class="fa fa-car"></i>&nbsp;&nbsp;用户管理</a></li>
            <li class="list-group-item"><a target="_blank" href="https://github.com/wuzhc/team/blob/master/docs/%E5%8A%9F%E8%83%BD%E9%9C%80%E6%B1%82.md" class="text-black"><i class="fa fa-car"></i>&nbsp;&nbsp;团队管理</a></li>
<!--            <li class="list-group-item"><a target="_blank" href="https://github.com/wuzhc/team/blob/master/docs/%E6%B6%88%E6%81%AF%E6%8E%A8%E9%80%81.md" class="text-black"><i class="fa fa-car"></i>&nbsp;&nbsp;消息推送</a></li>-->
<!--            <li class="list-group-item"><a target="_blank" href="https://github.com/wuzhc/team/blob/master/docs/%E6%9D%83%E9%99%90RBAC%E5%8A%9F%E8%83%BD%E8%AF%B4%E6%98%8E.md" class="text-black"><i class="fa fa-car"></i>&nbsp;&nbsp;权限管理</a></li>-->
        </ul>
    </div>
    <div class="col-lg-4">
        <h3>技术说明</h3>
        <ul class="list-group">
            <li class="list-group-item"><a target="_blank" href="https://github.com/wuzhc/team/blob/master/docs/%E5%8A%9F%E8%83%BD%E9%9C%80%E6%B1%82.md" class="text-black"><i class="fa fa-motorcycle"></i>&nbsp;&nbsp;RBAC权限功能</a></li>
            <li class="list-group-item"><a target="_blank" href="http://www.yiichina.com/doc/guide/2.0/db-migrations" class="text-black"><i class="fa fa-motorcycle"></i>&nbsp;&nbsp;数据库迁移</a></li>
            <li class="list-group-item"><a target="_blank" href="https://segmentfault.com/a/1190000012895446" class="text-black"><i class="fa fa-motorcycle"></i>&nbsp;&nbsp;命令行实现数据库表结构文档自动生成</a></li>
            <li class="list-group-item"><a target="_blank" href="https://github.com/wuzhc/team/blob/master/docs/%E6%B6%88%E6%81%AF%E6%8E%A8%E9%80%81.md" class="text-black"><i class="fa fa-motorcycle"></i>&nbsp;&nbsp;消息推送和实时动态</a></li>
<!--            <li class="list-group-item"><a target="_blank" href="https://github.com/wuzhc/team/blob/master/docs/%E5%8A%9F%E8%83%BD%E9%9C%80%E6%B1%82.md" class="text-black"><i class="fa fa-motorcycle"></i>&nbsp;&nbsp;团队管理</a></li>-->
<!--            <li class="list-group-item"><a target="_blank" href="https://github.com/wuzhc/team/blob/master/docs/%E6%9D%83%E9%99%90RBAC%E5%8A%9F%E8%83%BD%E8%AF%B4%E6%98%8E.md" class="text-black"><i class="fa fa-motorcycle"></i>&nbsp;&nbsp;权限管理</a></li>-->
        </ul>
    </div>
    <div class="col-lg-4">
        <h3>相关链接</h3>
        <ul class="list-group">
            <li class="list-group-item"><a target="_blank" href="https://github.com/walkor/phpsocket.io" class="text-black"><i class="fa fa-space-shuttle"></i>&nbsp;&nbsp;phpsocket.io</a></li>
            <li class="list-group-item"><a target="_blank" href="http://www.yiichina.com/doc/guide/2.0" class="text-black"><i class="fa fa-space-shuttle"></i>&nbsp;&nbsp;Yii 2.0 权威指南</a></li>
            <li class="list-group-item"><a target="_blank" href="https://v3.bootcss.com/" class="text-black"><i class="fa fa-space-shuttle"></i>&nbsp;&nbsp;Bootstrap响应式框架</a></li>
            <li class="list-group-item"><a target="_blank" href="https://github.com/dmstr/yii2-adminlte-asset" class="text-black"><i class="fa fa-space-shuttle"></i>&nbsp;&nbsp;yii2-adminlte-asset</a></li>
<!--            <li class="list-group-item"><a target="_blank" href="https://github.com/wuzhc/team/blob/master/docs/%E6%B6%88%E6%81%AF%E6%8E%A8%E9%80%81.md" class="text-black"><i class="fa fa-space-shuttle"></i>&nbsp;&nbsp;消息推送</a></li>-->
<!--            <li class="list-group-item"><a target="_blank" href="https://github.com/wuzhc/team/blob/master/docs/%E6%9D%83%E9%99%90RBAC%E5%8A%9F%E8%83%BD%E8%AF%B4%E6%98%8E.md" class="text-black"><i class="fa fa-space-shuttle"></i>&nbsp;&nbsp;权限管理</a></li>-->
        </ul>
    </div>
</div>





