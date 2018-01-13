<?php

namespace common\services;


use common\config\Conf;
use common\models\User;
use common\services\AbstractService;
use common\utils\ClientUtil;
use Yii;

/**
 * Class UserService
 * 会员服务类
 * @package common\services
 * @since 2017-08-09
 * @author wuzhc2016@163.com
 */
class UserService extends AbstractService
{

    /**
     * Returns the static model.
     * @param string $className Service class name.
     * @return UserService the static model class
     */
    public static function factory($className = __CLASS__)
    {
        return parent::factory($className);
    }

    /**
     * 根据账号找会员对象
     * @param string $account login|phone|email
     * @return array|null|\yii\db\ActiveRecord
     * @since 2017-08-09
     */
    public function getUserObjByAccount($account)
    {
        return User::find()
            ->orWhere(['fdLogin' => $account])
            ->orWhere(['fdEmail' => $account])
            ->orWhere(['fdPhone' => $account])
            ->limit(1)
            ->one();
    }

    /**
     * @param $user
     * @return null|User
     */
    public function getUserInstance($user)
    {
        if (is_numeric($user)) {
            $user = User::findOne(['id' => $user]);
        }

        if (!($user instanceof User)) {
            return null;
        }

        return $user;
    }



    /**
     * 保存登录日志
     * @param int|User $user 用户
     * @return bool
     * @since 2016-12-08
     */
    public function saveLoginLog($user)
    {
        $user = $this->getUserInstance($user);
        if (null === $user) {
            return false;
        }

        $collection = Yii::$app->mongodb->getCollection(Conf::USER_LOGIN_LOG);
        return $collection->insert([
            'userID'  => $user->id,
            'date'    => date('Y-m-d'),
            'loginIP' => ClientUtil::getClientIp()
        ]);
    }
}