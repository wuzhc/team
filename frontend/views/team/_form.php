<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Team */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="team-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'fdName')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'fdCreatorID')->textInput() ?>

        <?= $form->field($model, 'fdCompanyID')->textInput() ?>

        <?= $form->field($model, 'fdDescription')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'fdStatus')->textInput() ?>

        <?= $form->field($model, 'fdCreate')->textInput() ?>

        <?= $form->field($model, 'fdUpdate')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
