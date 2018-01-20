<?php

namespace console\controllers;


use common\rbac\UserGroupRule;
use Yii;
use yii\console\Controller;

/**
 * RBAC权限管理
 * Class RbacController
 * @link http://www.yiichina.com/doc/guide/2.0/security-authorization
 * @package console\controllers
 * @author wuzhc <wuzhc2016@163.com>
 * @since 2018-01-14
 */
class RbacController extends Controller
{
    /**
     * 权限管理——角色初始化（包括超级管理员，普通管理员，普通成员，游客四种角色）
     * @since 2018-01-18
     */
    public function actionInit()
    {
        echo "Begin: \n";

        $auth = Yii::$app->authManager;

        // 添加规则
        $userGroupRule = new UserGroupRule();
        $auth->add($userGroupRule);
        echo "- Add userGroupRule successfully \n";

        // 超级管理员角色
        $super = $auth->createRole('super');
        $super->ruleName = $userGroupRule->name;
        $super->description = '超级管理员';
        $auth->add($super);
        echo "- Add super role successfully \n";

        // 管理员角色
        $admin = $auth->createRole('admin');
        $admin->ruleName = $userGroupRule->name;
        $admin->description = '普通管理员';
        $auth->add($admin);
        $auth->addChild($super, $admin);
        echo "- Add the admin role and make it a child role of the super role successfully\n";

        // 普通成员
        $member = $auth->createRole('member');
        $member->ruleName = $userGroupRule->name;
        $member->description = '普通成员';
        $auth->add($member);
        $auth->addChild($admin, $member);
        echo "- Add the member role and make it a child role of the admin role successfully\n";

        // 游客
        $guest = $auth->createRole('guest');
        $guest->ruleName = $userGroupRule->name;
        $guest->description = '游客';
        $auth->add($guest);
        $auth->addChild($member, $guest);
        echo "- Add the guest role and make it a child role of the member role successfully\n";

        // 导入成员
        $importUser = $auth->createPermission('importUser');
        $importUser->description = '导入成员';
        $importUser->ruleName = null;
        $auth->add($importUser);
        $auth->addChild($admin, $importUser);
        echo "- Add importUser permission and assign to admin role successfully\n";

        // 设置管理员
        $setAdmin = $auth->createPermission('setAdmin');
        $setAdmin->description = '设置管理员';
        $setAdmin->ruleName = null;
        $auth->add($setAdmin);
        $auth->addChild($super, $setAdmin);
        echo "- Add setAdmin permission and assign to super role successfully\n";

        // 删除普通成员
        $delMember = $auth->createPermission('delMember');
        $delMember->description = '删除普通成员';
        $delMember->ruleName = null;
        $auth->add($delMember);
        $auth->addChild($admin, $delMember);
        echo "- Add delMember permission and assign to admin role successfully\n";

        // 创建团队
        $createTeam = $auth->createPermission('createTeam');
        $createTeam->description = '创建团队';
        $createTeam->ruleName = null;
        $auth->add($createTeam);
        $auth->addChild($admin, $createTeam);
        echo "- Add createTeam permission and assign to admin role successfully\n";

        // 编辑团队
        $editTeam = $auth->createPermission('editTeam');
        $editTeam->description = '编辑团队';
        $editTeam->ruleName = null;
        $auth->add($editTeam);
        $auth->addChild($admin, $editTeam);
        echo "- Add editTeam permission and assign to admin role successfully\n";

        // 删除团队
        $delTeam = $auth->createPermission('delTeam');
        $delTeam->description = '删除团队';
        $delTeam->ruleName = null;
        $auth->add($delTeam);
        $auth->addChild($admin, $delTeam);
        echo "- Add delTeam permission and assign to admin role successfully\n";

        // 创建项目
        $createProject = $auth->createPermission('createProject');
        $createProject->description = '创建项目';
        $createProject->ruleName = null;
        $auth->add($createProject);
        $auth->addChild($admin, $createProject);
        echo "- Add createProject permission and assign to admin role successfully\n";

        // 编辑项目
        $editProject = $auth->createPermission('editProject');
        $editProject->description = '编辑项目';
        $editProject->ruleName = null;
        $auth->add($editProject);
        $auth->addChild($admin, $editProject);
        echo "- Add editProject permission and assign to admin role successfully\n";

        // 删除项目
        $delProject = $auth->createPermission('delProject');
        $delProject->description = '编辑项目';
        $delProject->ruleName = null;
        $auth->add($delProject);
        $auth->addChild($admin, $delProject);
        echo "- Add delProject permission and assign to admin role successfully\n";

        echo "- All done \n";
    }

    /**
     * 清除所有权限
     * @since 2018-01-20
     */
    public function actionClear()
    {
        do {
            fwrite(STDOUT, "Are you sure clear all rbac? [y/n] \n");
            $act = trim(fgets(STDIN));
            if ($act == 'n') {
                exit;
            } elseif ($act == 'y') {
                $auth = Yii::$app->authManager;
                $auth->removeAll();
                echo "Remove all successfully \n";
                exit;
            }
        } while (true);
    }

    /**
     * 重置所有权限
     * @since 2018-01-20
     */
    public function actionReset()
    {
        do {
            fwrite(STDOUT, "Are you sure reset all rbac? [y/n] \n");
            $act = trim(fgets(STDIN));
            if ($act == 'n') {
                exit;
            } elseif ($act == 'y') {
                $auth = Yii::$app->authManager;
                $auth->removeAll();
                $this->actionInit();
                echo "Reset all successfully \n";
                exit;
            }
        } while (true);
    }

}