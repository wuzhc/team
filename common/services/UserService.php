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
        $collection->insert([
            'memberID' => $member->id,
            'time'     => time(),
            'date'     => date('Y-m-d'),
            'ip'       => ClientUtil::getClientIp()
        ]);
    }

    /**
     * 用户信息更新
     * @param $memberID
     * @param $args
     * <pre>
     * [
     *     'fdQQ' => '', // qq
     *     'fdIntro' => '', // 简介
     *     'fdSex' => '', // 性别
     * ]
     * </pre>
     * @return bool
     * @since 2017-08-09
     */
    public function updateInfo($memberID, $args)
    {
        if (!$memberID) {
            return false;
        }
        $memberInfo = MemberInformation::find()->where(['fdMemberID' => $memberID])->one();
        if (!$memberInfo) {
            $memberInfo = new MemberInformation();
            $memberInfo->fdMemberID = $memberID;
        }

        $columns = $memberInfo->attributes();
        foreach ($args as $key => $value) {
            if (!in_array($key, $columns)) {
                continue;
            }
            $memberInfo->$key = $value;
        }

        $res = $memberInfo->save();
        if (!$res && YII_DEBUG) {
            d($memberInfo->getErrors());
        }
        return $res;
    }

    /**
     * 会员信息
     * @param $memberID
     * @return array
     * @since 2017-08-09
     */
    public function info($memberID)
    {
        $data = [];
        $member = $this->getMemberByID($memberID);
        if (!$member) {
            return $data;
        }
        $data['id'] = $member->id;
        $data['login'] = htmlspecialchars($member->fdLogin);
        $data['name'] = htmlspecialchars($member->fdName);
        $data['nickname'] = htmlspecialchars($member->fdNickname);
        $data['phone'] = $member->fdPhone;
        $data['email'] = $member->fdEmail;
        $data['verify'] = $member->fdVerify;
        $data['lastIP'] = $member->fdLastIP;
        $data['lastTime'] = $member->fdLastTime;

        /** @var MemberInformation $memberInfo */
        $memberInfo = MemberInformation::find()->where(['fdMemberID' => $member->id])->one();
        if ($memberInfo) {
            $data['sex'] = htmlspecialchars($memberInfo->fdSex);
            $data['qq'] = htmlspecialchars($memberInfo->fdQQ);
            $data['intro'] = htmlspecialchars($memberInfo->fdIntro);
        }
        return $data;
    }

    /**
     * 统计会员数量
     * @param null $year
     * @param null $month
     * 参数说明：
     * 同时传递$year与$month两个参数将统计当前$month月份的每一天的数据量
     * 只传递$year参数将统计当前$year年份每一个月的数据量
     * 不传递参数时，将统计每一个年份的数据量
     * @return array
     * <pre>
     * [
     *      ['num' => '数量', 'time' => '年|月|日'],
     *      ['num' => '数量', 'time' => '年|月|日'],
     * ]
     * </pre>
     * @since 2016-08-31
     */
    public function statMembers($year = null, $month = null)
    {
        $redis = Yii::$app->redis;
        if ($redis && $data = $redis->get(Conf::REDIS_STAT_MEMBER)) {
            return json_decode($data, true);
        }

        $member = Member::find();
        $startTime = $endTime = null;

        if ($year && $month) {
            $time = strtotime(sprintf('%s-%s-01', $year, $month));
            $startTime = date('Y-m-d H:i:s', $time);
            $endTime = date('Y-m-d H:i:s', strtotime('+1 month', $time));
            $select = [
                'count(distinct id) as num',
                'dayofmonth(fdVerify) as time'
            ];
        } elseif ($year) {
            $time = strtotime(sprintf('%s-01', $year));
            $startTime = date('Y-m-d H:i:s', $time);
            $endTime = date('Y-m-d H:i:s', strtotime('+1 year', $time));
            $select = [
                'count(distinct id) as num',
                'month(fdVerify) as time'
            ];
        } else {
            $select = [
                'count(distinct id) as num',
                'DATE_FORMAT(fdVerify, "%Y") as time'
            ];
        }

        //统计时间范围
        if ($startTime && $endTime) {
            $member->andFilterWhere(['between', 'fdVerify', $startTime, $endTime]);
        }

        $data = $member->select($select)->groupBy('time')->asArray()->all();
        if ($data && $redis) {
            $redis->setex(Conf::REDIS_STAT_MEMBER, 3600, json_encode($data));
        }
        return $data;
    }

    /**
     * 保存用户游览记录
     * 说明：如果重复游览一个商品，则更新游览记录为最新时间
     * @param int $memberID 会员ID
     * @param int $goodsID 商品ID
     * @return bool
     * @since 2016-12-04
     */
    public function saveHistory($memberID, $goodsID)
    {
        if (!is_numeric($memberID) || !is_numeric($goodsID)) {
            return false;
        }

        Yii::$app->mongodb->getCollection(Conf::GOODS_VISIT)->update([
            'memberID' => $memberID,
            'goodsID'  => $goodsID
        ], [
            'memberID' => $memberID,
            'goodsID'  => $goodsID,
            'time'     => time(),
            'date'     => date('Y-m-d H:i:s', time())
        ], [
            'upsert' => true
        ]);
    }
}