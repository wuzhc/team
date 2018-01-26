<?php

/* @var $task \common\models\Task */
/* @var $logs array 任务操作日志 */

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
                        'projectID' => $task->fdProjectID,
                        'categoryID' => $task->fdTaskCategoryID
                    ]) ?>" class="btn btn-sm btn-default pull-right">返回任务列表</a>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <div class="mailbox-read-info">
                        <h3><?= Html::encode($task->fdName) ?></h3>
                        <h5><?= Html::encode($task->creator->fdName) ?>
                            <span class="mailbox-read-time pull-right"><?= $task->fdCreate ?></span></h5>
                    </div>
                    <!-- /.mailbox-read-info -->
                    <div class="mailbox-controls with-border text-center">
                        <div class="btn-group">
                            <button type="button" class="task-delete btn btn-default btn-sm <?= $task->fdCreatorID ==
                            Yii::$app->user->id ? '' : 'hidden' ?>" data-toggle="tooltip" data-container="body" title=""
                                    data-original-title="删除">
                                <i class="fa fa-trash-o"></i>
                            </button>
                            <button type="button" class="task-edit btn btn-default btn-sm <?= $task->fdCreatorID ==
                            Yii::$app->user->id ? '' : 'hidden' ?>" data-toggle="tooltip" data-container="body" title=""
                                    data-original-title="编辑">
                                <i class="fa fa-edit"></i>
                            </button>
                        </div>
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
                                <?= $log['operator'] . $log['action'], '了', $log['type'], $log['accept'] ?
                                    '给' . $log['accept'] : '' ?>
                            </div>
                            <!-- /.comment-text -->
                        </div>
                    <?php } ?>
                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <button type="button" class="btn btn-default"><i class="fa fa-reply"></i> Reply</button>
                        <button type="button" class="btn btn-default"><i class="fa fa-share"></i> Forward</button>
                    </div>
                    <button type="button" class="btn btn-default"><i class="fa fa-trash-o"></i> Delete</button>
                    <button type="button" class="btn btn-default"><i class="fa fa-print"></i> Print</button>
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
            $('#task-view').on('click', '.task-edit', function () {
                window.location.href = "<?= Url::to(['task/update', 'taskID' => $task->id]) ?>"
            });
        });
        <?php $this->endBlock() ?>
    </script>
<?php $this->registerJs($this->blocks['jquery'], \yii\web\View::POS_END); ?>