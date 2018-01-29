<?php

namespace common\services;


use common\config\Conf;
use common\models\Task;
use common\models\User;
use common\utils\ClientUtil;
use MongoDB\BSON\UTCDateTime;
use Yii;
use yii\helpers\Html;
use yii\mongodb\Query;

/**
 * 日志服务类
 * Class LogService
 * @package common\services
 * @author wuzhc <wuzhc2016@163.com>
 * @since 2018-01-25
 */
class LogService extends AbstractService
{
    /**
     * Returns the static model.
     * @param string $className Service class name.
     * @return LogService the static model class
     */
    public static function factory($className = __CLASS__)
    {
        return parent::factory($className);
    }

    /**
     * 保存用户操作日志
     * @param $args
     * @return bool
     * @since 2018-01-25
     */
    public function saveHandleLog($args)
    {
        $data = [];

        // 操作者
        if (!empty($args['operatorID'])) {
            $data['operatorID'] = (int)$args['operatorID'];
        } else {
            if (YII_DEBUG) {
                Yii::$app->end('operatorID empty');
            }
            return false;
        }

        // 操作者
        if (!empty($args['operator'])) {
            $data['operator'] = $args['operator'];
        } else {
            if (YII_DEBUG) {
                Yii::$app->end('operator empty');
            }
            return false;
        }

        // 操作者头像
        if (!empty($args['portrait'])) {
            $data['portrait'] = $args['portrait'];
        }

        // 接受者
        if (!empty($args['receiverID'])) {
            $data['receiverID'] = (int)$args['receiverID'];
        }

        // 公司
        if (!empty($args['companyID'])) {
            $data['companyID'] = (int)$args['companyID'];
        } else {
            if (YII_DEBUG) {
                Yii::$app->end('companyID empty');
            }
            return false;
        }

        // 日志标题
        if (!empty($args['title'])) {
            $data['title'] = $args['title'];
        } else {
            if (YII_DEBUG) {
                Yii::$app->end('title error');
            }
            return false;
        }

        // 日志内容
        if (!empty($args['content'])) {
            $data['content'] = $args['content'];
        } else {
            if (YII_DEBUG) {
                Yii::$app->end('content error');
            }
            return false;
        }

        // 链接地址
        if (!empty($args['url'])) {
            $data['url'] = $args['url'];
        } else {
            if (YII_DEBUG) {
                Yii::$app->end('url empty');
            }
        }

        // 操作对象
        if (!empty($args['targetID'])) {
            $data['targetID'] = (int)$args['targetID'];
        } else {
            if (YII_DEBUG) {
                Yii::$app->end('targetID empty');
            }
            return false;
        }

        // 操作对象类型 see Yii::$app->params['handleTargetType']
        if (!empty($args['targetType']) &&
            in_array($args['targetType'], array_keys(Yii::$app->params['handleTargetType']))
        ) {
            $data['targetType'] = (int)$args['targetType'];
        } else {
            if (YII_DEBUG) {
                Yii::$app->end('targetType empty or error');
            }
            return false;
        }

        $data['date'] = new UTCDateTime(time() * 1000);

        /** @var \yii\mongodb\Connection $mongo */
        $mongo = Yii::$app->mongodb;
        $collection = $mongo->getCollection(Conf::M_HANDLE_LOG);
        return $collection->insert($data) ? true : false;
    }

    /**
     * 获取操作日志
     * @param array $args
     * @return array
     * @since 2018-01-25
     */
    public function getHandleLogs($args = [])
    {
        $condition = [];
        if (!empty($args['begin'])) {
            $condition['date']['$gt'] = new UTCDateTime($args['begin'] * 1000);
        }
        if (!empty($args['end'])) {
            $condition['date']['$lt'] = new UTCDateTime($args['end'] * 1000);
        }
        if (!empty($args['targetID'])) {
            $condition['targetID'] = (int)$args['targetID'];
        }
        if (!empty($args['targetType'])) {
            $condition['targetType'] = (int)$args['targetType'];
        }
        if (!empty($args['companyID'])) {
            $condition['companyID'] = (int)$args['companyID'];
        }
        if (!empty($args['limit'])) {
            $limit = (int)$args['limit'];
        } else {
            $limit = 15;
        }
        if (!empty($args['offset'])) {
            $offset = (int)$args['offset'];
        } else {
            $offset = 0;
        }

        $rows = (new Query())->from(Conf::M_HANDLE_LOG)
            ->where($condition)
            ->limit($limit)
            ->orderBy(['date' => SORT_DESC])
            ->offset($offset)
            ->all();

        $data = [];
        $hasInDay = [];

        foreach ($rows as $row) {
            $temp = [];

            $tz = new \DateTimeZone('PRC');
            $date = $row['date']->toDateTime()->setTimezone($tz)->format('Y-m-d H:i:s');
            $temp['date'] = $date;

            $day = date('Y-m-d', strtotime($date));
            if (!in_array($day, $hasInDay)) {
                $temp['day'] = $day;
                $hasInDay[] = $day;
            }

            $temp['url'] = $row['url'];
            $temp['title'] = Html::encode($row['title']);
            $temp['content'] = Html::encode($row['content']);
            $temp['operator'] = $row['operator'];
            $temp['operatorID'] = $row['operatorID'];
            $temp['portrait'] = $row['portrait'] ?: Yii::$app->params['defaultPortrait'][rand(0,10)];
            $data[] = $temp;
        }

        return $data;
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
        $user = UserService::factory()->getUserInstance($user);
        if (null === $user) {
            return false;
        }

        /** @var \yii\mongodb\Connection $mongo */
        $mongo = Yii::$app->mongodb;
        $collection = $mongo->getCollection(Conf::M_USER_LOGIN_LOG);

        return $collection->insert([
            'userID'  => $user->id,
            'date'    => new UTCDateTime(time() * 1000),
            'loginIP' => ClientUtil::getClientIp()
        ]) ? true : false;
    }
}