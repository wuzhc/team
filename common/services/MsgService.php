<?php

namespace common\services;


use common\config\Conf;
use common\utils\HttpClient;
use MongoDB\BSON\UTCDateTime;
use Yii;
use yii\helpers\Html;
use yii\mongodb\Query;

class MsgService extends AbstractService
{
    /**
     * 消息服务类
     * Returns the static model.
     * @param string $className Service class name.
     * @return MsgService the static model class
     * @author wuzhc <wuzhc2016@163.com>
     * @since 2018-01-29
     */
    public static function factory($className = __CLASS__)
    {
        return parent::factory($className);
    }

    /**
     * 保存消息
     * @param $args
     * @return bool
     * @since 2018-01-29
     */
    public function saveMessage($args)
    {
        $data = [];

        // 发送者
        if (!empty($args['senderID'])) {
            $data['senderID'] = (int)$args['senderID'];
        } else {
            if (YII_DEBUG) {
                Yii::$app->end('senderID empty');
            }
            return false;
        }

        // 发送者头像
        if (!empty($args['portrait'])) {
            $data['portrait'] = $args['portrait'];
        }

        // 接受者
        if (!empty($args['receiverID'])) {
            $data['receiverID'] = (int)$args['receiverID'];
        } else {
            if (YII_DEBUG) {
                Yii::$app->end('receiverID empty');
            }
            return false;
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

        // 类型
        if (!empty($args['typeID']) &&
            in_array($args['typeID'], array(Conf::MSG_SYSTEM, Conf::MSG_ADMIN, Conf::MSG_HANDLE))
        ) {
            $data['typeID'] = (int)$args['typeID'];
        } else {
            if (YII_DEBUG) {
                Yii::$app->end('typeID error');
            }
            return false;
        }

        // 消息标题
        if (!empty($args['title'])) {
            $data['title'] = $args['title'];
        } else {
            if (YII_DEBUG) {
                Yii::$app->end('title error');
            }
            return false;
        }

        // 消息内容
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

        $data['isRead'] = 0;
        $data['date'] = new UTCDateTime(time() * 1000);

        /** @var \yii\mongodb\Connection $mongo */
        $mongo = Yii::$app->mongodb;
        $collection = $mongo->getCollection(Conf::M_MSG_NOTICE);

        return $collection->insert($data) ? true : false;
    }

    /**
     * 消息内容
     * @param array $args
     * @return array
     * @since 2018-01-29
     */
    public function getMessages($args = [])
    {
        $data = [];

        foreach ($this->findMessage($args)->all() as $row) {
            $temp = [];
            $temp['url'] = $row['url'];
            $temp['typeID'] = $row['typeID'];
            $temp['senderID'] = $row['senderID'];
            $temp['title'] = Html::encode($row['title']);
            $temp['content'] = Html::encode($row['content']);
            $temp['portrait'] = $row['portrait'];

            $tz = new \DateTimeZone('PRC');
            $date = $row['date']->toDateTime()->setTimezone($tz)->format('Y-m-d H:i:s');
            $temp['date'] = $date;
            $data[] = $temp;
        }

        return $data;
    }

    /**
     * 消息总数
     * @param array $args
     * @return int
     * @since 2018-01-29
     */
    public function countMessages($args = [])
    {
        return $this->findMessage($args)->count();
    }

    /**
     * @param $args
     * @return Query
     */
    protected function findMessage($args)
    {
        $obj = (new Query())->from(Conf::M_MSG_NOTICE);

        $condition = [];
        if (!empty($args['begin'])) {
            $condition['date']['$gt'] = new UTCDateTime($args['begin'] * 1000);
        }
        if (!empty($args['end'])) {
            $condition['date']['$lt'] = new UTCDateTime($args['end'] * 1000);
        }
        if (is_numeric($args['typeID'])) {
            $condition['typeID'] = (int)$args['typeID'];
        }
        if (!empty($args['senderID'])) {
            $condition['senderID'] = (int)$args['senderID'];
        }
        if (!empty($args['receiverID'])) {
            $condition['receiverID'] = (int)$args['receiverID'];
        }
        if (is_numeric($args['isRead'])) {
            $condition['isRead'] = (int)$args['isRead'];
        }
        if (!empty($args['limit'])) {
            $obj->limit((int)$args['limit']);
        }
        if (!empty($args['offset'])) {
            $obj->offset((int)$args['offset']);
        }

        return $obj->where($condition)->orderBy(['date' => SORT_DESC]);
    }

    /**
     * 即时推送
     * @param string $action 动作，dynamic表示动态，message表示消息
     * @param array $args 具体参数参考文档
     * @since 2018-01-30
     */
    public function push($action, array $args)
    {
        $args = array_merge($args, [
            'action' => $action,
            'token' => $this->encrypt(serialize($args))
        ]);
        HttpClient::request(PUSH_MSG_REQUEST_URL, 'post', $args);
    }

    public function encrypt($data)
    {
        Yii::$app->security->encryptByKey($data, PUSH_MSG_SECRET);
    }

    public function decrypt($str)
    {
        Yii::$app->security->decryptByKey($str, PUSH_MSG_SECRET);
    }
}