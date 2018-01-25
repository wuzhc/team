<?php

use frontend\assets\AdminLtePluginAsset;
use frontend\assets\AppAsset;
use yii\helpers\Url;

$this->title = $_GET['me'] == 1 ? '我的任务' : '所有任务';
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
                    <h3 class="box-title" id="task-category-title">全部</h3>
                    <a href="javaScript:void(0)" class="btn btn-sm btn-danger pull-right hidden" id="task-category-url">新建任务</a>
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
                                <li><a href="#" data-type="stop">未处理</a></li>
                                <li><a href="#" data-type="begin">正在处理</a></li>
                                <li><a href="#" data-type="finish">已完成</a></li>
                            </ul>
                        </div>
                        <a href="<?= Url::to(['task/index', 'projectID' => $_GET['projectID']]) ?>" id="task-fresh" title="刷新页面">
                            <button type="button" class="btn btn-default btn-sm">
                                <i class="fa fa-refresh"></i>
                            </button>
                        </a>
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
                            <span id="task-cur-page"></span>-<span id="task-total-page"></span>/<span
                                    id="task-total"></span>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm btn-prev"><i
                                            class="fa fa-chevron-left"></i>
                                </button>
                                <button type="button" class="btn btn-default btn-sm btn-next"><i
                                            class="fa fa-chevron-right"></i>
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
            <td title="标记为完成任务"><input type="checkbox" value="<%=list[i].id%>" <% if (list[i].progress == 2) { %>
                checked=true <% } %>>
            </td>
            <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
            <td class="mailbox-name"><a href="read-mail.html"><%=list[i].creator%></a></td>
            <td class="mailbox-subject">
                <a href="<?= Url::to(['task/view', 'projectID' => $_GET['projectID']])?>&taskID=<%=list[i].id%>&categoryID=<%=list[i].categoryID%>" class="text-black" title="<%=list[i].originName%>">
                    <i class="fa fa-circle-o <%=list[i].level%>"></i>&nbsp;&nbsp;
                    <%=list[i].name%>
                </a>
            </td>
            <td class="mailbox-date"><%=list[i].create%></td>
            <td class="mailbox-attachment">
                <% if (list[i].progress == 0) { %>
                <a href="javascript:void(0)" class="text-success" data-id="<%=list[i].id%>">
                    <i class="fa fa-circle" title="点击开始任务"></i>
                </a>
                <% } else if (list[i].progress == 1) { %>
                <a href="javascript:void(0)" class="text-red" data-id="<%=list[i].id%>">
                    <i class="fa fa-play" title="点击停止任务"></i>
                </a>
                <% } %>
            </td>
            <td>
                <a href="javascript:void(0)" class="text-muted" data-id="<%=list[i].id%>">
                    <i class="fa fa-trash-o" title="删除任务"></i>
                </a>
            </td>
            <td>
                <a href="<?=Url::to(['task/update', 'projectID' => $_GET['projectID']])?>&categoryID=<%=list[i].categoryID%>&taskID=<%=list[i].id%>" class="text-muted">
                    <i class="fa fa-edit" title="编辑任务"></i>
                </a>
            </td>
        </tr>
        <% } %>
    </script>

    <script type="text/html" id="none-task-template">
        <div class="jumbotron">
            <p>
            <p class="lead">现在还没有新任务，点击创建一个新任务试试吧.</p>
            <a class="btn btn-lg btn-success"
               href="<?= \yii\helpers\Url::to(['task/create']) ?>&projectID=1&categoryID=<%=info.id%>">新建任务</a>
            </p>
        </div>
    </script>

    <script>
        <?php $this->beginBlock('taskList') ?>
        $(function () {

            var curPage = 1;
            var totalPage = 0;
            var projectID = "<?= $_GET['projectID'] ?>";
            var categoryID = "<?= $_GET['categoryID']?>";

            $('#task-index').on('click', '#task-category>li', function () {
                categoryID = $(this).data('id');
                renderList();
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
                .on('click', '.mailbox-star', function (e) {
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
                })
                // 上一页
                .on('click', '.btn-prev', function () {
                    if (curPage - 1 <= 0) {
                        return false;
                    } else {
                        curPage--;
                        renderList();
                    }
                })
                // 下一页
                .on('click', '.btn-next', function () {
                    if (curPage + 1 > totalPage) {
                        return false;
                    } else {
                        curPage++;
                        renderList();
                    }
                })
                // 开始任务
                .on('click', '.fa-circle', function () {
                    var self = $(this);
                    handleTask(self, 'begin', '', function (cls, cls2) {
                        self.removeClass().addClass(cls);
                        self.parent().removeClass().addClass(cls2);
                    });
                })
                // 暂停任务
                .on('click', '.fa-play', function () {
                    var self = $(this);
                    handleTask(self, 'stop', '', function (cls, cls2) {
                        self.removeClass().addClass(cls);
                        self.parent().removeClass().addClass(cls2);
                    });
                })
                // 取消完成任务
                .on('ifUnchecked', '.mailbox-messages input[type="checkbox"]', function () {
                    var self = $(this);
                    var taskID = self.val();
                    finishTask(taskID);
                })
                // 完成任务
                .on('ifChecked', '.mailbox-messages input[type="checkbox"]', function () {
                    var self = $(this);
                    var taskID = self.val();
                    finishTask(taskID);
                })
                // 任务状态选择
                .on('click', '.dropdown-menu>li>a', function (e) {
                    e.preventDefault();
                    var params = {};
                    var type = $(this).data('type');
                    if (type === 'stop') {
                        params.progress = 0;
                    } else if (type === 'begin') {
                        params.progress = 1;
                    } else if (type === 'finish') {
                        params.progress = 2;
                    }
                    renderList(params);
                });

            // 完成任务
            function finishTask(taskID) {
                if (!taskID) {
                    $.showBox({msg: '参数有误'});
                    return false;
                }

                $.ajax({
                    type: 'GET',
                    url: "<?= Url::to(['task/finish', 'projectID' => $_GET['projectID']])?>",
                    data: {
                        taskID: taskID
                    },
                    dataType: 'json'
                }).done(function (data) {
                    if (data.action === 'begin') {
                        self.parents('tr').children().find('.mailbox-attachment>a').html('<i class="fa fa-circle" title="点击开始任务"></i>');
                    } else if (data.action === 'stop') {
                        self.parents('tr').children().find('.mailbox-attachment>a>i').remove();
                    }

                }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
                    var msg = XMLHttpRequest.responseText || '系统繁忙～';
                    $.showBox({msg: msg, seconds: 3000});
                })
            }

            // 处理任务进度
            function handleTask(selector, action, value, callback) {
                var taskID = value || selector.parent().data('id');
                if (!taskID || !action) {
                    $.showBox({msg: '参数有误'});
                    return false;
                }

                $.ajax({
                    type: 'GET',
                    url: "<?= Url::to(['task/handle', 'projectID' => $_GET['projectID']])?>",
                    data: {
                        action: action,
                        taskID: taskID
                    },
                    dataType: 'json'
                }).done(function (data) {
                    var cls = '', cls2 = '';
                    if (data.action === 'begin') {
                        cls = 'fa fa-play';
                        cls2 = 'text-red';
                    } else if (data.action === 'stop') {
                        cls = 'fa fa-circle';
                        cls2 = 'text-success';
                    }

                    callback && callback(cls, cls2);

                }).fail(function (xhr, status, error) {
                    var msg = xhr.responseText || '系统繁忙～';
                    $.showBox({msg: msg, seconds: 3000});
                })
            }

            var ajaxBort = null;

            // 渲染列表
            function renderList(options) {
                var params = $.extend({
                    totalInit: 1,
                    pageSize: 10,
                    page: curPage,
                    categoryID: categoryID,
                    me: "<?=$_GET['me']?>",
                }, options);
                var url = "<?=\yii\helpers\Url::to(['task/list', 'projectID' => $_GET['projectID']])?>";

                ajaxBort && ajaxBort.abort();
                ajaxBort = $.ajax({
                    type: 'GET',
                    url: url,
                    data: params,
                    dataType: 'json'
                }).done(function (data) {

                    // 页码设置
                    $('#task-cur-page').text(curPage);
                    if (params.totalInit === 1) {
                        if (data.data.total > 0) {
                            totalPage = (data.data.total / params.pageSize).toFixed(0);
                            $('#task-total').text(data.data.total);
                            $('#task-total-page').text(totalPage);
                        } else {
                            $('#task-total').text(0);
                            $('#task-total-page').text(0);
                        }
                    }

                    // 上一页
                    if (curPage - 1 <= 0) {
                        $('.btn-prev').attr('disabled', true);
                    } else {
                        $('.btn-prev').removeAttr('disabled');
                    }

                    // 下一页
                    if (curPage + 1 > totalPage) {
                        $('.btn-next').attr('disabled', true);
                    } else {
                        $('.btn-next').removeAttr('disabled');
                    }

                    // 标题链接
                    var info = data.data.info || {};
                    if (!info.id || !info.name) {
                        $('#task-category-title').text('全部');
                        $('#task-category-url').addClass('hidden');
                    } else {
                        $('#task-category-title').text(info.name);
                        $('#task-category-url').removeClass('hidden').attr('href', "<?= Url::to(['task/create'])?>&categoryID="+info.id+'&projectID='+projectID);
                    }

                    // 刷新页面
                    $('#task-fresh').attr('href', "<?= Url::to(['task/index', 'me' => $_GET['me']])?>&categoryID="+info.id+'&projectID='+projectID);

                    var list = data.data.list || [];
                    var len = list.length;
                    var html = '';
                    if (len > 0) {
                        html = template.render('task-template', {list: list});
                        $('#task-list').html(html);
                        $('.mailbox-messages input[type="checkbox"]').iCheck({
                            checkboxClass: 'icheckbox_flat-green',
                            radioClass: 'iradio_flat-green'
                        });
                    } else {
                        html = template.render('none-task-template', {info: info});
                        $('#task-list').html(html);
                    }
                }).fail(function (xhr, status, error) {
                    var msg = xhr.responseText || error;
                    $.showBox({msg: msg});
                })
            }

            renderList({});

        })
        ;
        <?php $this->endBlock() ?>
    </script>
<?php $this->registerJs($this->blocks['taskList'], \yii\web\View::POS_END); ?>