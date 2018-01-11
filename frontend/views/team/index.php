<?php
$this->title = '所有团队';
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
?>
<div class="row">
    <div class="col-md-3">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">团队</h3>

                <div class="box-tools">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked">
                    <li class="active"><a href="#"><i class="fa fa-inbox"></i> 后端<span class="label label-success pull-right">12</span></a></li>
                    <li><a href="#"><i class="fa fa-envelope-o"></i> 前端<span class="label label-success pull-right">12</span></a></li>
                    <li><a href="#"><i class="fa fa-trash-o"></i> 运维<span class="label label-success pull-right">12</span></a></li>
                    <li><a href="#"><i class="fa fa-file-text-o"></i> 产品<span class="label label-success pull-right">12</span></a></li>
                    <li><a href="#"><i class="fa fa-filter"></i> 运营 <span class="label label-success pull-right">65</span></a>
                    </li>
                    <li><a href="#"><i class="fa fa-trash-o"></i> 客服<span class="label label-success pull-right">12</span></a></li>
                    <li><a href="#"><i class="fa fa-trash-o"></i> 培训<span class="label label-success pull-right">12</span></a></li>
                    <li><a href="#"><i class="fa fa-trash-o"></i> 美术<span class="label label-success pull-right">12</span></a></li>
                </ul>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
    <!-- /.col -->
    <div class="col-md-9">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">所有成员</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <ul class="users-list clearfix">
                    <li>
                        <img src="<?= $directoryAsset ?>/img/user1-128x128.jpg" alt="User Image">
                        <a class="users-list-name" href="#">Alexander Pierce</a>
                        <span class="users-list-date">Today</span>
                    </li>
                    <li>
                        <img src="<?= $directoryAsset ?>/img/user8-128x128.jpg" alt="User Image">
                        <a class="users-list-name" href="#">Norman</a>
                        <span class="users-list-date">Yesterday</span>
                    </li>
                    <li>
                        <img src="<?= $directoryAsset ?>/img/user7-128x128.jpg" alt="User Image">
                        <a class="users-list-name" href="#">Jane</a>
                        <span class="users-list-date">12 Jan</span>
                    </li>

                    <li>
                        <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" alt="User Image">
                        <a class="users-list-name" href="#">Alexander</a>
                        <span class="users-list-date">13 Jan</span>
                    </li>
                    <li>
                        <img src="<?= $directoryAsset ?>/img/user5-128x128.jpg" alt="User Image">
                        <a class="users-list-name" href="#">Sarah</a>
                        <span class="users-list-date">14 Jan</span>
                    </li>
                    <li>
                        <img src="<?= $directoryAsset ?>/img/user4-128x128.jpg" alt="User Image">
                        <a class="users-list-name" href="#">Nora</a>
                        <span class="users-list-date">15 Jan</span>
                    </li>
                    <li>
                        <img src="<?= $directoryAsset ?>/img/user3-128x128.jpg" alt="User Image">
                        <a class="users-list-name" href="#">Nadia</a>
                        <span class="users-list-date">15 Jan</span>
                    </li>
                    <li>
                        <img src="<?= $directoryAsset ?>/img/user1-128x128.jpg" alt="User Image">
                        <a class="users-list-name" href="#">Alexander Pierce</a>
                        <span class="users-list-date">Today</span>
                    </li>
                    <li>
                        <img src="<?= $directoryAsset ?>/img/user8-128x128.jpg" alt="User Image">
                        <a class="users-list-name" href="#">Norman</a>
                        <span class="users-list-date">Yesterday</span>
                    </li>
                    <li>
                        <img src="<?= $directoryAsset ?>/img/user7-128x128.jpg" alt="User Image">
                        <a class="users-list-name" href="#">Jane</a>
                        <span class="users-list-date">12 Jan</span>
                    </li>
                    <li>
                        <img src="<?= $directoryAsset ?>/img/user6-128x128.jpg" alt="User Image">
                        <a class="users-list-name" href="#">John</a>
                        <span class="users-list-date">12 Jan</span>
                    </li>
                    <li>
                        <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" alt="User Image">
                        <a class="users-list-name" href="#">Alexander</a>
                        <span class="users-list-date">13 Jan</span>
                    </li>
                    <li>
                        <img src="<?= $directoryAsset ?>/img/user5-128x128.jpg" alt="User Image">
                        <a class="users-list-name" href="#">Sarah</a>
                        <span class="users-list-date">14 Jan</span>
                    </li>
                    <li>
                        <img src="<?= $directoryAsset ?>/img/user4-128x128.jpg" alt="User Image">
                        <a class="users-list-name" href="#">Nora</a>
                        <span class="users-list-date">15 Jan</span>
                    </li>
                </ul>
                <!-- /.users-list -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
                <a href="javascript:void(0)" class="uppercase">View All Users</a>
            </div>
            <!-- /.box-footer -->
        </div>
        <!-- /. box -->
    </div>
    <!-- /.col -->
</div>


