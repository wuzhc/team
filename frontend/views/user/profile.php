<?php

/* @var $user \common\models\User */

use common\services\UserService;
use frontend\assets\AdminLtePluginAsset;
use frontend\assets\AppAsset;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = '个人资料';
AdminLtePluginAsset::register($this);
AppAsset::registerJsFile($this, 'js/template.js')

?>
    <div class="row" id="user-profile">
        <div class="col-md-3">

            <!-- Profile Image -->
            <div class="box box-success">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle"
                         src="<?= UserService::factory()->getUserPortrait($user) ?>"
                         alt="User profile picture">

                    <h3 class="profile-username text-center"><?= Html::encode(UserService::factory()->getUserName($user)) ?></h3>

                    <p class="text-muted text-center"><?= Html::encode($user->fdPosition) ?></p>

                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>所有任务</b> <a class="pull-right" id="total-task">?</a>
                        </li>
                        <li class="list-group-item">
                            <b>已完成任务</b> <a class="pull-right" id="complete-task">?</a>
                        </li>
                        <li class="list-group-item">
                            <b>未完成任务</b> <a class="pull-right" id="unComplete-task">?</a>
                        </li>
                    </ul>

                    <a href="<?= Url::to(['default/index']) ?>" class="btn btn-success btn-block">查看项目</a>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <!-- About Me Box -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">About Me</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <strong><i class="fa fa-book margin-r-5"></i> Education</strong>

                    <p class="text-muted">
                        B.S. in Computer Science from the University of Tennessee at Knoxville
                    </p>

                    <hr>

                    <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>

                    <p class="text-muted">Malibu, California</p>

                    <hr>

                    <strong><i class="fa fa-pencil margin-r-5"></i> Skills</strong>

                    <p>
                        <span class="label label-danger">UI Design</span>
                        <span class="label label-success">Coding</span>
                        <span class="label label-info">Javascript</span>
                        <span class="label label-warning">PHP</span>
                        <span class="label label-primary">Node.js</span>
                    </p>

                    <hr>

                    <strong><i class="fa fa-file-text-o margin-r-5"></i> Notes</strong>

                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#activity" data-toggle="tab">我的任务</a></li>
                    <li><a href="#timeline" data-toggle="tab">动态</a></li>
                    <li><a href="#settings" data-toggle="tab">设置</a></li>
                </ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="activity">
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
                                <a href="<?= Url::to(['task/index', 'projectID' => $_GET['projectID']]) ?>"
                                   id="task-fresh" title="刷新页面">
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
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="timeline">
                        <!-- The timeline -->
                        <ul class="timeline timeline-inverse">
                            <!-- timeline time label -->
                            <li class="time-label">
                        <span class="bg-red">
                          10 Feb. 2014
                        </span>
                            </li>
                            <!-- /.timeline-label -->
                            <!-- timeline item -->
                            <li>
                                <i class="fa fa-envelope bg-blue"></i>

                                <div class="timeline-item">
                                    <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                                    <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

                                    <div class="timeline-body">
                                        Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                                        weebly ning heekya handango imeem plugg dopplr jibjab, movity
                                        jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                                        quora plaxo ideeli hulu weebly balihoo...
                                    </div>
                                    <div class="timeline-footer">
                                        <a class="btn btn-success btn-xs">Read more</a>
                                        <a class="btn btn-danger btn-xs">Delete</a>
                                    </div>
                                </div>
                            </li>
                            <!-- END timeline item -->
                            <!-- timeline item -->
                            <li>
                                <i class="fa fa-user bg-aqua"></i>

                                <div class="timeline-item">
                                    <span class="time"><i class="fa fa-clock-o"></i> 5 mins ago</span>

                                    <h3 class="timeline-header no-border"><a href="#">Sarah Young</a> accepted your
                                        friend request
                                    </h3>
                                </div>
                            </li>
                            <!-- END timeline item -->
                            <!-- timeline item -->
                            <li>
                                <i class="fa fa-comments bg-yellow"></i>

                                <div class="timeline-item">
                                    <span class="time"><i class="fa fa-clock-o"></i> 27 mins ago</span>

                                    <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>

                                    <div class="timeline-body">
                                        Take me to your leader!
                                        Switzerland is small and neutral!
                                        We are more like Germany, ambitious and misunderstood!
                                    </div>
                                    <div class="timeline-footer">
                                        <a class="btn btn-warning btn-flat btn-xs">View comment</a>
                                    </div>
                                </div>
                            </li>
                            <!-- END timeline item -->
                            <!-- timeline time label -->
                            <li class="time-label">
                        <span class="bg-green">
                          3 Jan. 2014
                        </span>
                            </li>
                            <!-- /.timeline-label -->
                            <!-- timeline item -->
                            <li>
                                <i class="fa fa-camera bg-purple"></i>

                                <div class="timeline-item">
                                    <span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>

                                    <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>

                                    <div class="timeline-body">
                                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                                    </div>
                                </div>
                            </li>
                            <!-- END timeline item -->
                            <li>
                                <i class="fa fa-clock-o bg-gray"></i>
                            </li>
                        </ul>
                    </div>
                    <!-- /.tab-pane -->

                    <div class="tab-pane" id="settings">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">Name</label>

                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="inputName" placeholder="Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">Name</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputName" placeholder="Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputExperience" class="col-sm-2 control-label">Experience</label>

                                <div class="col-sm-10">
                                    <textarea class="form-control" id="inputExperience"
                                              placeholder="Experience"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputSkills" class="col-sm-2 control-label">Skills</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputSkills" placeholder="Skills">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-danger">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>

    <script type="text/html" id="task-template">
        <% for(var i = 0, len = list.length; i < len; i++) { %>
        <tr>
            <td title="标记为完成任务"><input type="checkbox" value="<%=list[i].id%>" <% if (list[i].progress == 2) { %>
                checked=true <% } %>>
            </td>
            <td class="mailbox-subject">
                <a href="<?= Url::to([
                    'task/view',
                    'projectID' => $_GET['projectID']
                ]) ?>&taskID=<%=list[i].id%>&categoryID=<%=list[i].categoryID%>" class="text-black"
                   title="<%=list[i].originName%>">
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
                <a href="<?= Url::to([
                    'task/update',
                    'projectID' => $_GET['projectID']
                ]) ?>&categoryID=<%=list[i].categoryID%>&taskID=<%=list[i].id%>" class="text-muted">
                    <i class="fa fa-edit" title="编辑任务"></i>
                </a>
            </td>
        </tr>
        <% } %>
    </script>

    <script type="text/html" id="none-task-template">
        <div class="jumbotron">
            <p>
            <p class="lead"><?= Html::encode(UserService::factory()->getUserName($user)) ?>现在还没有任务，可以进入项目里创建任务啊.</p>
            <a class="btn btn-sm btn-success" href="<?= \yii\helpers\Url::to(['default/index']) ?>">进入项目</a>
            </p>
        </div>
    </script>

    <script>
        <?php $this->beginBlock('profile') ?>
        $(function () {

            var curPage = 1;
            var totalPage = 0;
            var projectID = "<?= $_GET['projectID'] ?>";
            var categoryID = "<?= $_GET['categoryID']?>";

            $('#user-profile').on('click', '#task-category>li', function () {
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

            var ajaxAbort = null;

            // 渲染列表
            function renderList(options) {
                var params = $.extend({
                    totalInit: 1,
                    pageSize: 15,
                    page: curPage,
                    categoryID: categoryID,
                    userID: "<?=$user->id?>"
                }, options);
                var url = "<?=\yii\helpers\Url::to(['user/tasks'])?>";

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
                            totalPage = (data.total / params.pageSize).toFixed(0);
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
                        html = template.render('none-task-template');
                        $('#task-list').html(html);
                    }
                }).fail(function (xhr, status, error) {
                    var msg = xhr.responseText || error;
                    $.showBox({msg: msg});
                })
            }

            renderList({});

            // 总数
            (function () {
                $.ajax({
                    type: 'GET',
                    url: "<?=Url::to(['user/stat-tasks'])?>",
                    dataType: 'json'
                }).done(function (data) {
                    $('#total-task').text(data.total);
                    $('#complete-task').text(data.complete);
                    $('#unComplete-task').text(data.total * 1 - data.complete * 1);
                }).fail(function (xhr, status, error) {
                    var msg = xhr.responseText || error;
                    $.showBox({msg: msg});
                })
            })();

        })
        ;
        <?php $this->endBlock() ?>
    </script>
<?php $this->registerJs($this->blocks['profile'], \yii\web\View::POS_END); ?>