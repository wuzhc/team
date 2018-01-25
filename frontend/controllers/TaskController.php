<?php

namespace frontend\controllers;


use common\config\Conf;
use common\models\Project;
use common\models\Task;
use common\models\TaskCategory;
use common\services\ProjectService;
use common\services\TaskService;
use common\services\UserService;
use common\utils\ResponseUtil;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\StringHelper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class TaskController extends BaseController
{
    public $layout = 'main-member';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'         => true,
                        'matchCallback' => function () {
                            // 登录检测
                            if (Yii::$app->user->isGuest) {
                                return false;
                            }
                            $projectID = Yii::$app->request->get('projectID');
                            if (!$projectID) {
                                return false;
                            }
                            if (!(Project::findOne(['id' => $projectID, 'fdStatus' => Conf::ENABLE]))) {
                                throw new NotFoundHttpException('数据不存在');
                            }
                            // 项目访问权限检测
                            if (!ProjectService::factory()->checkUserAccessProject(Yii::$app->user->id, $projectID)) {
                                return false;
                            }
                            return true;
                        }
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * 任务列表
     * @return string
     * @since 2018-01-23
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'projectID' => Yii::$app->request->get('projectID')
        ]);
    }

    /**
     * @param $projectID
     * @since 2018-01-24
     */
    public function actionList($projectID)
    {
        if (Yii::$app->request->isAjax) {
            $params = Yii::$app->request->get();
            $progress = isset($params['progress']) ? $params['progress'] : null;
            $categoryID = !empty($params['categoryID']) ? $params['categoryID'] : null;
            $userID = isset($params['me']) && $params['me'] == 1 ? Yii::$app->user->id : null;
            $args = [
                'userID'     => $userID,
                'progress'   => $progress,
                'categoryID' => $categoryID,
            ];

            $total = null;
            if (!empty($params['totalInit'])) {
                $total = TaskService::factory()->countTasks($projectID, $args);
            }

            $args['limit'] = !empty($params['pageSize']) ? $params['pageSize'] : 10;
            $args['offset'] = !empty($params['page']) ? ($params['page'] - 1) * $args['limit'] : 0;
            $args['order'] = ['id' => SORT_DESC];

            $list = [];
            $tasks = TaskService::factory()->getTasks($projectID, $args);

            /** @var Task $task */
            foreach ((array)$tasks as $task) {
                $temp = [];
                $temp['id'] = $task->id;
                $temp['originName'] = $task->fdName;
                $temp['name'] = StringHelper::truncate($task->fdName, 28);
                $temp['create'] = $task->fdCreate;
                $temp['update'] = $task->fdUpdate;
                $temp['progress'] = $task->fdProgress;
                $temp['categoryID'] = $task->fdTaskCategoryID;
                $temp['creator'] = UserService::factory()->getUserName($task->fdCreatorID);
                $temp['level'] = Yii::$app->params['taskLevel'][$task->fdLevel];
                $list[] = $temp;
            }

            ResponseUtil::jsonCORS([
                'data' => [
                    'total' => $total,
                    'list'  => $list,
                    'info'  => $this->_getTaskCategory($categoryID)
                ]
            ]);
        }
    }

    /**
     * 创建任务清单
     * @param $projectID
     * @since 2018-01-23
     */
    public function actionCreateTaskCategory($projectID)
    {
        if ($name = Yii::$app->request->get('name') && Yii::$app->request->isAjax) {
            $res = TaskService::factory()->saveTaskCategory($projectID, $name);
            ResponseUtil::jsonCORS(['status' => (int)$res]);
        }
    }

    /**
     * 新建任务
     * @param $projectID
     * @param $categoryID
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionCreate($projectID, $categoryID)
    {
        if (!Yii::$app->user->can('createTask')) {
            throw new ForbiddenHttpException('禁止操作');
        }

        if ($data = Yii::$app->request->post()) {
            if (empty($data['name'])) {
                ResponseUtil::jsonCORS(['status' => 0, 'msg' => '参数错误']);
            }

            $args = [
                'name'       => $data['name'],
                'creatorID'  => Yii::$app->user->id,
                'companyID'  => $this->companyID,
                'level'      => $data['level'],
                'categoryID' => $categoryID,
                'projectID'  => $projectID,
                'content'    => $data['content']
            ];

            list($status, $msg) = TaskService::factory()->save($args) ? [1, '创建成功'] : [0, '创建失败'];
            ResponseUtil::jsonCORS([
                'status' => $status,
                'msg'    => $msg
            ]);
        } else {
            return $this->render('create', [
                'categoryID' => $categoryID,
                'projectID'  => $projectID,
                'category'   => $this->_getTaskCategory($categoryID)
            ]);
        }
    }

    /**
     * 编辑任务
     * @param $projectID
     * @param $categoryID
     * @param $taskID
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionUpdate($projectID, $categoryID, $taskID)
    {
        if (!Yii::$app->user->can('editTask')) {
            throw new ForbiddenHttpException('对不起，你无权编辑任务');
        }

        $this->_checkTaskAccess($taskID, $projectID, $categoryID);

        if ($data = Yii::$app->request->post()) {
            if (empty($data['name'])) {
                ResponseUtil::jsonCORS(['status' => 0, 'msg' => '参数错误']);
            }

            $args = [
                'name'    => $data['name'],
                'level'   => $data['level'],
                'content' => $data['content']
            ];

            list($status, $msg) = TaskService::factory()->update($taskID, $args) ? [1, '编辑成功'] : [0, '编辑失败'];
            ResponseUtil::jsonCORS([
                'status' => $status,
                'msg'    => $msg
            ]);
        } else {
            $task = Task::find()->where(['id' => $taskID])->one();
            return $this->render('update', [
                'task'       => $task,
                'projectID'  => $projectID,
                'categoryID' => $categoryID,
            ]);
        }
    }

    /**
     * 任务详情
     * @param $projectID
     * @param $taskID
     * @param $categoryID
     * @return string
     */
    public function actionView($projectID, $taskID, $categoryID)
    {
        $task = Task::find()->where(['id' => $taskID])->one();
        return $this->render('view', [
            'task'       => $task,
            'projectID'  => $projectID,
            'categoryID' => $categoryID,
        ]);
    }

    /**
     * 删除任务
     * @param int $taskID
     * @since 2018-01-22
     */
    public function del($taskID)
    {
        if (Yii::$app->user->can('delTask')) {
            ResponseUtil::jsonCORS(['status' => Conf::FAILED, 'msg' => '禁止操作']);
        }

        if ($task = Task::findOne(['id' => $taskID])) {
            ResponseUtil::jsonCORS(['status' => Conf::FAILED, 'msg' => '数据不存在']);
        }

        if ($task->fdCreatorID != Yii::$app->user->id) {
            ResponseUtil::jsonCORS(['status' => Conf::FAILED, 'msg' => '禁止操作']);
        }

        if ($task->fdStatus == Conf::DISABLE) {
            ResponseUtil::jsonCORS(['status' => Conf::SUCCESS]);
        }

        $res = Task::updateAll(['fdStatus' => Conf::DISABLE], ['id' => $taskID]);
        ResponseUtil::jsonCORS(['status' => $res ? Conf::SUCCESS : Conf::FAILED]);
    }

    /**
     * 任务统计数据（完成数+总数）
     * @param int $projectID 项目ID
     * @since 2018-01-23
     */
    public function actionStatTasks($projectID)
    {
        $tasks = [];

        $categories = TaskService::factory()->getTasks($projectID, [
            'status'  => Conf::ENABLE,
            'group'   => ['fdTaskCategoryID'],
            'asArray' => true,
            'select'  => ['count(id) as total', 'fdTaskCategoryID as cid']
        ]);

        $completeCategories = TaskService::factory()->getTasks($projectID, [
            'status'   => Conf::ENABLE,
            'group'    => ['fdTaskCategoryID'],
            'asArray'  => true,
            'progress' => Conf::TASK_FINISH,
            'select'   => ['count(id) as completeTotal', 'fdTaskCategoryID as cid']
        ]);

        $map = [];
        foreach ($completeCategories as $category) {
            $map[$category['cid']] = $category['completeTotal'];
        }

        foreach ($categories as $category) {
            $temp = [];
            $temp['cid'] = $category['cid'];
            $temp['allTasks'] = $category['total'];
            $temp['completeTasks'] = isset($map[$category['cid']]) ? $map[$category['cid']] : 0;
            $tasks[] = $temp;
        }

        ResponseUtil::jsonCORS([
            'tasks' => $tasks
        ]);
    }

    /**
     * 完成任务
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @since 2018-01-24
     */
    public function actionFinish($projectID)
    {
        if (Yii::$app->request->isAjax) {
            $taskID = Yii::$app->request->get('taskID');
            if (!$taskID) {
                throw new ForbiddenHttpException('参数错误');
            }

            $task = $this->_checkTaskAccess($taskID, $projectID);

            if ($task->fdProgress == Conf::TASK_FINISH) {
                $attribute['fdProgress'] = Conf::TASK_STOP;
            } else {
                $attribute['fdProgress'] = Conf::TASK_FINISH;
            }

            // 更新任务进度
            Task::updateAll($attribute, ['id' => $task->id]);

            // 返回进度
            $task = Task::findOne(['id' => $taskID, 'fdStatus' => Conf::ENABLE]);
            switch ($task->fdProgress) {
                case Conf::TASK_STOP:
                    $action = 'begin';
                    break;
                case Conf::TASK_FINISH:
                    $action = 'finish';
                    break;
                default:
                    throw new NotFoundHttpException('数据不存在');
            }

            ResponseUtil::jsonCORS(['action' => $action]);
        }
    }

    /**
     * 处理任务
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @since 2018-01-24
     */
    public function actionHandle($projectID)
    {
        if (Yii::$app->request->isAjax) {
            $action = Yii::$app->request->get('action');
            $taskID = Yii::$app->request->get('taskID');
            if (!$action || !$taskID) {
                throw new ForbiddenHttpException('参数错误');
            }

            $task = $this->_checkTaskAccess($taskID, $projectID);

            if ($task->fdProgress == Conf::TASK_STOP && $action === 'begin') {
                $attribute['fdProgress'] = Conf::TASK_BEGIN;
            } elseif ($task->fdProgress == Conf::TASK_BEGIN && $action === 'stop') {
                $attribute['fdProgress'] = Conf::TASK_STOP;
            } else {
                throw new ForbiddenHttpException('禁止操作');
            }

            // 更新任务进度
            Task::updateAll($attribute, ['id' => $task->id]);

            // 返回进度
            $task = Task::findOne(['id' => $taskID, 'fdStatus' => Conf::ENABLE]);
            switch ($task->fdProgress) {
                case Conf::TASK_STOP:
                    $action = 'stop';
                    break;
                case Conf::TASK_BEGIN:
                    $action = 'begin';
                    break;
                default:
                    throw new NotFoundHttpException('数据不存在');
            }

            ResponseUtil::jsonCORS(['action' => $action]);
        }
    }

    /**
     * @param int $taskID
     * @param int $projectID
     * @return Task
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    private function _checkTaskAccess($taskID, $projectID)
    {
        $task = Task::findOne([
            'id'          => $taskID,
            'fdStatus'    => Conf::ENABLE,
            'fdProjectID' => $projectID,
        ]);
        if (!$task) {
            throw new NotFoundHttpException('任务不存在或已删除');
        }
        if ($task->fdCreatorID != Yii::$app->user->id) {
            throw new ForbiddenHttpException('禁止操作');
        }

        return $task;
    }

    /**
     * 获取任务清单信息
     * @param $categoryID
     * @return array
     */
    private function _getTaskCategory($categoryID)
    {
        $data = [];

        if ($categoryID) {
            $category = TaskCategory::findOne(['id' => $categoryID]);
            $data['id'] = $category->id;
            $data['name'] = $category->fdName;
        }

        return $data;
    }
}