<?php

/* @var $projectID string 项目ID */
/* @var $isMe int 是否为我的任务，0否1是 */
/* @var $categoryID int 分类ID */
/* @var $categories array 任务分类清单 */

use frontend\assets\AdminLtePluginAsset;
use frontend\assets\AppAsset;
use yii\helpers\Url;

$this->title = $isMe == 1 ? '我的任务' : '所有任务';
AdminLtePluginAsset::register($this);
AppAsset::registerJsFile($this, 'js/template.js');

?>
    <div class="row" id="task-index">
        <div class="col-md-3">
            <div class="box box-solid">
                <a href="<?= Url::to(['task/index', 'projectID' => $projectID, 'categoryID' => $categoryID, 'isMe' => ($isMe+1)%2])?>" class="btn btn-success" style="width: 100%"><?= $isMe == 1 ? '返回所有项目任务' : '返回我的任务'?></a>
            </div>

            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">清单</h3>
                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked" id="task-category">
                        <li data-id="0">
                            <a href="javascript:void(0)" id="task-category-0">
                                <h4 class="control-sidebar-subheading">
                                    全部
                                    <small class="task-category-info">(0/0)</small>
                                    <span class="label label-primary pull-right task-category-rate">0%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-primary" style="width: 0%"></div>
                                </div>
                            </a>
                        </li>
                        <?php if (!empty($categories)) { ?>
                            <?php foreach ($categories as $k => $category) { ?>
                                <li data-id="<?=$category->id?>">
                                    <a href="javascript:void(0)" id="task-category-<?=$category->id?>">
                                        <h4 class="control-sidebar-subheading">
                                            <?= \yii\helpers\StringHelper::truncate($category->fdName, 20)?>
                                            <small class="task-category-info">(0/0)</small>
                                            <span class="label label-<?= Yii::$app->params['colorTwo'][$k%4]?> pull-right task-category-rate">0%</span>
                                        </h4>

                                        <div class="progress progress-xxs">
                                            <div class="progress-bar progress-bar-<?= Yii::$app->params['colorTwo'][$k%4]?>" style="width: 0%"></div>
                                        </div>
                                    </a>
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
                    <h3 class="box-title">任务等级</h3>

                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li>
                            <a href="javaScript:void(0)">
                                <i class="fa fa-circle-o text-primary"></i>蓝色
                            </a>
                        </li>
                        <li>
                            <a href="javaScript:void(0)">
                                <i class="fa fa-circle-o text-yellow"></i>黄色
                            </a>
                        </li>
                        <li>
                            <a href="javaScript:void(0)">
                                <i class="fa fa-circle-o text-orange"></i>橙色
                            </a>
                        </li>
                        <li>
                            <a href="javaScript:void(0)">
                                <i class="fa fa-circle-o text-red"></i>红色
                            </a>
                        </li>
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
                        <a href="<?= Url::to(['task/index', 'projectID' => $projectID]) ?>" id="task-fresh" title="刷新页面">
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
                            当前页
                            <span id="task-cur-page"></span>
                            -
                            <span id="task-total-page"></span>
                            总页/
                            (总共<span id="task-total"></span>条记录)
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
                <a href="<?= Url::to(['task/view'])?>&taskID=<%=list[i].id%>" class="text-black" title="<%=list[i].originName%>">
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
                <a href="<?=Url::to(['task/update', 'projectID' => $projectID])?>&categoryID=<%=list[i].categoryID%>&taskID=<%=list[i].id%>" class="text-muted">
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
            var categoryID = "<?= $categoryID?>";

            // 总数初始化
            (function () {
                var url = "<?= Url::to(['task/stat-tasks', 'isMe' => $isMe]) ?>";
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {projectID: "<?= !empty($projectID) ? $projectID : 0 ?>"},
                    dataType: 'json'
                }).done(function (data) {
                    var tasks = data.tasks || [];
                    var map = {};
                    for (var i in tasks) {
                        map[tasks[i].cid] = tasks[i];
                    }

                    var aTotal = 0;
                    var aComplete = 0;

                    $.each($('#task-category>li'), function(k, v) {
                        var cid = $(this).data('id');
                        if (map[cid]) {
                            var total = map[cid].allTasks;
                            var complete = map[cid].completeTasks || 0;
                            var rate = total > 0 && complete > 0 ? ((complete/total) * 100).toFixed(0) : 0;
                            $(this).find('.task-category-info').text('('+ complete + '/' + total +')');
                            $(this).find('.task-category-rate').text(rate +'%');
                            $(this).find('.progress-bar').css({'width':rate + '%'});

                            aTotal += total * 1;
                            aComplete += complete * 1;
                        }
                    });

                    if (aTotal > 0) {
                        var aRate = aTotal > 0 ? ((aComplete/aTotal) * 100).toFixed(0) : 0;
                        $('#task-category-0').find('.task-category-info').text('('+ aComplete + '/' + aTotal +')');
                        $('#task-category-0').find('.task-category-rate').text(aRate +'%');
                        $('#task-category-0').find('.progress-bar').css({'width':aRate + '%'});
                    }

                }).fail(function () {
                    $.showBox({msg: '系统繁忙~'});
                });

            })();

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
                })
                .on('click', '.fa-trash-o', function (e) {
                    e.preventDefault();
                    if (confirm('确定要删除该任务吗？')) {
                        var self = $(this);
                        $.ajax({
                            url: "<?= Url::to(['task/delete']) ?>",
                            data: {taskID: $(this).parent().data('id')},
                            method: 'GET',
                            dataType: 'json'
                        }).done(function(data){
                            if (data.status == 1) {
                                $.showBox({msg: '删除成功'});
                                self.parents('tr').remove();
                            } else {
                                $.showBox({msg: '删除失败'});
                            }
                        }).fail(function (xhr, status, error) {
                            var msg = xhr.responseText || error;
                            $.showBox({msg: msg});
                        })
                    }
                })

            // 完成任务
            function finishTask(taskID) {
                if (!taskID) {
                    $.showBox({msg: '参数有误'});
                    return false;
                }

                $.ajax({
                    type: 'GET',
                    url: "<?= Url::to(['task/finish'])?>",
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

                }).fail(function (xhr, status, error) {
                    var msg = xhr.responseText || '系统繁忙～';
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
                    url: "<?= Url::to(['task/handle'])?>",
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

            var ajaxAbort = null;

            // 渲染列表
            function renderList(options) {
                var params = $.extend({
                    totalInit: 1,
                    pageSize: 15,
                    page: curPage,
                    categoryID: categoryID,
                    isMe: "<?=$isMe?>"
                }, options);
                var url = "<?=Url::to(['task/list', 'projectID' => $projectID])?>";

                ajaxAbort && ajaxAbort.abort();
                ajaxAbort = $.ajax({
                    type: 'GET',
                    url: url,
                    data: params,
                    dataType: 'json'
                }).done(function (data) {

                    // 页码设置
                    $('#task-cur-page').text(curPage);
                    if (params.totalInit === 1) {
                        if (data.total > 0) {
                            totalPage = Math.ceil(data.total / params.pageSize);
                            $('#task-total').text(data.total);
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
                    var info = data.info || {};
                    if (!info.id || !info.name) {
                        $('#task-category-title').text('全部');
                        $('#task-category-url').addClass('hidden');
                    } else {
                        $('#task-category-title').text(info.name);
                        $('#task-category-url').removeClass('hidden').attr('href', "<?= Url::to(['task/create', 'projectID' => $projectID])?>&categoryID="+info.id);
                    }

                    // 刷新页面
                    $('#task-fresh').attr('href', "<?= Url::to(['task/index', 'projectID' => $projectID, 'isMe' => $isMe])?>&categoryID="+info.id);

                    var list = data.list || [];
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