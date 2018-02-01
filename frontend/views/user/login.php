<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = '登录';

$fieldOptions1 = [
    'options'       => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options'       => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>
<style>
    .login-box {
        width: 320px
    }
</style>
<div class="login-box">
    <div class="login-logo">
        <!--<a href="#"><b>ZC</b>shop</a>-->
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">账号登录</p>

        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => true]); ?>

        <?= $form->field($model, 'username', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('用户名'), 'value' => 'superadmin']) ?>

        <?= $form->field($model, 'password', $fieldOptions2)
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('密码'), 'value' => '123456']) ?>

        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <?= Html::submitButton('登录', [
                    'class' => 'btn btn-primary btn-block btn-flat',
                    'name'  => 'login-button'
                ]) ?>
            </div>
            <!-- /.col -->
        </div>

        <?php ActiveForm::end(); ?>
        <!-- /.social-auth-links -->

        <a href="#">忘记密码</a><br>
        <a href="<?= Yii::$app->urlManager->createUrl(['user/signup']); ?>" class="text-center">注册一个新用户</a>

    </div>
    <!-- /.login-box-body -->
    <div class="text-center" style="margin-top: 20px">
        <h4>测试账号</h4>
        <h5>超级管理员: superadmin 123456</h5>
        <h5>普通管理员: zhangfei 123456</h5>
        <h5>普通成员:   mayun 123456</h5>
    </div>
</div><!-- /.login-box -->
