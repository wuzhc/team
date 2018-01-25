<div class="box box-solid">
    <a href="<?= \yii\helpers\Url::to(['task/index', 'projectID' => $projectID, 'categoryID' => $categoryID, 'me' => ($me+1)%2])?>" class="btn btn-success" style="width: 100%"><?= $me == 1 ? '所有项目任务' : '我的任务'?></a>
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
        <ul class="nav nav-pills nav-stacked">
<!--            --><?php //if (!empty($categories)) { ?>
<!--                --><?php //foreach ($categories as $k => $category) { ?>
<!--                    <li --><?//= isset($categoryID) && ($category->id == $categoryID) ? 'class="active"' : ''?><!-->
<!--                        <a href="javaScript:void(0)" data-id="--><?//=$category->id?><!--"><i class="fa fa-inbox"></i>--><?//= $category->fdName?><!--</a>-->
<!--                    </li>-->
<!--                --><?php //} ?>
<!--            --><?php //} ?>
        </ul>
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

<!--<div class="box box-solid">-->
<!--    <div class="box-header with-border">-->
<!--        <h3 class="box-title">标签</h3>-->
<!---->
<!--        <div class="box-tools">-->
<!--            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>-->
<!--            </button>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="box-body no-padding">-->
<!--        <ul class="nav nav-pills nav-stacked" id="task-label">-->
<!--            --><?php //if (!empty($labels)) { ?>
<!--                --><?php //foreach ($labels as $k => $label) { ?>
<!--                    <li>-->
<!--                        <a href="javaScript:void(0)" ><i class="fa fa-circle-o text---><?//= $label->fdColor?><!--"></i>--><?//= $label->fdName?><!--</a>-->
<!--                    </li>-->
<!--                --><?php //} ?>
<!--            --><?php //} ?>
<!--        </ul>-->
<!--    </div>-->
<!--    <!-- /.box-body -->
<!--</div>-->
<!--<!-- /.box -->

<script>
    <?php $this->beginBlock('taskCategory') ?>
    $(function () {

        (function () {
            var url = "<?=\yii\helpers\Url::to(['task/stat-tasks'])?>";
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

    });
    <?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['taskCategory'], \yii\web\View::POS_END); ?>
