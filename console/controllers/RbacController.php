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
        echo "正在初始化rbac... \n";

        $auth = Yii::$app->authManager;

        // 添加规则
        $userGroupRule = new UserGroupRule();
        $auth->add($userGroupRule);
        echo "- 成功添加角色规则 \n";

        // 超级管理员角色
        $super = $auth->createRole('super');
        $super->ruleName = $userGroupRule->name;
        $super->description = '超级管理员';
        $auth->add($super);
        echo "- 成功添加超级管理员角色（super） \n";

        // 管理员角色
        $admin = $auth->createRole('admin');
        $admin->ruleName = $userGroupRule->name;
        $admin->description = '普通管理员';
        $auth->add($admin);
        $auth->addChild($super, $admin);
        echo "- 成功添加管理员角色（admin），并作为超级管理员角色的子角色 \n";

        // 普通成员
        $member = $auth->createRole('member');
        $member->ruleName = $userGroupRule->name;
        $member->description = '普通成员';
        $auth->add($member);
        $auth->addChild($admin, $member);
        echo "- 成功添加成员角色（member），并作为管理员角色的子角色 \n";

        // 游客
        $guest = $auth->createRole('guest');
        $guest->ruleName = $userGroupRule->name;
        $guest->description = '游客';
        $auth->add($guest);
        $auth->addChild($member, $guest);
        echo "- 成功添加游客角色（guest），并作为成员角色的子角色 \n";

        // 导入成员
        $importUser = $auth->createPermission('importUser');
        $importUser->description = '导入成员';
        $importUser->ruleName = null;
        $auth->add($importUser);
        $auth->addChild($admin, $importUser);
        echo "- 成功添加导入成员权限（importUser），并赋给了管理员角色 \n";

        // 设置管理员
        $setAdmin = $auth->createPermission('setAdmin');
        $setAdmin->description = '设置管理员';
        $setAdmin->ruleName = null;
        $auth->add($setAdmin);
        $auth->addChild($super, $setAdmin);
        echo "- 成功添加设置管理管理权限（setAdmin），并赋给了超级管理员角色 \n";

        // 删除普通成员
        $delMember = $auth->createPermission('delMember');
        $delMember->description = '删除普通成员';
        $delMember->ruleName = null;
        $auth->add($delMember);
        $auth->addChild($admin, $delMember);
        echo "- 成功添加删除成员权限（delMember），并赋给了管理角色 \n";

        // 创建团队
        $createTeam = $auth->createPermission('createTeam');
        $createTeam->description = '创建团队';
        $createTeam->ruleName = null;
        $auth->add($createTeam);
        $auth->addChild($admin, $createTeam);
        echo "- 成功添加创建团队权限（createTeam），并赋给了管理角色 \n";

        // 编辑团队
        $editTeam = $auth->createPermission('editTeam');
        $editTeam->description = '编辑团队';
        $editTeam->ruleName = null;
        $auth->add($editTeam);
        $auth->addChild($admin, $editTeam);
        echo "- 成功添加编辑团队权限（editTeam），并赋给了管理角色 \n";

        // 删除团队
        $delTeam = $auth->createPermission('delTeam');
        $delTeam->description = '删除团队';
        $delTeam->ruleName = null;
        $auth->add($delTeam);
        $auth->addChild($admin, $delTeam);
        echo "- 成功添加删除团队权限（delTeam），并赋给了管理角色 \n";

        // 创建项目
        $createProject = $auth->createPermission('createProject');
        $createProject->description = '创建项目';
        $createProject->ruleName = null;
        $auth->add($createProject);
        $auth->addChild($admin, $createProject);
        echo "- 成功添加创建项目权限（createProject），并赋给了管理角色 \n";

        // 编辑项目
        $editProject = $auth->createPermission('editProject');
        $editProject->description = '编辑项目';
        $editProject->ruleName = null;
        $auth->add($editProject);
        $auth->addChild($admin, $editProject);
        echo "- 成功添加编辑项目权限（editProject），并赋给了管理角色 \n";

        // 删除项目
        $delProject = $auth->createPermission('delProject');
        $delProject->description = '编辑项目';
        $delProject->ruleName = null;
        $auth->add($delProject);
        $auth->addChild($admin, $delProject);
        echo "- 成功添加删除项目权限（delProject），并赋给了管理角色 \n";

        // 项目成员管理
        $setMembers = $auth->createPermission('setMembers');
        $setMembers->description = '项目人员管理';
        $setMembers->ruleName = null;
        $auth->add($setMembers);
        $auth->addChild($admin, $setMembers);
        echo "- 成功添加项目成员管理权限（setMembers），并赋给了管理角色 \n";

        echo "初始化完成 \n";
    }

    /**
     * 清除所有权限
     * @since 2018-01-20
     */
    public function actionClear()
    {
        do {
            fwrite(STDOUT, "你确定要删除所有的rbac吗? [y/n] \n");
            $act = trim(fgets(STDIN));
            if ($act == 'n') {
                exit;
            } elseif ($act == 'y') {
                echo "正在删除... \n";
                $auth = Yii::$app->authManager;
                $auth->removeAll();
                echo "删除完成 \n";
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
            fwrite(STDOUT, "你确定要重置rbac吗? [y/n] \n");
            $act = trim(fgets(STDIN));
            if ($act == 'n') {
                exit;
            } elseif ($act == 'y') {
                echo "正在重置... \n";
                $auth = Yii::$app->authManager;
                $auth->removeAll();
                $this->actionInit();
                echo "重置完成 \n";
                exit;
            }
        } while (true);
    }

}