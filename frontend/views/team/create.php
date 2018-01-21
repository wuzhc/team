<?php

/* @var $this yii\web\View */
/* @var $model common\models\Team */

$this->title = '新建团队';
$this->params['breadcrumbs'][] = ['label' => '团队列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-create">

    <div class="project-form box box-success">
        <form id="w0" action="<?= \yii\helpers\Url::to(['team/create'])?>" method="post">
            <input type="hidden" name="<?= Yii::$app->request->csrfParam?>" value="<?= Yii::$app->request->csrfToken?>">
            <div class="box-body table-responsive">

                <div class="form-group field-project-fdname required">
                    <label class="control-label" for="project-fdname">名称</label>
                    <input type="text" id="project-fdname" class="form-control" name="name" maxlength="32"
                           aria-required="true">

                    <div class="help-block"></div>
                </div>
                <div class="form-group field-project-fddescription">
                    <label class="control-label" for="project-fddescription">描述</label>
                    <textarea id="project-fddescription" class="form-control" name="desc" rows="10"
                              cols="30" placeholder="随便写点什么吧"></textarea>

                    <div class="help-block"></div>
                </div>

                <div class="form-group">
                    <?php if ($members) { ?>
                        <?php foreach ($members as $member) { ?>
                            <label class="checkbox-inline" style="margin-bottom: 20px">
                                <input type="checkbox" id="inlineCheckbox1" value="<?= $member->id?>" name="members[]">
                                <img src="<?= $member->fdPortrait ?: \common\config\Conf::USER_PORTRAIT?>" alt="User Image" class="img-thumbnail" width="100px" height="100px">
                                <span class="users-list-name text-center" href="#"><?= $member->fdName?></span>
                            </label>
                        <?php } ?>
                    <?php } ?>
                </div>

            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-success btn-flat">保存</button>
            </div>
        </form>
    </div>

</div>

<script>
    <?php $this->beginBlock('jquery') ?>
    $(function () {
        $('input[type="checkbox"]').on('click', function () {
            if ($(this).is(':checked')) {
                $(this).next('img').addClass('img-circle').removeClass('img-thumbnail');
            } else {
                $(this).next('img').addClass('img-thumbnail').removeClass('img-circle');
            }
        });
    });
    <?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['jquery'], \yii\web\View::POS_END); ?>
