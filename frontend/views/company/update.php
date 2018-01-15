<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Company */

$this->title = '编辑: ' . $model->fdName;
$this->params['breadcrumbs'][] = ['label' => '公司', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fdName, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="company-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
