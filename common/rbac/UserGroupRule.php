<?php

namespace common\rbac;


use common\config\Conf;
use Yii;
use yii\rbac\Rule;

/**
 * 角色分组规则
 * Class UserGroupRule
 * @package common\rbac
 * @author wuzhc
 * @since 2018-01-17
 */
class UserGroupRule extends Rule
{
    public $name = 'userGroup';

    /**
     * 角色判断
     * @param int|string     $user
     * @param \yii\rbac\Item $item
     * @param array          $params
     * @return bool
     */
    public function execute($user, $item, $params)
    {
        if (!Yii::$app->user->isGuest) {
            $roleID = Yii::$app->user->identity->fdRoleID;
            if ($item->name === 'super') {
                return $roleID == Conf::ROLE_SUPER;
            } elseif ($item->name === 'admin') {
                return $roleID == Conf::ROLE_ADMIN || $roleID == Conf::ROLE_SUPER;
            } elseif ($item->name === 'member') {
                return $roleID == Conf::ROLE_SUPER || $roleID == Conf::ROLE_ADMIN
                    || $roleID == Conf::ROLE_MEMBER;
            }
        }

        return false;
    }
}