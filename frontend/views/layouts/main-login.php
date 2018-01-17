<?php
use dmstr\web\AdminLteAsset;
use frontend\assets\AppAsset;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

AdminLteAsset::register($this);
AppAsset::registerJsFile($this, 'js/showBox.js');

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script>
        <?php $this->beginBlock('jquery') ?>
        $(function () {

            /*弹窗提示*/
            var flag = <?= (isset($_GET['showmsg']) && $_GET['showmsg'] == 1) ? 1 : 0?>;
            if (flag === 1) {
                $.getJSON('<?= \yii\helpers\Url::to(['default/show-box'])?>', function (data) {
                    if (data.msg) {
                        $.showBox({msg: data.msg, seconds: data.seconds});
                    }
                });
            }
            /*弹窗提示*/

        });
        <?php $this->endBlock() ?>
    </script>
    <?php $this->registerJs($this->blocks['jquery'], \yii\web\View::POS_END); ?>
</head>
<body class="login-page">

<?php $this->beginBody() ?>

<!--弹窗-->
<div class="modal fade" id="showMsgModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog"
         style="position: absolute;left: 50%;top: 45%;transform:translateX(-50%) translateY(-50%);">
        <div class="modal-content">
            <div class="modal-header hidden">
                <button type="button" class="close none" data-dismiss="modal"><span
                            aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <p class="text-center"></p>
            </div>
            <div class="modal-footer hidden">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary">确定</button>
            </div>
        </div>
    </div>
</div>
<!--弹窗-->

<?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
