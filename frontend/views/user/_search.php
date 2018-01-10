<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'fdName') ?>

    <?= $form->field($model, 'fdLogin') ?>

    <?= $form->field($model, 'fdStatus') ?>

    <?= $form->field($model, 'fdRoleID') ?>

    <?php // echo $form->field($model, 'fdPhone') ?>

    <?php // echo $form->field($model, 'fdEmail') ?>

    <?php // echo $form->field($model, 'fdPortrait') ?>

    <?php // echo $form->field($model, 'fdCreate') ?>

    <?php // echo $form->field($model, 'fdVerify') ?>

    <?php // echo $form->field($model, 'fdLastIP') ?>

    <?php // echo $form->field($model, 'fdLastTime') ?>

    <?php // echo $form->field($model, 'fdPwdHash') ?>

    <?php // echo $form->field($model, 'fdPwdResetToken') ?>

    <?php // echo $form->field($model, 'fdAuthKey') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
