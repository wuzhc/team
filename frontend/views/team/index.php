<?php
use yii\helpers\Url;

$this->title = '所有团队';
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
?>
<div class="row" id="team-index">
    <div class="col-md-3">
        <?php if (Yii::$app->user->can('importUser')) { ?>
        <div class="box box-solid">
            <a href="<?= Url::to(['team/create'])?>" class="btn btn-success" style="width: 49%">新建团队</a>
            <a href="<?= Url::to(['user/import'])?>" class="btn btn-success" style="width: 49%">导入成员</a>
        </div>
        <?php } ?>
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">团队</h3>

                <div class="box-tools">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked" id="team-nav">
                    <!--                    <li class="active"><a href="#"><i class="fa fa-inbox"></i> 后端<span-->
                    <!--                                    class="label label-success pull-right">12</span></a></li>-->
                    <!--                    <li><a href="#"><i class="fa fa-envelope-o"></i> 前端<span-->
                    <!--                                    class="label label-success pull-right">12</span></a></li>-->
                    <!--                    <li><a href="#"><i class="fa fa-trash-o"></i> 运维<span-->
                    <!--                                    class="label label-success pull-right">12</span></a></li>-->
                    <!--                    <li><a href="#"><i class="fa fa-file-text-o"></i> 产品<span-->
                    <!--                                    class="label label-success pull-right">12</span></a></li>-->
                    <!--                    <li><a href="#"><i class="fa fa-filter"></i> 运营 <span-->
                    <!--                                    class="label label-success pull-right">65</span></a>-->
                    <!--                    </li>-->
                    <!--                    <li><a href="#"><i class="fa fa-trash-o"></i> 客服<span-->
                    <!--                                    class="label label-success pull-right">12</span></a></li>-->
                    <!--                    <li><a href="#"><i class="fa fa-trash-o"></i> 培训<span-->
                    <!--                                    class="label label-success pull-right">12</span></a></li>-->
                    <!--                    <li><a href="#"><i class="fa fa-trash-o"></i> 美术<span-->
                    <!--                                    class="label label-success pull-right">12</span></a></li>-->
                </ul>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
    <!-- /.col -->
    <div class="col-md-9">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title" id="team-name">成员列表</h3>
                <a href="javaScript:void(0)" class="btn-sm btn-danger pull-right" id="team-url">新增</a>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <ul class="users-list clearfix" id="team-member">
                    <!--                    <li>-->
                    <!--                        <img src="-->
                    <? //= $directoryAsset ?><!--/img/user1-128x128.jpg" alt="User Image">-->
                    <!--                        <a class="users-list-name" href="#">Alexander Pierce</a>-->
                    <!--                        <span class="users-list-date">Today</span>-->
                    <!--                    </li>-->
                    <!--                    <li>-->
                    <!--                        <img src="-->
                    <? //= $directoryAsset ?><!--/img/user8-128x128.jpg" alt="User Image">-->
                    <!--                        <a class="users-list-name" href="#">Norman</a>-->
                    <!--                        <span class="users-list-date">Yesterday</span>-->
                    <!--                    </li>-->
                    <!--                    <li>-->
                    <!--                        <img src="-->
                    <? //= $directoryAsset ?><!--/img/user7-128x128.jpg" alt="User Image">-->
                    <!--                        <a class="users-list-name" href="#">Jane</a>-->
                    <!--                        <span class="users-list-date">12 Jan</span>-->
                    <!--                    </li>-->
                    <!---->
                    <!--                    <li>-->
                    <!--                        <img src="-->
                    <? //= $directoryAsset ?><!--/img/user2-160x160.jpg" alt="User Image">-->
                    <!--                        <a class="users-list-name" href="#">Alexander</a>-->
                    <!--                        <span class="users-list-date">13 Jan</span>-->
                    <!--                    </li>-->
                    <!--                    <li>-->
                    <!--                        <img src="-->
                    <? //= $directoryAsset ?><!--/img/user5-128x128.jpg" alt="User Image">-->
                    <!--                        <a class="users-list-name" href="#">Sarah</a>-->
                    <!--                        <span class="users-list-date">14 Jan</span>-->
                    <!--                    </li>-->
                    <!--                    <li>-->
                    <!--                        <img src="-->
                    <? //= $directoryAsset ?><!--/img/user4-128x128.jpg" alt="User Image">-->
                    <!--                        <a class="users-list-name" href="#">Nora</a>-->
                    <!--                        <span class="users-list-date">15 Jan</span>-->
                    <!--                    </li>-->
                    <!--                    <li>-->
                    <!--                        <img src="-->
                    <? //= $directoryAsset ?><!--/img/user3-128x128.jpg" alt="User Image">-->
                    <!--                        <a class="users-list-name" href="#">Nadia</a>-->
                    <!--                        <span class="users-list-date">15 Jan</span>-->
                    <!--                    </li>-->
                    <!--                    <li>-->
                    <!--                        <img src="-->
                    <? //= $directoryAsset ?><!--/img/user1-128x128.jpg" alt="User Image">-->
                    <!--                        <a class="users-list-name" href="#">Alexander Pierce</a>-->
                    <!--                        <span class="users-list-date">Today</span>-->
                    <!--                    </li>-->
                    <!--                    <li>-->
                    <!--                        <img src="-->
                    <? //= $directoryAsset ?><!--/img/user8-128x128.jpg" alt="User Image">-->
                    <!--                        <a class="users-list-name" href="#">Norman</a>-->
                    <!--                        <span class="users-list-date">Yesterday</span>-->
                    <!--                    </li>-->
                    <!--                    <li>-->
                    <!--                        <img src="-->
                    <? //= $directoryAsset ?><!--/img/user7-128x128.jpg" alt="User Image">-->
                    <!--                        <a class="users-list-name" href="#">Jane</a>-->
                    <!--                        <span class="users-list-date">12 Jan</span>-->
                    <!--                    </li>-->
                    <!--                    <li>-->
                    <!--                        <img src="-->
                    <? //= $directoryAsset ?><!--/img/user6-128x128.jpg" alt="User Image">-->
                    <!--                        <a class="users-list-name" href="#">John</a>-->
                    <!--                        <span class="users-list-date">12 Jan</span>-->
                    <!--                    </li>-->
                    <!--                    <li>-->
                    <!--                        <img src="-->
                    <? //= $directoryAsset ?><!--/img/user2-160x160.jpg" alt="User Image">-->
                    <!--                        <a class="users-list-name" href="#">Alexander</a>-->
                    <!--                        <span class="users-list-date">13 Jan</span>-->
                    <!--                    </li>-->
                    <!--                    <li>-->
                    <!--                        <img src="-->
                    <? //= $directoryAsset ?><!--/img/user5-128x128.jpg" alt="User Image">-->
                    <!--                        <a class="users-list-name" href="#">Sarah</a>-->
                    <!--                        <span class="users-list-date">14 Jan</span>-->
                    <!--                    </li>-->
                    <!--                    <li>-->
                    <!--                        <img src="-->
                    <? //= $directoryAsset ?><!--/img/user4-128x128.jpg" alt="User Image">-->
                    <!--                        <a class="users-list-name" href="#">Nora</a>-->
                    <!--                        <span class="users-list-date">15 Jan</span>-->
                    <!--                    </li>-->
                </ul>
                <!-- /.users-list -->
            </div>
            <!-- /.box-body -->
            <!-- /.box-footer -->
        </div>
        <!-- /. box -->
    </div>
    <!-- /.col -->
