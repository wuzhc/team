<?php

/* @var $this yii\web\View */

$this->title = '最新动态';
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
?>
<style>
    .portrait {
        width: 50px;
        height: 50px;
        position: absolute;
        left: 5px
    }
    .portrait-img {
        width: 50px;
        height: 50px;
    }
    .desc {
        font-size: 13px;
    }
    .timeline>li>.timeline-item {
        background: #f4f4f4;
    }
</style>

<section class="content">

    <!-- row -->
    <div class="row">
        <div class="col-md-12">
            <!-- The time line -->
            <ul class="timeline">
                <!-- timeline time label -->
                <li class="time-label">
                  <span class="bg-green">
                    2018-01-11
                  </span>
                </li>
                <!-- /.timeline-label -->


                <!-- timeline item -->
                <li>
                    <i class="portrait"><img class="img-circle img-bordered-sm portrait-img" src="<?= $directoryAsset ?>/img/user1-128x128.jpg" alt="User Image"></i>

                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                        <h3 class="timeline-header"><a href="#">吴桢灿</a> <span class="desc">创建了任务</span></h3>

                        <div class="timeline-body">
                            APP端保存不了诊断报告问题
                        </div>
                    </div>
                </li>
                <!-- END timeline item -->

                <!-- timeline item -->
                <li>
                    <i class="portrait"><img class="img-circle img-bordered-sm portrait-img" src="<?= $directoryAsset ?>/img/user4-128x128.jpg" alt="User Image"></i>

                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                        <h3 class="timeline-header"><a href="#">吴桢灿</a> <span class="desc">开始处理这条任务</span></h3>

                        <div class="timeline-body">
                            优化 首页 “为您推荐”优化机制，推荐的期末、期中、中考真题/模拟、月考卷根据其适用地域属性推送给用户
                        </div>
                    </div>
                </li>
                <!-- END timeline item -->

                <!-- timeline item -->
                <li>
                    <i class="portrait"><img class="img-circle img-bordered-sm portrait-img" src="<?= $directoryAsset ?>/img/user5-128x128.jpg" alt="User Image"></i>

                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                        <h3 class="timeline-header"><a href="#">吴桢灿</a> <span class="desc">开始处理这条任务</span></h3>

                        <div class="timeline-body">
                            优化 首页 “为您推荐”优化机制，推荐的期末、期中、中考真题/模拟、月考卷根据其适用地域属性推送给用户
                        </div>
                    </div>
                </li>
                <!-- END timeline item -->

                <!-- timeline item -->
                <li>
                    <i class="portrait"><img class="img-circle img-bordered-sm portrait-img" src="<?= $directoryAsset ?>/img/user1-128x128.jpg" alt="User Image"></i>

                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                        <h3 class="timeline-header"><a href="#">吴桢灿</a> <span class="desc">开始处理这条任务</span></h3>

                        <div class="timeline-body">
                            优化 首页 “为您推荐”优化机制，推荐的期末、期中、中考真题/模拟、月考卷根据其适用地域属性推送给用户
                        </div>
                    </div>
                </li>
                <!-- END timeline item -->


                <!-- timeline item -->
                <li>
                    <i class="portrait"><img class="img-circle img-bordered-sm portrait-img" src="<?= $directoryAsset ?>/img/user3-128x128.jpg" alt="User Image"></i>

                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                        <h3 class="timeline-header"><a href="#">吴桢灿</a> <span class="desc">开始处理这条任务</span></h3>

                        <div class="timeline-body">
                            优化 首页 “为您推荐”优化机制，推荐的期末、期中、中考真题/模拟、月考卷根据其适用地域属性推送给用户
                        </div>
                    </div>
                </li>
                <!-- END timeline item -->





                <!-- timeline time label -->
                <li class="time-label">
                  <span class="bg-red">
                     2018-01-10
                  </span>
                </li>
                <!-- /.timeline-label -->


                <!-- timeline item -->
                <li>
                    <i class="portrait"><img class="img-circle img-bordered-sm portrait-img" src="<?= $directoryAsset ?>/img/user1-128x128.jpg" alt="User Image"></i>

                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                        <h3 class="timeline-header"><a href="#">吴桢灿</a> <span class="desc">创建了任务</span></h3>

                        <div class="timeline-body">
                            APP端保存不了诊断报告问题
                        </div>
                    </div>
                </li>
                <!-- END timeline item -->

                <!-- timeline item -->
                <li>
                    <i class="portrait"><img class="img-circle img-bordered-sm portrait-img" src="<?= $directoryAsset ?>/img/user6-128x128.jpg" alt="User Image"></i>

                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                        <h3 class="timeline-header"><a href="#">吴桢灿</a> <span class="desc">开始处理这条任务</span></h3>

                        <div class="timeline-body">
                            优化 首页 “为您推荐”优化机制，推荐的期末、期中、中考真题/模拟、月考卷根据其适用地域属性推送给用户
                        </div>
                    </div>
                </li>
                <!-- END timeline item -->

                <!-- timeline item -->
                <li>
                    <i class="portrait"><img class="img-circle img-bordered-sm portrait-img" src="<?= $directoryAsset ?>/img/user3-128x128.jpg" alt="User Image"></i>

                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                        <h3 class="timeline-header"><a href="#">吴桢灿</a> <span class="desc">开始处理这条任务</span></h3>

                        <div class="timeline-body">
                            优化 首页 “为您推荐”优化机制，推荐的期末、期中、中考真题/模拟、月考卷根据其适用地域属性推送给用户
                        </div>
                    </div>
                </li>
                <!-- END timeline item -->

                <!-- timeline item -->
                <li>
                    <i class="portrait"><img class="img-circle img-bordered-sm portrait-img" src="<?= $directoryAsset ?>/img/user3-128x128.jpg" alt="User Image"></i>

                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                        <h3 class="timeline-header"><a href="#">吴桢灿</a> <span class="desc">开始处理这条任务</span></h3>

                        <div class="timeline-body">
                            优化 首页 “为您推荐”优化机制，推荐的期末、期中、中考真题/模拟、月考卷根据其适用地域属性推送给用户
                        </div>
                    </div>
                </li>
                <!-- END timeline item -->


                <!-- timeline item -->
                <li>
                    <!--                    <i class="portrait"><img class="img-circle img-bordered-sm portrait-img" src="--><?//= $directoryAsset ?><!--/img/user7-128x128.jpg" alt="User Image"></i>-->

                    <i class="fa fa-camera bg-purple"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i></span>

                        <h3 class="timeline-header no-border"><a href="#">end</a>
                        </h3>

                    </div>
                </li>
                <!-- END timeline item -->


            </ul>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->


</section>
