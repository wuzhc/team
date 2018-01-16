<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\CompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '公司';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-index box box-success">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
        <?= Html::a('新建', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'label' => '主键',
                    'attribute' => 'id',
                    'headerOptions' => ['width' => 50]
                ],
                'fdName',
                [
                    'label' => '创建者',
                    'attribute' => 'fdCreatorID',
                    'value' => function($searchModel) {
                        return $searchModel->user->fdName;
                    },
                ],
                [
                    'label' => '简介',
                    'attribute' => 'fdDescription',
                    'value' => \yii\helpers\StringHelper::truncateWords($searchModel->fdDescription,20)
                ],
                [
                    'label'=>'状态',
                    'attribute' => 'fdStatus',
                    'value' => function ($searchModel) {
                        $state = [
                            '1' => '可用',
                            '2' => '禁用',
                        ];
                        return $state[$searchModel->fdStatus];
                    },
                    'filter' => ['1' => '可用', '2' => '禁用'],
                ],
                 'fdCreate',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