</div>

<script>
    <?php $this->beginBlock('jquery') ?>
    $(function () {

        var teamMemeberMap = {};

        (function () {
            $.ajax({
                type: 'GET',
                url: "<?= Url::to(['team/get-members']);?>",
                dataType: 'json'
            }).done(function (data) {
                var teams = data.data || [];
                var nav = '';
                for (var i = 0, len = teams.length; i < len; i++) {
                    teamMemeberMap[teams[i].id] = teams[i];
                    nav += '<li data-id="' + teams[i].id + '">' +
                        '<a href="#"><i class="fa fa-group"></i>' + teams[i].name +
                        '<span class="label label-success pull-right">' + teams[i].members.length + '</span>' +
                        '</a></li>';

                    if (i === 0) {
                        renderMembers(teams[i].id);
                    }
                }
                $('#team-nav').html(nav);
            }).fail(function () {
                $.showBox({msg: '系统繁忙~'});
            })
        })();

        // 渲染成员
        function renderMembers(teamID) {

            $('#team-name').text(teamMemeberMap[teamID].name);
            $('#team-url').attr('href', "<?= Url::to(['team/update'])?>&id="+teamID);

            var portrait = '';
            var members = teamMemeberMap[teamID].members || [];
            for (var j = 0, mlen = members.length; j < mlen; j++) {
                portrait += '<li>' +
                    '<img src="' + members[j].portrait + '" alt="User Image">' +
                    '<a class="users-list-name" href="#">' + members[j].name + '</a>' +
                    '<span class="users-list-date">' + members[j].role + '</span>' +
                    '</li>';
            }

            if (!portrait) {
                $('#team-member').html('<li>还没有成员啊～</li>');
            } else {
                $('#team-member').html(portrait);
            }
        }

        $('#team-index').on('click', '#team-nav>li', function () {
            renderMembers($(this).data('id'));
        });

    });
    <?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['jquery'], \yii\web\View::POS_END); ?>
