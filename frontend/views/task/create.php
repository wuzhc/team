<?php

use frontend\assets\AppAsset;

$this->title = '新建任务';
$this->registerJsFile(
    '//unpkg.com/wangeditor/release/wangEditor.min.js',
    [AppAsset::className(), 'depends' => 'frontend\assets\AppAsset']
);
?>
<div class="row">
    <div class="col-md-3">

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">清单</h3>

                <div class="box-tools">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked">
                    <?php if (!empty($categories)) { ?>
                        <?php foreach ($categories as $k => $category) { ?>
                            <li>
                                <a href="javaScript:void(0)" data-id="<?= $category->id ?>"><i
                                            class="fa fa-inbox"></i><?= $category->fdName ?></a>
                            </li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /. box -->
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">标签</h3>

                <div class="box-tools">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked">
                    <?php if (!empty($labels)) { ?>
                        <?php foreach ($labels as $k => $label) { ?>
                            <li>
                                <a href="javaScript:void(0)"><i
                                            class="fa fa-circle-o text-<?= $label->fdColor ?>"></i><?= $label->fdName ?>
                                </a>
                            </li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
    <div class="col-md-9">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">课堂2.0版本</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="form-group">
                    <input class="form-control" placeholder="标题">
                </div>
                <div class="form-group">
                    <input class="form-control" placeholder="标签">
                </div>
                <div class="form-group">
                    <div id="editor">
                        <p>欢迎使用 <b>wangEditor</b> 富文本编辑器</p>
                    </div>
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
                    <button type="button" class="btn btn-default"><i class="fa fa-pencil"></i> 预览</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-envelope-o"></i> 确定</button>
                </div>
                <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> 清空</button>
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
        // 或者 var editor = new E( document.getElementById('editor') )
        editor.customConfig.pasteFilterStyle = false;
        editor.customConfig.uploadImgShowBase64 = true;
        editor.create();
        editor.txt.html('写点什么吧')
    });
    <?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['jquery'], \yii\web\View::POS_END); ?>