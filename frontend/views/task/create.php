<?php

use frontend\assets\AdminLtePluginAsset;
use frontend\assets\AppAsset;

$this->title = '新建任务';
$this->registerJsFile('//unpkg.com/wangeditor/release/wangEditor.min.js', [
    AppAsset::className(),
    'depends' => 'frontend\assets\AppAsset'
]);
AdminLtePluginAsset::register($this);
?>
    <div class="row" id="task-create">
        <div class="col-md-3">
            <?= \common\widgets\TaskCategory::widget() ?>
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= isset($category['name']) ? $category['name'] : '未知' ?></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <input id="csrf-token" name="<?= Yii::$app->request->csrfParam ?>" type="hidden"
                           value="<?= Yii::$app->request->csrfToken ?>">
                    <div class="form-group">
                        <input class="form-control" placeholder="标题" name="title">
                        <span class="help-block text-red hidden">标题不能为空.</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" value="<?= $category['name'] ?>" readonly>
                    </div>
                    <div class="form-group">
                        <div id="editor">
                        </div>
                        <span class="help-block text-red hidden">标题不能为空.</span>
                    </div>
                    <div class="form-group">
                        任务等级：
                        <label class="radio-inline">
                            <input type="radio" name="level" value="0" checked="checked"> 蓝色
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="level" value="2"> 黄色
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="level" value="1"> 橙色
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="level" value="2"> 红色
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
                        <button type="button" class="btn btn-default"><i class="fa fa-pencil"></i> 草稿</button>
                        <button type="button" class="btn btn-success" id="submit-task"><i class="fa fa-envelope-o"></i>
                            确定
                        </button>
                    </div>
                    <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> 返回</button>
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
            var projectID = "<?= $projectID?>";
            var categoryID = "<?= $categoryID?>";

            var E = window.wangEditor;
            var editor = new E('#editor');
            // 或者 var editor = new E( document.getElementById('editor') )
            editor.customConfig.pasteFilterStyle = false;
            editor.customConfig.uploadImgShowBase64 = true;
            editor.create();
//        editor.txt.html('写点什么吧');

            $('input[type="radio"]').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });

            $('#task-create').on('click', '#task-category>li', function () {
                if (confirm('确定要切换到任务列表页吗？')) {
                    var categoryID = $(this).data('id');
                    var url = "<?= \yii\helpers\Url::to([
                            'task/index',
                            'projectID' => $projectID
                        ])?>&categoryID=" + categoryID;
                    window.location.href = url;
                }
            })
                .on('click', '#submit-task', function () {
                    var nameInput = $('input[name="title"]');
                    var name = $.trim(nameInput.val());
                    if (!name) {
                        nameInput.next('.help-block').removeClass('hidden');
                        return false;
                    } else {
                        nameInput.next('.help-block').addClass('hidden');
                    }

                    var url = "<?= \yii\helpers\Url::to([
                        'task/create',
                        'projectID'  => $projectID,
                        'categoryID' => $categoryID
                    ])?>";
                    $.ajax({
                        url: url,
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
                                msg: '创建成功',
                                callback: function () {
                                    window.location.href = "<?=\yii\helpers\Url::to([
                                        'task/index',
                                        'me' => 1,
                                        'projectID'  => $projectID,
                                        'categoryID' => $categoryID,
                                    ])?>"
                                }
                            });
                        } else {
                            $.showBox({
                                msg: '创建失败【' + data.msg + '】'
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