<?php
/** @var \common\models\User $identify */
$identify = Yii::$app->user->identity;
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= \common\services\UserService::factory()->getUserPortrait(Yii::$app->user->identity) ?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?=$identify->fdName?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> 当前有&nbsp;<b id="online-people">0</b>&nbsp;人在线</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    ['label' => '最新动态', 'icon' => 'car', 'url' => ['/default/dynamic']],
                    ['label' => '团队成员', 'icon' => 'space-shuttle', 'url' => ['/team/index']],
                    ['label' => '项目管理', 'icon' => 'motorcycle', 'url' => ['/project/index'], 'visible' => ($identify->fdRoleID === \common\config\Conf::ROLE_SUPER || $identify->fdRoleID === \common\config\Conf::ROLE_ADMIN)],
                    ['label' => '成员管理', 'icon' => 'subway', 'url' => ['/user-manage/index'], 'visible' => ($identify->fdRoleID === \common\config\Conf::ROLE_SUPER || $identify->fdRoleID === \common\config\Conf::ROLE_ADMIN)],
                    ['label' => '权限列表', 'icon' => 'ship', 'url' => ['/rbac/roles']],
                ],
            ]
        ) ?>

    </section>

</aside>
