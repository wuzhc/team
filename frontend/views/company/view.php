<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Company */

$this->title = $model->fdName;
$this->params['breadcrumbs'][] = ['label' => '公司', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-view box box-primary">
    <div class="box-header">
        <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => '你确定要删除吗?',
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
                [
                    'label' => '创建者',
                    'attribute' => 'user.fdName',
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
