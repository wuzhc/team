<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Project */

$this->title = $model->fdName;
$this->params['breadcrumbs'][] = ['label' => $project->fdName, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-view box box-success">
    <div class="box-header">
        <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'fdName',
                'fdName',
                [
                    'label' => '创建者',
                    'attribute' => 'user.fdName',
                ],
                [
                    'label' => '公司',
                    'attribute' => 'company.fdName',
                ],
                'fdDescription',
                [
                    'attribute' => 'fdStatus',
                    'value' => $model->fdStatus == \common\config\Conf::ENABLE ? '可用' : '禁用'
                ],
                'fdCreate',
                'fdUpdate',
            ],
        ]) ?>
    </div>
</div>
