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

        // 超级管理员角色
        $super = $auth->createRole('super');
        $super->ruleName = $userGroupRule->name;
        $super->description = '超级管理员';
        $auth->add($super);

        // 管理员角色
        $admin = $auth->createRole('admin');
        $admin->ruleName = $userGroupRule->name;
        $admin->description = '普通管理员';
        $auth->add($admin);
        $auth->addChild($super, $admin);

        // 普通成员
        $member = $auth->createRole('member');
        $member->ruleName = $userGroupRule->name;
        $member->description = '普通成员';
        $auth->add($member);
        $auth->addChild($admin, $member);

        // 游客
        $guest = $auth->createRole('guest');
        $guest->ruleName = $userGroupRule->name;
        $guest->description = '游客';
        $auth->add($guest);
        $auth->addChild($member, $guest);

        // 导入成员
        $importUser = $auth->createPermission('importUser');
        $importUser->description = '导入成员';
        $importUser->ruleName = null;
        $auth->add($importUser);
        $auth->addChild($admin, $importUser);

        // 设置管理员
        $setAdmin = $auth->createPermission('setAdmin');
        $setAdmin->description = '设置管理员';
        $setAdmin->ruleName = null;
        $auth->add($setAdmin);
        $auth->addChild($super, $setAdmin);

        // 删除普通成员
        $delMember = $auth->createPermission('delMember');
        $delMember->description = '删除普通成员';
        $delMember->ruleName = null;
        $auth->add($delMember);

        // 创建团队
        $createTeam = $auth->createPermission('createTeam');
        $createTeam->description = '创建团队';
        $createTeam->ruleName = null;
        $auth->add($createTeam);

        // 编辑团队
        $editTeam = $auth->createPermission('editTeam');
        $editTeam->description = '编辑团队';
        $editTeam->ruleName = null;
        $auth->add($editTeam);

        // 删除团队
        $delTeam = $auth->createPermission('delTeam');
        $delTeam->description = '删除团队';
        $delTeam->ruleName = null;
        $auth->add($delTeam);

        echo "successfully \n";
    }


}