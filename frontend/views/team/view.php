<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Team */

$this->title = $team->fdName;
$this->params['breadcrumbs'][] = ['label' => 'Teams', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-success">
    <div class="box-header with-border">
        <?php if (Yii::$app->user->can('editTeam')) { ?>
            <a href="<?= \yii\helpers\Url::to(['team/update', 'id' => $team->id]) ?>"
               class="btn-sm btn-danger pull-right" id="team-url">编辑</a>
        <?php } ?>
    </div>
    <!-- /.box-header -->
    <div class="box-body no-padding">
        <ul class="users-list clearfix" id="team-member">
            <?php if ($members) { ?>
                <?php foreach ($members as $member) { ?>
                    <li>
                        <img src="<?= $member->fdPortrait ?: \common\config\Conf::USER_PORTRAIT ?>" alt="User Image">
                        <a class="users-list-name" href="#"><?= $member->fdName ?></a>
                        <span class="users-list-date"><?= $member->fdPosition ?></span>
                    </li>
                <?php } ?>
            <?php } ?>
        </ul>
        <!-- /.users-list -->
    </div>
    <!-- /.box-body -->
    <!-- /.box-footer -->
</div>

