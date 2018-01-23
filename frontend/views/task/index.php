<?php

use frontend\assets\AdminLtePluginAsset;
use frontend\assets\AppAsset;
use yii\helpers\Url;

$this->title = '任务列表';
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
AdminLtePluginAsset::register($this);
AppAsset::registerJsFile($this, 'js/template.js')
?>
    <div class="row" id="task-index">
        <div class="col-md-3">
            <?= \common\widgets\TaskCategory::widget() ?>
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">课堂2.0版本</h3>
                    <a href="javaScript:void(0)" class="btn-sm btn-danger pull-right" id="team-url">新建任务</a>

                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <div class="mailbox-controls">
                        <!-- Single button -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                任务进度 <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#">未处理</a></li>
                                <li><a href="#">正在进行</a></li>
                                <li><a href="#">已完成</a></li>
                            </ul>
                        </div>
                        <div class="box-tools pull-right">
                            <div class="has-feedback">
                                <input type="text" class="form-control input-sm" placeholder="Search Mail">
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mailbox-messages">
                        <table class="table table-hover table-striped">
                            <tbody id="task-list"></tbody>
                        </table>
                        <!-- /.table -->
                    </div>
                    <!-- /.mail-box-messages -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer no-padding">
                    <div class="mailbox-controls">
                        <!-- /.btn-group -->
                        <div class="pull-right">
                            1-50/<span id="task-total"></span>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i>
                                </button>
                                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i>
                                </button>
                            </div>
                            <!-- /.btn-group -->
                        </div>
                        <!-- /.pull-right -->
                    </div>
                </div>
            </div>
            <!-- /. box -->
        </div>
        <!-- /.col -->
    </div>

    <script type="text/html" id="task-template">
        <% for(var i = 0, len = list.length; i < len; i++) { %>
        <tr>
            <td ><input type="checkbox"></td>
            <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
            <td class="mailbox-name"><a href="read-mail.html"><%=list[i].creator%></a></td>
            <td class="mailbox-subject">
                <a href="#" class="text-black" title="<%=list[i].originName%>">
                    <small class="text-success">优化</small>&nbsp;&nbsp;
                    <%=list[i].name%>
                </a>
            </td>
            <td class="mailbox-attachment">
                <% if (list[i].process == 0) { %>
                    <a href="javascript:void(0)" class="text-default" data-id="<%=list[i].id%>"><i class="fa fa-play" title="开始任务"></i></a>
                <% } else if (list[i].process == 1) { %>
                    <a href="javascript:void(0)" class="text-warning" data-id="<%=list[i].id%>"><i class="fa fa-pause" title="暂停任务"></i></a>
                <% } else if (list[i].process == 2) { %>
                <a href="javascript:void(0)" class="text-success" data-id="<%=list[i].id%>"><i class="fa fa-check" title="已完成"></i></a>
                <% } %>
            </td>
            <td class="mailbox-date"><%=list[i].create%></td>
            <td>
                <a href="javascript:void(0)" class="text-muted" data-id="<%=list[i].id%>"><i class="fa fa-trash-o" title="删除任务"></i></a>
            </td>
        </tr>
        <% } %>
    </script>

    <script type="text/html" id="none-task-template">
        <div class="jumbotron">
            <p>
            <p class="lead">现在还没有新任务，点击创建一个新任务试试吧.</p>
            <a class="btn btn-lg btn-success" href="<?= \yii\helpers\Url::to(['task/create'])?>&projectID=1&categoryID=1">新建任务</a>
            </p>
        </div>
    </script>

    <script>
        <?php $this->beginBlock('taskList') ?>
        $(function () {



            $('#task-index').on('click', '#task-category>li', function () {
                var categoryID = $(this).data('id');
                renderList({categoryID: categoryID});
            })
            // Enable check and uncheck all functionality
                .on('click', '.checkbox-toggle', function () {
                    var clicks = $(this).data('clicks');
                    if (clicks) {
                        //Uncheck all checkboxes
                        $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
                        $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
                    } else {
                        //Check all checkboxes
                        $(".mailbox-messages input[type='checkbox']").iCheck("check");
                        $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
                    }
                    $(this).data("clicks", !clicks);
                })
                // Handle starring for glyphicon and font awesome
                .on('click', '.mailbox-star', function (e   ) {
                    e.preventDefault();
                    //detect type
                    var $this = $(this).find("a > i");
                    var glyph = $this.hasClass("glyphicon");
                    var fa = $this.hasClass("fa");

                    //Switch states
                    if (glyph) {
                        $this.toggleClass("glyphicon-star");
                        $this.toggleClass("glyphicon-star-empty");
                    }

                    if (fa) {
                        $this.toggleClass("fa-star");
                        $this.toggleClass("fa-star-o");
                    }
                });

            function renderList(options) {
                var params = $.extend({
                    totalInit: 1,
                    limit: 10,
                    offset: 0
                }, options);
                var url = "<?=\yii\helpers\Url::to(['task/list', 'projectID' => $_GET['projectID']])?>";
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: params,
                    dataType: 'json'
                }).done(function (data) {
                    if (params.totalInit === 1) {
                        $('#task-total').val(data.data.total || 0);
                    }

                    var list = data.data.list || [];
                    var len = list.length;
                    var html = '';
                    if (len > 0) {
                        html = template.render('task-template', {list: list});
                        $('#task-list').html(html);
                        $('.mailbox-messages input[type="checkbox"]').iCheck({
                            checkboxClass: 'icheckbox_flat-blue',
                            radioClass: 'iradio_flat-blue'
                        });
                    } else {
                        html = template.render('none-task-template');
                        $('#task-list').html(html);
                    }
                }).fail(function () {
                    $.showBox({msg: '系统繁忙~'});
                })
            }

            renderList({});

        })
        ;
        <?php $this->endBlock() ?>
    </script>
<?php $this->registerJs($this->blocks['taskList'], \yii\web\View::POS_END); ?>