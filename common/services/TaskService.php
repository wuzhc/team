<?php

namespace common\services;

use common\config\Conf;
use common\models\Project;
use common\models\Task;
use common\models\TaskCategory;
use common\models\TaskContent;
use common\models\TaskLabel;
use Yii;
use yii\db\Exception;


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
     * 新建任务
     * @param $args
     * @return int
     * @throws Exception
     * @since 2018-01-25
     */
    public function save($args)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $task = new Task();
            $task->fdName = $args['name'];
            $task->fdCreatorID = $args['creatorID'];
            $task->fdCompanyID = $args['companyID'];
            $task->fdLevel = $args['level'];
            $task->fdProgress = Conf::TASK_STOP;
            $task->fdTaskCategoryID = $args['categoryID'];
            $task->fdProjectID = $args['projectID'];
            $task->fdCreate = date('Y-m-d H:i:s');
            $task->fdUpdate = date('Y-m-d H:i:s');
            $task->fdStatus = Conf::ENABLE;
            $res = $task->save();

            if ($res && $task->id) {
                $taskContent = new TaskContent();
                $taskContent->fdTaskID = $task->id;
                $taskContent->fdContent = $args['content'];
                $taskContent->save();
            } else {
                var_dump($task->getErrors());
                exit;
            }

            $transaction->commit();
            return $res ? (int)$task->id : 0;
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * 编辑任务
     * @param int $taskID
     * @param array $args
     * @return bool
     * @throws Exception
     * @since 2018-01-25
     */
    public function update($taskID, array $args)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $task = Task::findOne(['id' => $taskID]);
            $task->fdName = $args['name'];
            $task->fdLevel = $args['level'];
            $task->fdUpdate = date('Y-m-d H:i:s');
            $res = $task->update();

            if ($res && $task->id) {
                $taskContent = TaskContent::findOne(['fdTaskID' => $taskID]);
                $taskContent->fdContent = $args['content'];
                $taskContent->update();
            } else {
                var_dump($task->getErrors());
                exit;
            }

            $transaction->commit();
            return $res;
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
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

    /**
     * 创建任务分类
     * @param int $projectID
     * @param string $name
     * @return bool
     * @since 2018-01-22
     */
    public function saveTaskCategory($projectID, $name)
    {
        if (!$projectID || !$name) {
            return false;
        }

        $taskCategory = new TaskCategory();
        $taskCategory->fdName = trim(strip_tags($name));
        $taskCategory->fdProjectID = $projectID;

        $res = $taskCategory->save();
        if (!$res && YII_DEBUG) {
            var_dump($taskCategory->getErrors());
            exit;
        }

        return $res;
    }

    /**
     * 获取所有任务分类
     * @param $projectID
     * @return array|\yii\db\ActiveRecord[]
     * @since 2018-01-22
     */
    public function getTaskCategories($projectID)
    {
        return TaskCategory::find()->where(['fdProjectID' => $projectID, 'fdStatus' => Conf::ENABLE])->all();
    }

    /**
     * 获取所有任务标签
     * @param $projectID
     * @return array|\yii\db\ActiveRecord[]
     * @since 2018-01-22
     */
    public function getTaskLabels($projectID)
    {
        return TaskLabel::find()->where(['fdProjectID' => $projectID, 'fdStatus' => Conf::ENABLE])->all();
    }

    /**
     * 获取任务
     * @param int $projectID
     * @param array $args
     * @return array|\yii\db\ActiveRecord[]
     * @since 2018-01-23
     */
    public function getTasks($projectID, array $args)
    {
        if (empty($projectID)) {
            if (YII_DEBUG) {
                Yii::$app->end('getTask(): ProjectID is empty');
            }
            return [];
        }

        $args['projectID'] = $projectID;
        return $this->findTaskCriteria($args)->all();
    }

    /**
     * 任务总数
     * @param $projectID
     * @param array $args
     * @return int
     * @since 2018-01-23
     */
    public function countTasks($projectID, array $args)
    {
        if (empty($projectID)) {
            return 0;
        }

        $args['projectID'] = $projectID;
        return (int)$this->findTaskCriteria($args)->count();
    }

    /**
     * @param array $args
     * @return \yii\db\ActiveQuery
     */
    protected function findTaskCriteria(array $args)
    {
        $task = Task::find();

        if (is_numeric($args['creatorID'])) {
            $task->andWhere(['fdCreatorID' => $args['creatorID']]);
        }
        if (is_numeric($args['companyID'])) {
            $task->andWhere(['fdCompanyID' => $args['companyID']]);
        }
        if (is_numeric($args['projectID'])) {
            $task->andWhere(['fdProjectID' => $args['projectID']]);
        }
        if (is_numeric($args['categoryID'])) {
            $task->andWhere(['fdTaskCategoryID' => $args['categoryID']]);
        }
        if (is_numeric($args['progress'])) {
            $task->andWhere(['fdProgress' => $args['progress']]);
        }
        if (is_numeric($args['status'])) {
            $task->andWhere(['fdStatus' => $args['status']]);
        }
        if (!empty($args['select'])) {
            $task->select($args['select']);
        }
        if (is_array($args['group'])) {
            $task->groupBy($args['group']);
        }
        if (is_array($args['order'])) {
            $task->orderBy($args['order']);
        }
        if (is_numeric($args['limit'])) {
            $task->limit($args['limit']);
        }
        if (is_numeric($args['offset'])) {
            $task->offset($args['offset']);
        }
        if (is_bool($args['asArray'])) {
            $task->asArray($args['asArray']);
        }

        return $task;
    }
}