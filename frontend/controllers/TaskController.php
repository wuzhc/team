<?php

namespace frontend\controllers;


use common\config\Conf;
use common\models\Project;
use common\models\Task;
use common\models\TaskCategory;
use common\services\LogService;
use common\services\ProjectService;
use common\services\TaskService;
use common\services\UserService;
use common\utils\ResponseUtil;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\StringHelper;
use yii\web\BadRequestHttpException;
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
                            return true;
                        }
                    ],
                    [
                        'actions'       => ['index', 'list', 'create', 'create-task-category', 'stat-tasks'],
                        'allow'         => true,
                        'matchCallback' => function () {
                            // 项目访问权限检测
                            $projectID = Yii::$app->request->get('projectID');
                            if (empty($projectID)) {
                                throw new BadRequestHttpException('参数错误');
                            }
                            if (!ProjectService::factory()->checkUserAccessProject(Yii::$app->user->id, $projectID)) {
                                throw new ForbiddenHttpException('你还不是我们项目的成员，联系一下管理员试试吧');
                            }
                            return true;
                        }
                    ]
                ],
            ]
        ];
    }

    /**
     * 任务列表
     * @param int $projectID
     * @param int $isMe 是否为我自己的任务，0否1是
     * @param int $categoryID 分类ID
     * @return string
     * @throws ForbiddenHttpException
     * @since 2018-01-23
     */
    public function actionIndex($projectID, $isMe = 0, $categoryID = 0)
    {
        return $this->render('index', [
            'isMe'       => $isMe,
            'projectID'  => $projectID,
            'categoryID' => $categoryID,
            'categories' => TaskService::factory()->getTaskCategories($projectID)
        ]);
    }

    /**
     * 任务列表
     * @param $projectID
     * @throws ForbiddenHttpException
     * @since 2018-01-24
     */
    public function actionList($projectID)
    {
        if (Yii::$app->request->isAjax) {
            $list = [];

            $params = Yii::$app->request->get();
            $progress = Yii::$app->request->get('progress');
            $categoryID = Yii::$app->request->get('categoryID');
            $isMe = Yii::$app->request->get('isMe', 0);
            $page = Yii::$app->request->get('page', 1);
            $pageSize = Yii::$app->request->get('pageSize', 15);

            $args = [
                'status'     => Conf::ENABLE,
                'progress'   => $progress,
                'creatorID'  => $isMe == 1 ? Yii::$app->user->id : null,
                'categoryID' => !empty($categoryID) ? $categoryID : null,
            ];

            $total = null;
            if (!empty($params['totalInit'])) {
                $total = TaskService::factory()->countProjectTasks($projectID, $args);
            }

            $args['limit'] = $pageSize;
            $args['offset'] = $pageSize * ($page - 1);
            $args['order'] = ['id' => SORT_DESC];
            $tasks = TaskService::factory()->getProjectTasks($projectID, $args);

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
                'total' => $total,
                'list'  => $list,
                'info'  => $this->_getTaskCategory($categoryID)
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
            ResponseUtil::jsonCORS(['status' => $res ? Conf::SUCCESS : Conf::FAILED]);
        }
    }

    /**
     * 新建任务
     * @param $projectID
     * @param $categoryID
     * @return string
     * @throws ForbiddenHttpException
     * @since 2018-01-26
     */
    public function actionCreate($projectID, $categoryID)
    {
        if (!Yii::$app->user->can('createTask')) {
            throw new ForbiddenHttpException('你没有创建任务的权限，练习下管理员吧');
        }

        if ($data = Yii::$app->request->post()) {
            if (empty($data['name'])) {
                ResponseUtil::jsonCORS(['status' => Conf::FAILED, 'msg' => '任务标题不能为空']);
            }

            $taskID = TaskService::factory()->save([
                'name'       => $data['name'],
                'creatorID'  => Yii::$app->user->id,
                'companyID'  => $this->companyID,
                'level'      => $data['level'],
                'categoryID' => $categoryID,
                'projectID'  => $projectID,
                'content'    => $data['content']
            ]);

            if ($taskID) {
                LogService::factory()->saveHandleLog([
                    'target'     => $taskID,
                    'action'     => Conf::ACTION_CREATE,
                    'operator'   => Yii::$app->user->id,
                    'targetType' => Conf::TARGET_TASK,
                ]);
                ResponseUtil::jsonCORS([
                    'status' => Conf::SUCCESS,
                    'msg'    => '创建成功'
                ]);
            } else {
                ResponseUtil::jsonCORS([
                    'status' => Conf::FAILED,
                    'msg'    => '创建失败'
                ]);
            }
        } else {
            return $this->render('create', [
                'projectID' => $projectID,
                'category'  => TaskCategory::findOne(['id' => $categoryID]),
            ]);
        }
    }

    /**
     * 编辑任务
     * @param int $taskID 任务ID
     * @return string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @since 2018-01-26
     */
    public function actionUpdate($taskID)
    {
        $task = Task::findOne(['id' => $taskID, 'fdStatus' => Conf::ENABLE]);
        if (!$task) {
            throw new NotFoundHttpException('任务不存在或已删除');
        }

        // 项目检测
        if (!ProjectService::factory()->checkUserAccessProject(Yii::$app->user->id, $task->fdProjectID)) {
            throw new ForbiddenHttpException('你还不是我们项目的成员，联系一下管理员试试吧');
        }

        // rbac检测
        if (!Yii::$app->user->can('editTask')) {
            throw new ForbiddenHttpException('你没有编辑任务的权限，联系下管理员吧');
        }

        if (($data = Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            if (empty($data['name'])) {
                ResponseUtil::jsonCORS(['status' => Conf::FAILED, 'msg' => '参数错误']);
            }

            $res = TaskService::factory()->update($taskID, [
                'name'    => $data['name'],
                'level'   => $data['level'],
                'content' => $data['content']
            ]);
            if ($res) {
                LogService::factory()->saveHandleLog([
                    'target'     => $taskID,
                    'action'     => Conf::ACTION_EDIT,
                    'operator'   => Yii::$app->user->id,
                    'targetType' => Conf::TARGET_TASK,
                ]);
            }

            list($status, $msg) = $res ? [Conf::SUCCESS, '编辑成功'] : [Conf::FAILED, '编辑失败'];
            ResponseUtil::jsonCORS(['status' => $status, 'msg' => $msg]);
        } else {
            return $this->render('update', [
                'task' => $task,
            ]);
        }
    }

    /**
     * 任务详情
     * @param int $taskID
     * @return string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @since 2018-01-26
     */
    public function actionView($taskID)
    {
        $task = Task::findOne(['id' => $taskID, 'fdStatus' => Conf::ENABLE]);
        if (!$task) {
            throw new NotFoundHttpException('任务不存在或已删除');
        }

        if (!ProjectService::factory()->checkUserAccessProject(Yii::$app->user->id, $task->fdProjectID)) {
            throw new ForbiddenHttpException('你还不是我们项目的成员，联系一下管理员试试吧');
        }

        $logs = LogService::factory()->getHandleLogs([
            'target'     => (int)$task->id,
            'targetType' => Conf::TARGET_TASK
        ]);

//        print_r($logs);exit;

        $members = ProjectService::factory()->getHasJoinProjectMembers($task->fdProjectID);

        return $this->render('view', [
            'task'    => $task,
            'logs'    => $logs,
            'members' => $members,
        ]);
    }

    /**
     * 删除任务
     * @param $taskID
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @since 2018-01-22
     */
    public function actionDelete($taskID)
    {
        if (!Yii::$app->user->can('delTask')) {
            throw new ForbiddenHttpException('你没有删除任务的权限，联系下管理员吧');
        }

        $task = Task::findOne(['id' => $taskID, 'fdStatus' => Conf::ENABLE]);
        if (!$task) {
            throw new NotFoundHttpException('任务不存在或已删除');
        }

        if (!ProjectService::factory()->checkUserAccessProject(Yii::$app->user->id, $task->fdProjectID)) {
            throw new ForbiddenHttpException('你还不是我们项目的成员，联系一下管理员试试吧');
        }

        if ($task->fdCreatorID != Yii::$app->user->id) {
            throw new ForbiddenHttpException('这不是你的任务，你不能删除啊');
        }

        if ($task->fdStatus == Conf::DISABLE) {
            ResponseUtil::jsonCORS(['status' => Conf::SUCCESS]);
        }

        $res = Task::updateAll(['fdStatus' => Conf::DISABLE], ['id' => $taskID]);
        if ($res) {
            LogService::factory()->saveHandleLog([
                'target'     => $task->id,
                'operator'   => Yii::$app->user->id,
                'action'     => Conf::ACTION_DEL,
                'targetType' => Conf::TARGET_TASK,
            ]);
            ResponseUtil::jsonCORS(['status' => Conf::SUCCESS]);
        }

        ResponseUtil::jsonCORS(['status' => Conf::FAILED]);
    }

    /**
     * 指派任务
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @since 2018-01-26
     */
    public function actionAssign()
    {
        if (Yii::$app->request->isAjax && ($data = Yii::$app->request->post())) {
            $taskID = $data['taskID'];
            $acceptor = $data['acceptor'];

            if (!$taskID || !$acceptor) {
                throw new BadRequestHttpException('参数错误');
            }

            $task = Task::findOne(['id' => $taskID, 'fdStatus' => Conf::ENABLE]);
            if (!$task) {
                throw new NotFoundHttpException('任务不存在或已删除');
            }

            // 检查被指派者的权限
            if (!ProjectService::factory()->checkUserAccessProject($acceptor, $task->fdProjectID)) {
                throw new ForbiddenHttpException('禁止操作');
            }

            $res = Task::updateAll([
                'fdCreatorID' => $acceptor,
                'fdProgress'  => Conf::TASK_STOP
            ], ['id' => $taskID]);

            if ($res) {
                LogService::factory()->saveHandleLog([
                    'operator'   => Yii::$app->user->id,
                    'acceptor'   => $acceptor,
                    'target'     => $task->id,
                    'targetType' => Conf::TARGET_TASK,
                    'action'     => Conf::ACTION_ASSIGN,
                ]);
                ResponseUtil::jsonCORS(['status' => Conf::SUCCESS]);
            } else {
                ResponseUtil::jsonCORS(['status' => Conf::FAILED]);
            }
        }
    }

    /**
     * 任务统计数据（完成数+总数）
     * @param int $projectID 项目ID
     * @param int $isMe
     * @since 2018-01-23
     */
    public function actionStatTasks($projectID, $isMe = 0)
    {
        $tasks = [];

        $categories = TaskService::factory()->getProjectTasks($projectID, [
            'status'    => Conf::ENABLE,
            'group'     => ['fdTaskCategoryID'],
            'asArray'   => true,
            'creatorID' => $isMe == 1 ? Yii::$app->user->id : null,
            'select'    => ['count(id) as total', 'fdTaskCategoryID as cid']
        ]);

        $completeCategories = TaskService::factory()->getProjectTasks($projectID, [
            'status'    => Conf::ENABLE,
            'group'     => ['fdTaskCategoryID'],
            'asArray'   => true,
            'progress'  => Conf::TASK_FINISH,
            'creatorID' => $isMe == 1 ? Yii::$app->user->id : null,
            'select'    => ['count(id) as completeTotal', 'fdTaskCategoryID as cid']
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
     * @param int $taskID
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @since 2018-01-24
     */
    public function actionFinish($taskID)
    {
        if (Yii::$app->request->isAjax) {
            $task = Task::findOne(['id' => $taskID, 'fdStatus' => Conf::ENABLE]);
            if (!$task) {
                throw new NotFoundHttpException('任务不存在或已删除');
            }

            if (!ProjectService::factory()->checkUserAccessProject(Yii::$app->user->id, $task->fdProjectID)) {
                throw new ForbiddenHttpException('你还不是我们项目的成员，联系一下管理员试试吧');
            }

            if ($task->fdProgress == Conf::TASK_FINISH) {
                $attribute['fdProgress'] = Conf::TASK_STOP;
            } else {
                $attribute['fdProgress'] = Conf::TASK_FINISH;
            }
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
    public function actionHandle()
    {
        if (Yii::$app->request->isAjax) {
            $action = Yii::$app->request->get('action');
            $taskID = Yii::$app->request->get('taskID');
            if (!$action || !$taskID) {
                throw new BadRequestHttpException('参数错误');
            }

            $task = Task::findOne(['id' => $taskID, 'fdStatus' => Conf::ENABLE]);
            if (!$task) {
                throw new NotFoundHttpException('任务不存在或已删除');
            }

            if (!ProjectService::factory()->checkUserAccessProject(Yii::$app->user->id, $task->fdProjectID)) {
                throw new ForbiddenHttpException('你还不是我们项目的成员，联系一下管理员试试吧');
            }

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