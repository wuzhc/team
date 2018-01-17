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

    public function actionP()
    {
        $auth = Yii::$app->authManager;

        // 添加规则
        $userGroupRule = new UserGroupRule();
        $auth->add($userGroupRule);

        // 超级管理员角色
        $super = $auth->createRole('super');
        $super->ruleName = $userGroupRule->name;
        $auth->add($super);

        // 管理员角色
        $admin = $auth->createRole('admin');
        $admin->ruleName = $userGroupRule->name;
        $auth->add($admin);
        $auth->addChild($super, $admin);

        // 普通成员
        $member = $auth->createRole('member');
        $member->ruleName = $userGroupRule->name;
        $auth->add($member);
        $auth->addChild($admin, $member);
    }

    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // 添加 "createPost" 权限
        $createPost = $auth->createPermission('createPost');
        $createPost->description = 'Create a post';
        $auth->add($createPost);

        // 添加 "updatePost" 权限
        $updatePost = $auth->createPermission('updatePost');
        $updatePost->description = 'Update post';
        $auth->add($updatePost);

        // 添加 "author" 角色并赋予 "createPost" 权限
        $author = $auth->createRole('author');
        $auth->add($author);
        $auth->addChild($author, $createPost);

        // 添加 "admin" 角色并赋予 "updatePost"
        // 和 "author" 权限
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $updatePost);
        $auth->addChild($admin, $author);

        // 为用户指派角色。其中 1 和 2 是由 IdentityInterface::getId() 返回的id （译者注：user表的id）
        // 通常在你的 User 模型中实现这个函数。
        $auth->assign($author, 2);
        $auth->assign($admin, 1);

        // 添加规则
        $rule = new \common\rbac\AuthorRule;
        $auth->add($rule);

// 添加 "updateOwnPost" 权限并与规则关联
        $updateOwnPost = $auth->createPermission('updateOwnPost');
        $updateOwnPost->description = 'Update own post';
        $updateOwnPost->ruleName = $rule->name;
        $auth->add($updateOwnPost);

// "updateOwnPost" 权限将由 "updatePost" 权限使用
        $auth->addChild($updateOwnPost, $updatePost);

// 允许 "author" 更新自己的帖子
        $auth->addChild($author, $updateOwnPost);

        if (\Yii::$app->user->can('createPost')) {
            // 建贴
        }
    }

}