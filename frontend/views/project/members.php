<?php

use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = '设置成员';
$this->params['breadcrumbs'][] = ['label' => '项目列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="project-members box box-success">
        <?php if (!empty($teams)) { ?>
            <?php foreach ($teams as $team) { ?>
                <div class="box-header with-border">
                    <h3 class="box-title pull-right" id="team-name"><b><?= $team['name'] ?></b></h3>
                    <button class="btn btn-success btn-xs select-all">全选</button>
                    <button class="btn btn-default btn-xs select-none">取消全选</button>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <?php if (!empty($team['members'])) { ?>
                        <?php foreach ($team['members'] as $member) { ?>
                            <label class="checkbox-inline" style="margin-bottom: 20px">
                                <input type="checkbox" value="<?= $member['id'] ?>" name="members"
                                       data-id="<?= $member['id'] ?>">
                                <img src="<?= !empty($member['portrait']) ? $member['portrait'] :
                                    common\config\Conf::USER_PORTRAIT ?>" alt="User Image"
                                     class="img-thumbnail"
                                     width="100px"
                                     height="100px">
                                <span class="users-list-name text-center" href="#"><?= $member['name'] ?></span>
                            </label>
                        <?php } ?>
                    <?php } ?>
                </div>
                <!-- /.box-body -->
                <!-- /.box-footer -->
            <?php } ?>
            <button type="button" class="btn btn-success btn-lg" id="submit-members">保存</button>
        <?php } else { ?>
            <div class="jumbotron">
                <h2>欢迎使用Team!</h2>
                <p>
                <?php if (Yii::$app->user->can('importUser')) { ?>
                    <p class="lead">现在还没有成员，点击导入成员吧.</p>
                    <a class="btn btn-lg btn-success" href="<?= \yii\helpers\Url::to(['user/import']) ?>">导入成员</a>
                <?php } ?>
                <?php if (Yii::$app->user->can('createProject')) { ?>
                    <a class="btn btn-lg btn-success" href="<?= \yii\helpers\Url::to(['project/create']) ?>">新建项目</a>
                <?php } ?>
                </p>
            </div>
        <?php } ?>
    </div>

    <script>
        <?php $this->beginBlock('jquery') ?>
        $(function () {

            (function () {
                $.ajax({
                    url: "<?= Url::to(['project/get-has-join-member-i-ds', 'id' => $_GET['id']])?>",
                    type: 'GET',
                    dataType: 'JSON'
                }).done(function (data) {
                    var memberIDs = data.memberIDs || [];
                    if (memberIDs.length === 0) {
                        return false;
                    }

                    $.each($('input[name="members"]'), function () {
                        if ($.inArray((String)($(this).data('id')), memberIDs) !== -1) {
                            $(this).attr('checked', 'checked');
                            $(this).next('img').addClass('img-circle').removeClass('img-thumbnail');
                        }
                    });
                });
            })();

            // 头像选中状态交互
            $('input[type="checkbox"]').on('click', function () {
                if ($(this).is(':checked')) {
                    $(this).next('img').addClass('img-circle').removeClass('img-thumbnail');
                } else {
                    $(this).next('img').addClass('img-thumbnail').removeClass('img-circle');
                }
            });

            $('.project-members').on('click', '.select-all', function () {
                $(this).parent().next().find('input[type="checkbox"]').attr('checked', true);
                $(this).parent().next().find('img').addClass('img-circle').removeClass('img-thumbnail');
            }).on('click', '.select-none', function () {
                $(this).parent().next().find('input[type="checkbox"]').removeAttr('checked');
                $(this).parent().next().find('img').addClass('img-thumbnail').removeClass('img-circle');
            }).on('click', '#submit-members', function () {
                var self = $(this);
                var url = "<?= Url::to(['project/members', 'id' => $_GET['id']])?>";
                self.attr('disabled', 'disabled');

                var members = [];
                $.each($('input[name="members"]:checked'), function () {
                    members.push($(this).val());
                });

                if (members.length === 0) {
                    $.showBox({msg: '请选择成员'});
                    self.removeAttr('disabled');
                    return false;
                }

                $.ajax({
                    url: url,
                    data: {members: members},
                    type: 'POST',
                    dataType: 'json'
                }).done(function (data) {
                    if (data.status === 1) {
                        $.showBox({
                            msg: data.msg, callback: function () {
                                window.location.href = url;
                            }
                        });
                    } else {
                        $.showBox({msg: data.msg});
                        self.removeAttr('disabled');
                    }
                }).fail(function () {
                    $.showBox({msg: '系统繁忙'});
                    self.removeAttr('disabled');
                });
            });
        });
        <?php $this->endBlock() ?>
    </script>
<?php $this->registerJs($this->blocks['jquery'], \yii\web\View::POS_END); ?>