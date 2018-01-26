<?php

/** @var $task \common\models\Task */

use yii\helpers\Url;
use yii\helpers\Html;
use frontend\assets\AdminLtePluginAsset;
use frontend\assets\AppAsset;
use yii\helpers\HtmlPurifier;

$this->title = '编辑任务';
AdminLtePluginAsset::register($this);
$this->registerJsFile('//unpkg.com/wangeditor/release/wangEditor.min.js', [
    AppAsset::className(),
    'depends' => 'frontend\assets\AppAsset'
]);

?>
    <div class="row" id="task-update">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Html::encode($task->category->fdName) ?></h3>
                    <a href="<?= Url::to([
                        'task/index',
                        'projectID' => $task->fdProjectID,
                        'categoryID' => $task->fdTaskCategoryID
                    ]) ?>" class="btn btn-sm btn-default pull-right">返回任务列表</a>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <input id="csrf-token" name="<?= Yii::$app->request->csrfParam ?>" type="hidden"
                           value="<?= Yii::$app->request->csrfToken ?>">
                    <div class="form-group">
                        <input class="form-control" placeholder="标题" name="title"
                               value="<?= Html::encode($task->fdName) ?>">
                        <span class="help-block text-red hidden">标题不能为空.</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" value="<?= Html::encode($task->category->fdName) ?>"
                               readonly>
                    </div>
                    <div class="form-group">
                        <div id="editor">

                        </div>
                        <span class="help-block text-red hidden">标题不能为空.</span>
                    </div>
                    <div class="form-group">
                        任务等级：
                        <label class="radio-inline">
                            <input type="radio" name="level" value="0" <?= $task->fdLevel == 0 ? 'checked="checked"' :
                                '' ?>> 蓝色
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="level" value="1" <?= $task->fdLevel == 1 ? 'checked="checked"' :
                                '' ?>> 黄色
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="level" value="2" <?= $task->fdLevel == 2 ? 'checked="checked"' :
                                '' ?>> 橙色
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="level" value="3" <?= $task->fdLevel == 3 ? 'checked="checked"' :
                                '' ?>> 红色
                        </label>
                    </div>
                    <div class="form-group">
                        <div class="btn btn-default btn-file">
                            <i class="fa fa-paperclip"></i> 附件
                            <input type="file" name="attachment">
                        </div>
                        <p class="help-block">Max. 32MB</p>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-right">
                        <!--                        <button type="button" class="btn btn-default"><i class="fa fa-pencil"></i> 草稿</button>-->
                        <button type="button" class="btn btn-success" id="submit-task"><i class="fa fa-envelope-o"></i>
                            确定
                        </button>
                    </div>
                    <!--                    <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> 返回</button>-->
                </div>
                <!-- /.box-footer -->
            </div>
            <!-- /. box -->
        </div>
        <!-- /.col -->
    </div>

    <script>
        <?php $this->beginBlock('jquery') ?>
        $(function () {

            var E = window.wangEditor;
            var editor = new E('#editor');
            editor.customConfig.pasteFilterStyle = false;
            editor.customConfig.uploadImgShowBase64 = true;
            editor.create();
            editor.txt.html('<?=addslashes(HtmlPurifier::process($task->taskContent->fdContent))?>');

            $('input[type="radio"]').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });

            $('#task-update').on('click', '#submit-task', function () {
                var nameInput = $('input[name="title"]');
                var name = $.trim(nameInput.val());
                if (!name) {
                    nameInput.next('.help-block').removeClass('hidden');
                    return false;
                } else {
                    nameInput.next('.help-block').addClass('hidden');
                }

                $.ajax({
                    url: "<?= Url::to(['task/update', 'taskID' => $task->id])?>",
                    type: 'POST',
                    data: {
                        name: name,
                        level: $('input[name="level"]:checked').val(),
                        content: editor.txt.html(),
                        _csrf: $('input[name="_csrf"]').val()
                    },
                    dataType: 'json'
                }).done(function (data) {
                    if (data.status === 1) {
                        $.showBox({
                            msg: '编辑成功',
                            callback: function () {
                                window.location.href = "<?=Url::to(['task/view', 'taskID' => $task->id])?>"
                            }
                        });
                    } else {
                        $.showBox({
                            msg: '编辑失败【' + data.msg + '】'
                        });
                    }
                }).fail(function (xhr, status, error) {
                    var msg = xhr.responseText || '系统繁忙～';
                    $.showBox({msg: msg, seconds: 3000});
                });
            });
        });
        <?php $this->endBlock() ?>
    </script>
<?php $this->registerJs($this->blocks['jquery'], \yii\web\View::POS_END); ?>