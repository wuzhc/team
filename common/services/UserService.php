<?php

namespace common\services;


use common\config\Conf;
use common\models\User;
use common\services\AbstractService;
use common\utils\ClientUtil;
use common\utils\VerifyUtil;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Yii;
use yii\helpers\StringHelper;

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
     * @author wuzhc
     * @since 2018-01-15
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
     * @author wuzhc
     * @since 2018-01-15
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
     * @author wuzhc
     * @since 2018-01-15
     */
    public function saveLoginLog($user)
    {
        $user = $this->getUserInstance($user);
        if (null === $user) {
            return false;
        }

        if (true === MONGO_ON) {
            /** @var \yii\mongodb\Connection $mongo */
            $mongo = Yii::$app->mongodb;
            $collection = $mongo->getCollection(Conf::USER_LOGIN_LOG);

            return $collection->insert([
                'userID'  => $user->id,
                'date'    => date('Y-m-d'),
                'loginIP' => ClientUtil::getClientIp()
            ]) ? true : false;
        }
    }

    /**
     * 新建用户
     * @param $args
     * @return User|null
     * @author wuzhc
     * @since 2018-01-16
     */
    public function createUser($args)
    {
        $user = new User();
        $user->fdLogin = $args['login'];
        $user->fdPhone = $args['phone'];
        $user->fdEmail = $args['email'];
        $user->fdName = $args['name'];
        $user->fdStatus = Conf::ENABLE;
        $user->fdCreate = date('Y-m-d H:i:s');
        $user->fdVerify = date('Y-m-d H:i:s');
        $user->setPassword($args['password']);
        $user->generateAuthKey();

        if ($user->save()) {
            return $user;
        } else {

            // 调试模式下输出错误信息
            if (YII_DEBUG) {
                var_dump($user->getErrors());
                exit;
            }

            return null;
        }
    }

    /**
     * 以邮箱为注册号，批量保存用户
     * @param $emails
     * @return array|int
     * @author wuzhc
     * @since 2018-01-17
     */
    public function batchCreateUser($emails)
    {
        if (!$emails || !is_array($emails)) {
            return [];
        }

        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('send_email', false, false, false, false);

        $values = [];
        foreach ($emails as $k => $email) {
            $values[$k] = [
                '佚名',
                't_' . VerifyUtil::getRandomCode(6, 3),
                Conf::ROLE_GUEST,
                $email,
                '游客',
                date('Y-m-d H:i:s'),
                Yii::$app->security->generatePasswordHash('123456'),
                Yii::$app->security->generateRandomString()
            ];

            $msg = new AMQPMessage($email);
            $channel->basic_publish($msg, '', 'send_email');
        }

        $channel->close();
        $connection->close();

        $fields = [
            'fdName',
            'fdLogin',
            'fdRoleID',
            'fdEmail',
            'fdPosition',
            'fdCreate',
            'fdPwdHash',
            'fdAuthKey',
        ];

        return Yii::$app->db->createCommand()->batchInsert(User::tableName(), $fields, $values)->execute();
    }
}