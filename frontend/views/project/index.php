<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '项目';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-index box box-success">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
        <?= Html::a('新建项目', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
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
                [
                    'label' => '名称',
                    'attribute' => 'fdName',
                    'value' => function($searchModel) {
                        return \yii\helpers\StringHelper::truncate($searchModel->fdName,15);
                    }
                ],
                [
                    'label' => '创建者',
                    'attribute' => 'fdCreatorID',
                    'value' => function($searchModel) {
                        return $searchModel->user->fdName;
                    },
                    'headerOptions' => ['width' => 100]
                ],
                [
                    'label' => '公司',
                    'attribute' => 'fdCompanyID',
                    'value' => function($searchModel) {
                        return $searchModel->company->fdName;
                    },
                    'headerOptions' => ['width' => 100]
                ],
                [
                    'label' => '简介',
                    'attribute' => 'fdDescription',
                    'value' => function($searchModel) {
                        return \yii\helpers\StringHelper::truncate($searchModel->fdDescription,20);
                    }
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
                // 'fdUpdate',
                [
                    'label' => '成员管理',
                    'value' => function ($searchModel) {
                        return '<a href="'. \yii\helpers\Url::to(['project/members', 'id' => $searchModel->id]) .'">设置</a>';
                    },
                    'format' => 'html',
                    'headerOptions' => ['width' => 80]
                ],

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
