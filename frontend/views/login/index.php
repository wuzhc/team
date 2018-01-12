<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = '登录';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>
<style>
    .login-box{width: 320px}
</style>
<div class="login-box">
    <div class="login-logo">
        <!--<a href="#"><b>ZC</b>shop</a>-->
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">会员中心登录</p>

        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => true]); ?>

        <?= $form
            ->field($model, 'username', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('用户名')]) ?>

        <?= $form
            ->field($model, 'password', $fieldOptions2)
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('密码')]) ?>

        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <?= Html::submitButton('登录', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
            <!-- /.col -->
        </div>

        <?php ActiveForm::end(); ?>
        <!-- /.social-auth-links -->

        <div class="social-auth-links text-center">
            <p>- 第三方账号登录 -</p>
            <a href="#" class="btn btn-block btn-warning">
                <i class="fa fa-google-plus"></i>
                QQ登录
            </a>
            <a href="#" class="btn btn-block btn-danger">
                <i class="fa fa-google-plus"></i>
                微信登录
            </a>
            <a href="http://openapi.baidu.com/oauth/2.0/authorize?response_type=code&redirect_uri=<?=BAIDU_CALLBACK?>&client_id=<?=BAIDU_API_KEY?>" class="btn btn-block btn-facebook">
                <i class="fa fa-facebook"></i>
                百度登录
            </a>
        </div>

        <a href="#">忘记密码</a><br>
        <a href="<?= Yii::$app->urlManager->createUrl(['member/signup']); ?>" class="text-center">注册一个新用户</a>

    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->

<?php $this->beginBlock('jquery') ?>
$(function(){
$(".sys-login").click(function(e){
e.preventDefault();
$(this).attr("disabled","disabled");
$("#login-form").submit();
});
});
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['jquery'], \yii\web\View::POS_END); ?>
