<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ProjectSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="project-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'fdName') ?>

    <?= $form->field($model, 'fdCreatorID') ?>

    <?= $form->field($model, 'fdCompanyID') ?>

    <?= $form->field($model, 'fdDescription') ?>

    <?php // echo $form->field($model, 'fdStatus') ?>

    <?php // echo $form->field($model, 'fdCreate') ?>

    <?php // echo $form->field($model, 'fdUpdate') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
