<?php

namespace common\services;

use common\config\Conf;
use common\models\Project;
use Yii;


/**
 * 任务服务类
 * Class TaskService
 * @package common\services
 * @author wuzhc <wuzhc2016@163.com>
 * @since 2018-01-19
 */
class TaskService extends AbstractService
{
    /**
     * Returns the static model.
     * @param string $className Service class name.
     * @return TaskService the static model class
     */
    public static function factory($className = __CLASS__)
    {
        return parent::factory($className);
    }

    /**
     * 根据companyID获取项目下任务统计
     * @param int $companyID
     * @return array
     * <pre>
     * array(
     *  array(
     *      'id' => 1,
     *      'name' => '项目名称',
     *      'tasks' => '项目任务数',
     *      'untasks' => '项目未完成任务数'
     *  )
     * )
     * </pre>
     */
    public function getProjectStatByCompanyID($companyID)
    {
        $data = [];

        $projects = Project::find()
            ->where(['fdCompanyID' => $companyID, 'fdStatus' => Conf::ENABLE])
            ->orderBy(['id' => SORT_DESC])
            ->all();

        if ($projects) {
            /** @var yii\redis\Connection $redis */
            $redis = Yii::$app->redis;

            $redis->multi();
            foreach ($projects as $proj) {
                $redis->hmget(Conf::R_COUNTER_PROJ_TASK_NUM . $proj->id, 'allTasks', 'completeTasks');
            }
            $res = $redis->exec();

            /** @var Project $proj */
            foreach ($projects as $k => $proj) {
                $temp = [];
                $temp['id'] = $proj->id;
                $temp['name'] = $proj->fdName;
                $temp['allTasks'] = (int)$res[$k][0];
                $temp['completeTasks'] = (int)$res[$k][1];
                $data[] = $temp;
            }
        }

        return $data;
    }

    public function incre($projectID)
    {
        /** @var yii\redis\Connection $redis */
        $redis = Yii::$app->redis;
    }
}