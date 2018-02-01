<?php

/* @var $roles array */

use yii\helpers\Url;
use frontend\assets\AppAsset;
use common\config\Conf;

AppAsset::registerJsFile($this, 'js/template.js');

$this->title = '角色';
$this->params['breadcrumbs'][] = '角色列表';
?>
    <div class="box box-success" id="user-manage">
        <div class="box-header">
            <h3 class="box-title">所有角色</h3>

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
                    <th>操作</th>
                </tr>
                <?php foreach ((array)$roles as $role) { ?>
                <tr>
                    <td><?= $role->name ?></td>
                    <td><?= $role->description ?></td>
                    <td><?= $role->ruleName ?></td>
                    <td><?= date('Y-m-d H:i:s', $role->createdAt) ?></td>
                    <td><?= date('Y-m-d H:i:s', $role->updatedAt) ?></td>
                    <td>
                        <a href="<?= Url::to(['rbac/role-permissions', 'name' => $role->name]) ?>" title="查看">查看</a>
                    </td>
                </tr>
                <?php } ?>
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

    <script>
        <?php $this->beginBlock('rbacList') ?>
        $(function () {

        })
        ;
        <?php $this->endBlock() ?>
    </script>
<?php $this->registerJs($this->blocks['rbacList'], \yii\web\View::POS_END); ?>