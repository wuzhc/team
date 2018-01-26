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
</style>

<section class="content">

    <!-- row -->
    <div class="row">
        <div class="col-md-12">
            <!-- The time line -->
            <ul class="timeline" id="task-list">
                <li class="text-center">
                    <h1><i class="fa fa-refresh fa-spin"></i></h1>
                </li>
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
                <h3 class="timeline-header"><a href="#"><%=list[i].operator%></a>
                    <span class="desc"><%=list[i].action%>了<%=list[i].type%>
                    <% if (list[i].acceptor) { %>
                        给<%=list[i].acceptor%>
                    <% } %>
                    </span>
                </h3>
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