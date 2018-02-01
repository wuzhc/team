<?php

/* @var $role \yii\rbac\Role */
/* @var $permissions array */

use frontend\assets\AppAsset;

AppAsset::registerJsFile($this, 'js/template.js');
$this->title = $role->description;

$this->params['breadcrumbs'][] = ['label' => '角色列表', 'url' => ['rbac/roles']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="box box-success" id="user-manage">
        <div class="box-header">
            <h3 class="box-title"><?=$role->description.'的权限    '?></h3>

            <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                    <a href="<?= Yii::$app->request->referrer ?>" class="btn-sm btn-danger pull-right" id="team-url">返回</a>
                </div>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tbody>
                <tr>
                    <th>名称</th>
                    <th>描述</th>
                    <th>规则</th>
                    <th>创建时间</th>
                    <th>更新时间</th>
                </tr>
                <?php if (!empty($permissions)) { ?>
                <?php foreach ($permissions as $permission) { ?>
                    <tr>
                        <td><?= $permission->name ?></td>
                        <td><?= $permission->description ?></td>
                        <td><?= $permission->ruleName ?: '无' ?></td>
                        <td><?= date('Y-m-d H:i:s', $permission->createdAt) ?></td>
                        <td><?= date('Y-m-d H:i:s', $permission->updatedAt) ?></td>
                    </tr>
                <?php } ?>
                <?php } else { ?>
                    <div class="jumbotron">
                        <p class="lead">现在还没有权限.</p>
                    </div>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->

    <script>
        <?php $this->beginBlock('rbacList') ?>
        $(function () {

        })
        ;
        <?php $this->endBlock() ?>
    </script>
<?php $this->registerJs($this->blocks['rbacList'], \yii\web\View::POS_END); ?>