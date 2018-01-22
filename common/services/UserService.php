<?php

namespace common\services;


use common\config\Conf;
use common\models\User;
use common\utils\ClientUtil;
use common\utils\VerifyUtil;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class UserService
 * 会员服务类
 * @package common\services
 * @since 2018-01-15
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
     * @return array|null|\yii\db\ActiveRecord|User
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
            $collection = $mongo->getCollection(Conf::M_USER_LOGIN_LOG);

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
        $user->fdRoleID = Conf::ROLE_SUPER;
        $user->fdPosition = 'Boss';
        $user->fdPortrait = Yii::$app->params['defaultPortrait'][round(0,12)];
        $user->fdCreate = date('Y-m-d H:i:s');
        $user->fdVerify = date('Y-m-d H:i:s');
        $user->fdCompanyID = $args['companyID'];
        $user->fdSalt = VerifyUtil::getRandomCode(6, 3);
        $user->fdPassword = md5(md5($args['password']) . $user->fdSalt);

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
     * @param int $companyID 对应tbCompany.id
     * @param array $accounts
     * @return array|int
     * @author wuzhc
     * @since 2018-01-17
     */
    public function batchCreateUser($companyID, array $accounts)
    {
        if (!$companyID || !$accounts || !is_array($accounts)) {
            return [];
        }

        $values = [];
        foreach ($accounts as $k => $account) {
            $salt = VerifyUtil::getRandomCode(6, 3);
            $values[$k] = [
                $account['name'] ,
                $account['login'],
                Conf::ROLE_MEMBER,
                $companyID,
                Conf::USER_ENABLE,
                $account['email'],
                $account['phone'],
                Yii::$app->params['defaultPortrait'][$k%13],
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
                md5(md5('123456') . $salt),
                $salt,
            ];
        }

        $fields = [
            'fdName',
            'fdLogin',
            'fdRoleID',
            'fdCompanyID',
            'fdStatus',
            'fdEmail',
            'fdPhone',
            'fdPortrait',
            'fdCreate',
            'fdVerify',
            'fdPassword',
            'fdSalt',
        ];

        return Yii::$app->db->createCommand()
            ->batchInsert(User::tableName(), $fields, $values)
            ->execute();
    }

    /**
     * 用户登录
     * @param int|User $user
     * @param int $rememberMe 记住密码
     * @return bool
     * @since 2018-01-18
     */
    public function login($user, $rememberMe = 2592000)
    {
        $user = $this->getUserInstance($user);
        if (null === $user) {
            return false;
        }

        // 注册登录后事件
        Yii::$app->user->on('afterLogin', function ($event) {
            $this->saveLoginLog($event->identity->id);
            $event->identity->fdLastIP = ClientUtil::getClientIp();
            $event->identity->fdLastTime = date('Y-m-d H:i:s');
            $event->identity->save();
        });

        if (Yii::$app->user->login($user, $rememberMe)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取用户
     * @param array $args
     * @see findUserCriteria
     * @return array|\yii\db\ActiveRecord[]
     * @since 2018-01-22
     */
    public function getUsers(array $args)
    {
        return $this->findUserCriteria($args)->all();
    }

    /**
     * 获取用户ID
     * @param array $args
     * @see findUserCriteria
     * @return array
     * @since 2018-01-22
     */
    public function getUserIDs(array $args)
    {
        $args['select'] or $args['select'] = ['id'];
        $users = $this->findUserCriteria($args)->all();
        return $users ? ArrayHelper::getColumn($users, 'id') : [];
    }

    /**
     * 统计用户总数
     * @see findUserCriteria
     * @param array $args
     * @return int|string
     */
    public function countUsers(array $args)
    {
        return $this->findUserCriteria($args)->count();
    }

    /**
     * 用户查询条件
     * @param $args
     * @return \yii\db\ActiveQuery
     */
    protected function findUserCriteria($args)
    {
        $user = User::find();

        if (is_numeric($args['limit'])) {
            $user->limit($args['limit']);
        }
        if (is_numeric($args['offset'])) {
            $user->offset($args['offset']);
        }
        if (is_numeric($args['status'])) {
            $user->andWhere(['fdStatus' => $args['status']]);
        }
        if (is_numeric($args['teamID'])) {
            $user->andWhere(['fdTeamID' => $args['teamID']]);
        }
        if (is_numeric($args['companyID'])) {
            $user->andWhere(['fdCompanyID' => $args['companyID']]);
        }
        if (is_array($args['select'])) {
            $user->select($args['select']);
        }
        if (is_array($args['order'])) {
            $user->orderBy($args['order']);
        }
        if (is_array($args['group'])) {
            $user->groupBy($args['group']);
        }
        if ($args['with']) {
            $user->with($args['with']);
        }

        return $user;
    }
}