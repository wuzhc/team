<?php

/* @var $this yii\web\View */

use frontend\assets\AppAsset;

$this->title = '动态消息';
AppAsset::registerJsFile($this, 'js/template.js');
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
    /*.timeline>li>.timeline-item {*/
        /*background: #f4f4f4;*/
    /*}*/
</style>

<section class="content">

    <!-- row -->
    <div class="row">
        <div class="col-md-12">
            <!-- The time line -->
            <ul class="timeline" id="task-list">
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

    <script type="text/html" id="dynamic-template">
        <% var dayArr = []; %>
        <% for(var i = 0, len = list.length; i < len; i++) { %>
            <% if (list[i].day) { %>
                <li class="time-label">
                          <span class="bg-green">
                            <%=list[i].day%>
                          </span>
                </li>
            <% } %>
        <li>
            <i class="portrait"><img class="img-circle img-bordered-sm portrait-img" src="<%=list[i].portrait%>" alt="User Image"></i>
            <div class="timeline-item">
                <span class="time"><i class="fa fa-clock-o"></i><%=list[i].date%></span>
                <h3 class="timeline-header"><a href="#"><%=list[i].operator%></a> <span class="desc"><%=list[i].action%>了<%=list[i].type%></span></h3>
                <div class="timeline-body">
                    <%=list[i].target%>
                </div>
            </div>
        </li>
        <% } %>
    </script>

    <script>
        <?php $this->beginBlock('dynamic') ?>
        $(function () {

            var curPage = 1;
            var totalPage = 0;
            var ajaxBort = null;

            // 渲染列表
            function renderList(options) {
                var params = $.extend({
                    limit: 15
                }, options);
                var url = "<?=\yii\helpers\Url::to(['default/get-dynamic'])?>";

                ajaxBort && ajaxBort.abort();
                ajaxBort = $.ajax({
                    type: 'GET',
                    url: url,
                    data: params,
                    dataType: 'json'
                }).done(function (data) {
                    var list = data.rows || [];
                    var len = list.length;
                    var html = '';
                    if (len > 0) {
                        html = template.render('dynamic-template', {list: list});
                        $('#task-list').html(html);
                    } else {
                        $('#dynamic-list').html('');
                    }
                }).fail(function () {
                    $.showBox({msg: '系统繁忙~'});
                })
            }

            renderList({});

        });
        <?php $this->endBlock() ?>
    </script>
<?php $this->registerJs($this->blocks['dynamic'], \yii\web\View::POS_END); ?>