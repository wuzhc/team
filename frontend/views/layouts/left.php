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
                    ['label' => '动态', 'icon' => 'file-code-o', 'url' => ['/default/dynamic']],
                    ['label' => '团队', 'icon' => 'fa fa-group', 'url' => ['/team/index']],
                    ['label' => '任务', 'icon' => 'dashboard', 'url' => ['/task/index','projectID' => 1]],
                    ['label' => '项目', 'icon' => 'dashboard', 'url' => ['/project/index'], 'visible' => ($identify->fdRoleID === \common\config\Conf::ROLE_SUPER || $identify->fdRoleID === \common\config\Conf::ROLE_ADMIN)],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    [
                        'label' => 'Some tools',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
                            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
                            [
                                'label' => 'Level One',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
                                    [
                                        'label' => 'Level Two',
                                        'icon' => 'circle-o',
                                        'url' => '#',
                                        'items' => [
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
