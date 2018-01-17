<?php

$this->title = '导入成员';
?>
<div class="box box-success" id="import-user-container">
    <!-- /.box-header -->
    <form role="form">
        <div class="box-header with-border">
            <a tabindex="0" class="btn btn-success" data-toggle="popover" data-trigger="focus">导入规则说明</a>
        </div>
        <div class="box-body">
            <textarea class="form-control" rows="15" placeholder="多个成员邮箱英文逗号隔开" id="user-content"></textarea>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="button" class="btn btn-default" id="clear-user">清空</button>
            <button type="button" class="btn btn-success pull-right" id="ensure-submit">确定</button>
        </div>
    </form>
</div>

<script>
    <?php $this->beginBlock('jquery') ?>
    $(function () {

        // 规则说明
        $('[data-toggle="popover"]').popover({
            title: '规则说明',
            content: '多个邮箱需要用英文状态下逗号隔开'
        });

        $('#import-user-container').on('click', '#ensure-submit', function () {
            var content = $.trim($('#user-content').val());
            if (!content) {
                $.showBox({msg: '内容不能为空'});
                return false;
            }

            var url = "<?=\yii\helpers\Url::to(['import-user'])?>";
            $.ajax({
                type: 'POST',
                url: url,
                data: {emails: content},
                dataType: 'json'
            }).done(function (data) {
                if (data.status === 1) {
                    var unAvailableEmails = data.unAvailable || [];
                    var len = unAvailableEmails.length;
                    if (len > 0) {
                        var html = '<div style="max-height: 250px;overflow-y: scroll">';
                        for (var i = 0; i < len; i++) {
                            html += '<p>' + unAvailableEmails[i] + '</p>';
                        }
                        html += '</div>';
                        $.showBox({
                            title: '以下邮箱格式不正确',
                            html: html,
                            isClose: false
                        })
                    } else {
                        $.showBox({
                            msg: '导入成功，等待成员邮箱验证',
                            callback: function () {
                                window.location.href = "<?=\yii\helpers\Url::to(['team/index'])?>";
                            }
                        })
                    }
                } else {
                    $.showBox({msg: '导入失败'});
                }
            }).fail(function () {
                $.showBox({msg: '系统繁忙~'});
            })
        }).on('click', '#clear-user', function () {
            $('#user-content').val('');
        })

    });
    <?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['jquery'], \yii\web\View::POS_END); ?>
