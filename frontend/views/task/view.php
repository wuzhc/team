<?php

/* @var $task \common\models\Task */
/* @var $logs array 任务操作日志 */
/* @var $members array 项目成员 */

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = '任务详情';
$this->params['breadcrumbs'][] = [
    'label' => '任务列表',
    'url'   => ['task/index', 'projectID' => $task->fdProjectID, 'isMe' => 1]
];
$this->params['breadcrumbs'][] = $this->title;
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');

?>
    <style>
        img {
            max-width: 100%;
        }
    </style>
    <div class="row" id="task-view">
        <!--        <div class="col-md-3">-->
        <!--            --><? //= \common\widgets\TaskCategory::widget() ?>
        <!--        </div>-->
        <!-- /.col -->
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Html::encode($task->category->fdName) ?></h3>
                    <a href="<?= Url::to([
                        'task/index',
                        'projectID'  => $task->fdProjectID,
                        'categoryID' => $task->fdTaskCategoryID
                    ]) ?>" class="btn btn-sm btn-default pull-right">返回任务列表</a>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <div class="mailbox-read-info">
                        <h3>
                            <?= Html::encode($task->fdName) ?>
                            <div class="pull-right">
                            <a href="javascript:void(0)" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#assign-task" data-id="<?= $task->id ?>">指派任务</a>
                            <a href="<?= Url::to(['task/update', 'taskID' => $task->id]) ?>" class="btn btn-sm btn-success" data-toggle="modal" data-target="#assign-task" data-id="<?= $task->id ?>">编辑</a>
                            </div>
                        </h3>
                    </div>
                    <!-- /.mailbox-read-info -->
                    <div class="mailbox-controls with-border text-center">
                        <h5><?= Html::encode($task->creator->fdName) ?>
                            <span class="mailbox-read-time"><?= $task->fdCreate ?></span>
                        </h5>
                    </div>
                    <!-- /.mailbox-controls -->
                    <div class="mailbox-read-message" style="overflow: hidden;">
                        <?= \yii\helpers\HtmlPurifier::process($task->taskContent->fdContent) ?>
                    </div>
                    <!-- /.mailbox-read-message -->
                </div>
                <!-- /.box-body -->
                <!--                <div class="box-footer">-->
                <!--                    <ul class="mailbox-attachments clearfix">-->
                <!--                        <li>-->
                <!--                            <span class="mailbox-attachment-icon"><i class="fa fa-file-pdf-o"></i></span>-->
                <!---->
                <!--                            <div class="mailbox-attachment-info">-->
                <!--                                <a href="#" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i>-->
                <!--                                    Sep2014-report.pdf</a>-->
                <!--                                <span class="mailbox-attachment-size">-->
                <!--                          1,245 KB-->
                <!--                          <a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>-->
                <!--                        </span>-->
                <!--                            </div>-->
                <!--                        </li>-->
                <!--                        <li>-->
                <!--                            <span class="mailbox-attachment-icon"><i class="fa fa-file-word-o"></i></span>-->
                <!---->
                <!--                            <div class="mailbox-attachment-info">-->
                <!--                                <a href="#" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> App-->
                <!--                                    Description.docx</a>-->
                <!--                                <span class="mailbox-attachment-size">-->
                <!--                          1,245 KB-->
                <!--                          <a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>-->
                <!--                        </span>-->
                <!--                            </div>-->
                <!--                        </li>-->
                <!--                        <li>-->
                <!--                            <span class="mailbox-attachment-icon has-img"><img-->
                <!--                                        src="-->
                <? //= $directoryAsset ?><!--/img/photo1.png" alt="Attachment"></span>-->
                <!---->
                <!--                            <div class="mailbox-attachment-info">-->
                <!--                                <a href="#" class="mailbox-attachment-name"><i class="fa fa-camera"></i> photo1.png</a>-->
                <!--                                <span class="mailbox-attachment-size">-->
                <!--                          2.67 MB-->
                <!--                          <a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>-->
                <!--                        </span>-->
                <!--                            </div>-->
                <!--                        </li>-->
                <!--                        <li>-->
                <!--                            <span class="mailbox-attachment-icon has-img"><img-->
                <!--                                        src="-->
                <? //= $directoryAsset ?><!--/img/photo2.png" alt="Attachment"></span>-->
                <!---->
                <!--                            <div class="mailbox-attachment-info">-->
                <!--                                <a href="#" class="mailbox-attachment-name"><i class="fa fa-camera"></i> photo2.png</a>-->
                <!--                                <span class="mailbox-attachment-size">-->
                <!--                          1.9 MB-->
                <!--                          <a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>-->
                <!--                        </span>-->
                <!--                            </div>-->
                <!--                        </li>-->
                <!--                    </ul>-->
                <!--                </div>-->
                <!-- /.box-footer -->
                <div class="box-footer box-comments">
                    <?php foreach ($logs as $log) { ?>
                        <div class="box-comment">
                            <!-- User image -->
                            <img class="img-circle img-sm" src="<?= $log['portrait'] ?>" alt="User Image">
                            <div class="comment-text">
                                <span class="username">
                                    <span class="text-muted pull-right"><?= $log['date'] ?></span>
                                </span><!-- /.username -->
                                <?= $log['operator'] . $log['action'], '了', $log['type'], $log['acceptor'] ?
                                    '给' . $log['acceptor'] : '' ?>
                            </div>
                            <!-- /.comment-text -->
                        </div>
                    <?php } ?>
                </div>
                <div class="box-footer">
                    <div class="pull-right">
<!--                        <button type="button" class="btn btn-default task-edit"><i class="fa fa-edit"></i> 编辑</button>-->
                    </div>
                </div>
                <!-- /.box-footer -->
            </div>
            <!-- /. box -->
        </div>
        <!-- /.col -->
    </div>

    <div class="modal fade" id="assign-task" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">将任务指派给谁</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <select class="form-control" id="project-users">
                                <?php if (is_array($members)) { ?>
                                    <?php foreach ($members as $member) { ?>
                                        <option value="<?= $member['id'] ?>"><?= Html::encode($member['name']) ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <input type="hidden" id="task-id" name="taskID" value="">
                        <input type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-success" id="submit-assign">确定</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        <?php $this->beginBlock('jquery') ?>
        $(function () {

            // 指派任务弹窗触发事件
            $('#assign-task').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var taskID = button.data('id');
                var modal = $(this);
                modal.find('.modal-body input[name="taskID"]').val(taskID)
            });

            // 指派任务
            $('#submit-assign').on('click', function () {
                var taskID = $('#task-id').val();
                var acceptor = $('#project-users').val();
                if (!taskID || !acceptor) {
                    $.showBox({msg: '参数错误'});
                    return false;
                }

                // 指派任务
                $.ajax({
                    url: "<?= Url::to(['task/assign'])?>",
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        taskID: taskID,
                        acceptor: acceptor,
                        _csrf: $('input[name="_csrf"]').val()
                    }
                }).done(function (data) {
                    if (data.status == 1) {
                        $.showBox({
                            msg: '指派成功', callback: function () {
                                window.location.reload();
                            }
                        });
                    } else {
                        $.showBox({
                            msg: '指派失败', callback: function () {
                                $('#assign-task').modal('hide');
                            }
                        });
                    }
                }).fail(function (xhr, status, error) {
                    var msg = xhr.responseText || '系统繁忙';
                    $.showBox({
                        msg: msg, callback: function () {
                            $('#assign-task').modal('hide');
                        }
                    });
                });
            });
        });
        <?php $this->endBlock() ?>
    </script>
<?php $this->registerJs($this->blocks['jquery'], \yii\web\View::POS_END); ?>