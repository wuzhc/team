<?php

namespace common\services;


use common\config\Conf;
use common\models\Task;
use common\models\User;
use Yii;
use yii\mongodb\Query;
use yii\web\Controller;

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
     * @param $args
     * @return bool
     */
    public function saveHandleLog($args)
    {
        $data = [];

        if (!empty($args['operator'])) {
            $data['operator'] = (int)$args['operator'];
        } else {
            if (YII_DEBUG) {
                Yii::$app->end('operator empty');
            }
            return false;
        }

        if (!empty($args['accept'])) {
            $data['accept'] = (int)$args['accept'];
        }

        if (!empty($args['action']) && in_array($args['action'], array_keys(Yii::$app->params['handleAction']))) {
            $data['action'] = $args['action'];
        } else {
            if (YII_DEBUG) {
                Yii::$app->end('action empty or error');
            }
            return false;
        }

        if (!empty($args['target'])) {
            $data['target'] = (int)$args['target'];
        } else {
            if (YII_DEBUG) {
                Yii::$app->end('target empty');
            }
            return false;
        }

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

        $data['date'] = new \MongoDate(time());

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
            $condition['date']['$gt'] = new \MongoDate($args['begin']);
        }
        if (!empty($args['end'])) {
            $condition['date']['$lt'] = new \MongoDate($args['end']);
        }
        if (!empty($args['target'])) {
            $condition['target'] = (int)$args['target'];
        }
        if (!empty($args['targetType'])) {
            $condition['targetType'] = (int)$args['targetType'];
        }
        if (!empty($args['operator'])) {
            $condition['operator'] = (int)$args['operator'];
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
        $userMap = [];
        $hasInDay = [];
        foreach ($rows as $row) {
            $temp = [];

            if (isset($userMap[$row['operator']])) {
                $operator = $userMap[$row['operator']]['name'];
                $portrait = $userMap[$row['operator']]['portrait'];
            } else {
                $user = User::findOne(['id' => $row['operator']]);
                $operator = UserService::factory()->getUserName($user);
                $portrait = UserService::factory()->getUserPortrait($user);
                $userMap[$row['operator']]['name'] = $operator;
                $userMap[$row['operator']]['portrait'] = $portrait;
            }

            if (isset($args['accept'])) {
                if (isset($userMap[$row['accept']])) {
                    $accept = $userMap[$row['accept']];
                } else {
                    if ($accept = UserService::factory()->getUserName($row['accept'])) {
                        $userMap[$row['accept']] = $accept;
                    }
                }
                $temp['accept'] = $accept;
            }

            $day = date('Y-m-d', $row['date']['sec']);
            if (!in_array($day, $hasInDay)) {
                $temp['day'] = $day;
                $hasInDay[] = $day;
            }

            $temp['operator'] = $operator;
            $temp['portrait'] = $portrait;
            $temp['action'] = Yii::$app->params['handleAction'][$row['action']];
            $temp['target'] = $this->_targetName($row['target'], $row['targetType']);
            $temp['type'] = Yii::$app->params['handleTargetType'][$row['targetType']];
            $temp['date'] = date('Y-m-d H:i:s', $row['date']['sec']);

            $data[] = $temp;
        }

        return $data;
    }

    /**
     * @param $target
     * @param $targetType
     * @return string
     */
    private function _targetName($target, $targetType)
    {
        switch ($targetType) {
            case Conf::TARGET_TASK:
                return Task::findOne(['id' => $target])->fdName;
            default:
                break;
        }
    }
}