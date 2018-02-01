<?php

use yii\helpers\Url;
use frontend\assets\AppAsset;
use common\config\Conf;

AppAsset::registerJsFile($this, 'js/template.js');

$this->title = '用户管理';
$this->params['breadcrumbs'][] = '用户列表';
?>
    <div class="box box-success" id="user-manage">
        <div class="box-header">
            <h3 class="box-title">用户管理</h3>

            <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                    <a href="<?= Url::to(['user-manage/import']) ?>" class="btn-sm btn-danger pull-right" id="team-url">导入成员</a>
                </div>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tbody id="user-list">

                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->

    <div class="modal fade" id="set-role" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">设置角色</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <label class="radio-inline">
                            <input type="radio" name="roleID" id="inlineRadio1" value="<?=Conf::ROLE_ADMIN?>"> 管理员
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="roleID" id="inlineRadio2" value="<?=Conf::ROLE_MEMBER?>"> 成员
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="roleID" id="inlineRadio3" value="<?=Conf::ROLE_GUEST?>"> 游客
                        </label>
                        <input type="hidden" id="user-id" name="userID" value="">
                        <input type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                    </form>
                    <p class="help-block text-red set-role-tip hide">请选择角色.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-success" id="submit-set-role">确定</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/html" id="user-template">
        <tr>
            <th>ID</th>
            <th>账号</th>
            <th>姓名</th>
            <th>手机</th>
            <th>邮箱</th>
            <th>角色</th>
            <th>状态</th>
            <th>所属团队</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        <% for(var i = 0, len = list.length; i < len; i++) { %>
        <tr>
            <td>
                <a href="<?= Url::to(['user/profile']) ?>&userID=<%=list[i].id%>">
                    <img width="40px" height="40px" src="<%=list[i].portrait%>" class="img-circle" alt="User Image">
                </a>
            </td>
            <td><%=list[i].login%></td>
            <td><%=list[i].name%></td>
            <td><%=list[i].phone%></td>
            <td><%=list[i].email%></td>
            <td>
                <% if (list[i].roleID == "<?=Conf::ROLE_SUPER?>") { %>
                <span>
                    <%=list[i].role%>
                </span>
                <% } else { %>
                <% var cls = list[i].roleID == "<?=Conf::ROLE_ADMIN?>" ? 'text-red' : list[i].roleID == "<?=Conf::ROLE_MEMBER?>" ? 'text-orange' : '' %>
                <a href="#" class="<%=cls%>" data-toggle="modal" data-target="#set-role" data-user_id="<%=list[i].id%>" data-role_id="<%=list[i].roleID%>" data-name="<%=list[i].name%>">
                    <%=list[i].role%>
                </a>
                <% } %>
            </td>
            <td>
                <% if (list[i].status == "<?=\common\config\Conf::USER_FREEZE?>") { %>
                <span class="label label-danger">冻结</span>
                <% } else if (list[i].status == "<?=\common\config\Conf::USER_ENABLE?>") { %>
                <span class="label label-success">正常</span>
                <% } %>
            </td>
            <td><%=list[i].team%></td>
            <td><%=list[i].create%></td>
            <td>
                <a href="<?= Url::to(['user/profile']) ?>&userID=<%=list[i].id%>" title="查看" aria-label="查看"
                   data-pjax="0">
                    <span class="glyphicon glyphicon-eye-open"></span></a>
                <a href="<?= Url::to(['user-manage/delete']) ?>&userID=<%=list[i].id%>" title="删除" aria-label="删除"
                   class="delete-user">
                    <span class="glyphicon glyphicon-trash"></span>
                </a>
            </td>
        </tr>
        <% } %>
    </script>

    <script type="text/html" id="none-user-template">
        <div class="jumbotron">
            <p>
            <p class="lead">现在还没有成员，点击导入成员吧.</p>
            <a class="btn btn-lg btn-success"
               href="<?= Url::to(['user-manage/import']) ?>&projectID=1&categoryID=<%=info.id%>">导入成员</a>
            </p>
        </div>
    </script>

    <script>
        <?php $this->beginBlock('userManageList') ?>
        $(function () {

            var curPage = 1;
            var totalPage = 0;
            var ajaxAbort = null;

            // 渲染列表
            function renderList(options) {
                var params = $.extend({
                    totalInit: 1,
                    pageSize: 20,
                }, options);
                var url = "<?=Url::to(['user-manage/list'])?>";

                ajaxAbort && ajaxAbort.abort();
                ajaxAbort = $.ajax({
                    type: 'GET',
                    url: url,
                    data: params,
                    dataType: 'json'
                }).done(function (data) {

                    // 页码设置
                    $('#user-cur-page').text(curPage);
                    if (params.totalInit === 1) {
                        if (data.total > 0) {
                            totalPage = Math.ceil(data.total / params.pageSize);
                            $('#user-total').text(data.total);
                            $('#user-total-page').text(totalPage);
                        } else {
                            $('#user-total').text(0);
                            $('#user-total-page').text(0);
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
                        html = template.render('user-template', {list: list});
                        $('#user-list').html(html);
                    } else {
                        html = template.render('none-user-template', {info: info});
                        $('#user-list').html(html);
                    }
                }).fail(function (xhr, status, error) {
                    var msg = xhr.responseText || error;
                    $.showBox({msg: msg});
                })
            }

            renderList({});

            // 设置角色
            $('#set-role').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var userID = button.data('user_id');
                var roleID = button.data('role_id');
                var name = button.data('name');
                var modal = $(this);
                modal.find('.modal-title').text('正在为'+name+'设置角色');
                modal.find('.modal-body input[name="userID"]').val(userID);
                $.each(modal.find('.modal-body input[type="radio"]'),function(){
                    if ($(this).val() == roleID) {
                        $(this).attr('checked', 'checked');
                    }
                });
            });

            // 设置角色
            $('#submit-set-role').on('click', function () {
                var userID = $('#user-id').val();
                var roleID = $('input[name="roleID"]').val();
                if (!userID) {
                    $('#set-role').modal('hide');
                    $.showBox({msg: '数据错误', callback:function () {
                        window.location.reload();
                    }});
                    return false;
                }
                if (!roleID) {
                    $('.set-role-tip').removeClass('hide');
                    return false;
                }

                // 指派任务
                $.ajax({
                    url: "<?= Url::to(['user-manage/set-role'])?>",
                    method: 'GET',
                    dataType: 'json',
                    data: {
                        userID: userID,
                        roleID: roleID,
                        _csrf: $('input[name="_csrf"]').val()
                    }
                }).done(function (data) {
                    if (data.status == 1) {
                        $('#set-role').modal('hide');
                        $.showBox({
                            msg: '设置成功', callback: function () {
                                window.location.reload();
                            }
                        });
                    } else {
                        $('#set-role').modal('hide');
                        $.showBox({msg: '设置失败'});
                    }
                }).fail(function (xhr, status, error) {
                    $('#set-role').modal('hide');
                    var msg = xhr.responseText || '系统繁忙';
                    $.showBox({msg: msg});
                });
            });

            $('#user-manage').on('click', '.delete-user', function (e) {
                // 删除用户
                e.preventDefault();
                if (!confirm('你确定要删除该用户吗？')) {
                    return false;
                }

                var self = $(this);
                var url = self.attr('href');
                $.ajax({
                    url: url,
                    method: 'get',
                    dataType: 'json'
                }).done(function (data) {
                    if (data.status === 1) {
                        self.parent().parent().remove();
                    } else {
                        $.showBox('删除失败');
                    }
                }).fail(function (xhr, status, error) {
                    var msg = xhr.responseText || '系统繁忙～';
                    $.showBox(msg);
                })
            });

        })
        ;
        <?php $this->endBlock() ?>
    </script>
<?php $this->registerJs($this->blocks['userManageList'], \yii\web\View::POS_END); ?>