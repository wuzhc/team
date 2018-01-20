<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Project */

$this->title = '编辑项目: ';
$this->params['breadcrumbs'][] = ['label' => '项目', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fdName, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="project-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
