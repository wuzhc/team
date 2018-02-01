<?php

namespace frontend\controllers;


use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class RbacController
 * @package frontend\controllers
 * @author wuzhc <wuzhc2016@163.com>
 * @since 2018-02-01
 */
class RbacController extends BaseController
{
    public $layout = 'main-member';

    /**
     * 角色列表
     * @return string
     * @since 2018-02-01
     */
    public function actionRoles()
    {
        return $this->render('roles', ['roles' => Yii::$app->authManager->getRoles()]);
    }

    /**
     * 角色权限列表
     * @param string $name
     * @return string
     * @throws NotFoundHttpException
     * @since 2018-02-01
     */
    public function actionRolePermissions($name = 'admin')
    {
        $auth = Yii::$app->authManager;

        $role = $auth->getRole($name);
        if (!$role) {
            throw new NotFoundHttpException('角色不存在');
        }

        return $this->render('role_permissions', [
            'role' => $role,
            'permissions' => $auth->getPermissionsByRole($name)
        ]);
    }

}