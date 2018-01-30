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
        <h2>功能说明</h2>

        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
            dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
            ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
            fugiat nulla pariatur.</p>

        <p><a class="btn btn-default" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
    </div>
    <div class="col-lg-4">
        <h2>技术说明</h2>

        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
            dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
            ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
            fugiat nulla pariatur.</p>

        <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
    </div>
    <div class="col-lg-4">
        <h2>参考文档</h2>

        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
            dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
            ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
            fugiat nulla pariatur.</p>

        <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
    </div>
</div>





