<?php

namespace frontend\controllers;


use common\config\Conf;
use common\models\Task;
use common\models\TaskCategory;
use common\services\LogService;
use common\services\MsgService;
use common\services\ProjectService;
use common\services\TaskService;
use common\services\UserService;
use common\utils\ResponseUtil;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * 任务控制器
 * Class TaskController
 * @package frontend\controllers
 */
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
                        'actions'       => ['index', 'list', 'create', 'create-task-category', 'stat-tasks'],
                        'allow'         => true,
                        'matchCallback' => function () {
                            // 项目访问权限检测
                            $projectID = Yii::$app->request->get('projectID');
                            if (empty($projectID)) {
                                throw new BadRequestHttpException('参数错误');
                            }
                            if (!ProjectService::factory()->checkUserAccessProject(Yii::$app->user->id, $projectID)) {
                                throw new ForbiddenHttpException(ResponseUtil::$msg[1]);
                            }
                            return true;
                        }
                    ],
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
        $userID = Yii::$app->user->id;

        if (!Yii::$app->user->can('createTask')) {
            throw new ForbiddenHttpException(ResponseUtil::$msg[1]);
        }

        if ($data = Yii::$app->request->post()) {
            if (empty($data['name'])) {
                ResponseUtil::jsonCORS(['status' => Conf::FAILED, 'msg' => '任务标题不能为空']);
            }

            // 保存任务
            $taskID = TaskService::factory()->save([
                'name'       => $data['name'],
                'creatorID'  => $userID,
                'companyID'  => $this->companyID,
                'level'      => $data['level'],
                'categoryID' => $categoryID,
                'projectID'  => $projectID,
                'content'    => $data['content']
            ]);

            if ($taskID) {
                $url = Url::to(['task/view', 'taskID' => $taskID]);
                $portrait = UserService::factory()->getUserPortrait($userID);
                $username = UserService::factory()->getUserName($userID);
                $title = '创建了新任务';
                $content = $data['name'];

                // 保存操作日志
                LogService::factory()->saveHandleLog([
                    'objectID'   => $taskID,
                    'companyID'  => $this->companyID,
                    'operatorID' => $userID,
                    'objectType' => Conf::OBJECT_TASK,
                    'content'    => $content,
                    'url'        => $url,
                    'title'      => $title,
                    'portrait'   => $portrait,
                    'operator'   => $username
                ]);

                // 动态推送
                MsgService::factory()->push('dynamic', [
                    'companyID'  => $this->companyID,
                    'operatorID' => $userID,
                    'operator'   => $username,
                    'portrait'   => $portrait,
                    'title'      => $title,
                    'content'    => $content,
                    'url'        => $url,
                    'date'       => date('Y-m-d H:i:s'),
                ]);

                ResponseUtil::jsonCORS(null, Conf::SUCCESS, '创建成功');
            } else {
                ResponseUtil::jsonCORS(null, Conf::FAILED, '创建失败');
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
        $userID = Yii::$app->user->id;

        $task = Task::findOne(['id' => $taskID, 'fdStatus' => Conf::ENABLE]);
        if (!$task) {
            throw new NotFoundHttpException('任务不存在或已删除');
        }

        // 项目检测
        if (!ProjectService::factory()->checkUserAccessProject(Yii::$app->user->id, $task->fdProjectID)) {
            throw new ForbiddenHttpException(ResponseUtil::$msg[1]);
        }

        // rbac检测
        if (!Yii::$app->user->can('editTask')) {
            throw new ForbiddenHttpException(ResponseUtil::$msg[3]);
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
                $url = Url::to(['task/view', 'taskID' => $taskID]);
                $portrait = UserService::factory()->getUserPortrait($userID);
                $username = UserService::factory()->getUserName($userID);
                $title = '编辑了任务';
                $content = $data['name'];

                // 保存操作日志
                LogService::factory()->saveHandleLog([
                    'objectID'   => $taskID,
                    'companyID'  => $this->companyID,
                    'operatorID' => $userID,
                    'objectType' => Conf::OBJECT_TASK,
                    'content'    => $content,
                    'url'        => $url,
                    'title'      => $title,
                    'portrait'   => $portrait,
                    'operator'   => $username
                ]);

                // 动态推送
                MsgService::factory()->push('dynamic', [
                    'companyID'  => $this->companyID,
                    'operatorID' => $userID,
                    'operator'   => $username,
                    'portrait'   => $portrait,
                    'title'      => $title,
                    'content'    => $content,
                    'url'        => $url,
                    'date'       => date('Y-m-d H:i:s'),
                ]);
            }

            list($status, $msg) = $res ? [Conf::SUCCESS, '编辑成功'] : [Conf::FAILED, '编辑失败'];
            ResponseUtil::jsonCORS(null, $status, $msg);
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
        $userID = Yii::$app->user->id;
        $task = Task::findOne(['id' => $taskID, 'fdStatus' => Conf::ENABLE]);
        if (!$task) {
            throw new NotFoundHttpException('任务不存在或已删除');
        }

        if (!ProjectService::factory()->checkUserAccessProject($userID, $task->fdProjectID)) {
            throw new ForbiddenHttpException(ResponseUtil::$msg[1]);
        }

        $logs = LogService::factory()->getHandleLogs([
            'target'     => (int)$task->id,
            'objectType' => Conf::OBJECT_TASK
        ]);

        // 已加入项目所有成员
        $members = ProjectService::factory()->getHasJoinProjectMembers($task->fdProjectID);

        // 是否有消息，如果有则更新为已读
        $messageID = Yii::$app->request->get('messageID');
        if (!empty($messageID)) {
            if (MsgService::factory()->hasMessage($messageID)) {
                MsgService::factory()->updateRead($messageID);
            }
        }

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
        $userID = Yii::$app->user->id;

        if (!Yii::$app->user->can('delTask')) {
            throw new ForbiddenHttpException(ResponseUtil::$msg[4]);
        }

        $task = Task::findOne(['id' => $taskID, 'fdStatus' => Conf::ENABLE]);
        if (!$task) {
            throw new NotFoundHttpException('任务不存在或已删除');
        }

        if (!ProjectService::factory()->checkUserAccessProject(Yii::$app->user->id, $task->fdProjectID)) {
            throw new ForbiddenHttpException(ResponseUtil::$msg[1]);
        }

        if ($task->fdCreatorID != Yii::$app->user->id) {
            throw new ForbiddenHttpException('这不是你的任务，你不能删除啊');
        }

        if ($task->fdStatus == Conf::DISABLE) {
            ResponseUtil::jsonCORS(['status' => Conf::SUCCESS]);
        }

        $res = Task::updateAll(['fdStatus' => Conf::DISABLE], ['id' => $taskID]);
        if (!$res) {
            ResponseUtil::jsonCORS(null, Conf::FAILED);
        }

        $url = Url::to(['task/view', 'taskID' => $taskID]);
        $portrait = UserService::factory()->getUserPortrait($userID);
        $username = UserService::factory()->getUserName($userID);
        $title = '删除了任务';
        $content = $task->fdName;

        // 保存操作日志
        LogService::factory()->saveHandleLog([
            'objectID'   => $taskID,
            'companyID'  => $this->companyID,
            'operatorID' => $userID,
            'objectType' => Conf::OBJECT_TASK,
            'content'    => $content,
            'url'        => $url,
            'title'      => $title,
            'portrait'   => $portrait,
            'operator'   => $username
        ]);

        // 推送动态
        MsgService::factory()->push('dynamic', [
            'companyID'  => $this->companyID,
            'operatorID' => $userID,
            'operator'   => $username,
            'content'    => $content,
            'url'        => $url,
            'title'      => $title,
            'portrait'   => $portrait,
            'date'       => date('Y-m-d H:i:s'),
        ]);

        ResponseUtil::jsonCORS(null, Conf::SUCCESS);
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
        $userID = Yii::$app->user->id;

        if (Yii::$app->request->isAjax && ($data = Yii::$app->request->post())) {
            $taskID = $data['taskID'];
            $receiverID = $data['acceptor'];
            if (!$taskID || !$receiverID) {
                throw new BadRequestHttpException('参数错误');
            }

            $task = Task::findOne(['id' => $taskID, 'fdStatus' => Conf::ENABLE]);
            if (!$task) {
                throw new NotFoundHttpException('任务不存在或已删除');
            }

            // 检查被指派者的权限
            if (!ProjectService::factory()->checkUserAccessProject($receiverID, $task->fdProjectID)) {
                throw new ForbiddenHttpException('对不起，接受者不是我们项目组的');
            }

            // 更新任务创建者
            $res = Task::updateAll([
                'fdCreatorID' => $receiverID,
                'fdProgress'  => Conf::TASK_STOP
            ], ['id' => $taskID]);

            if (!$res) {
                ResponseUtil::jsonCORS(null, Conf::FAILED);
            }

            $content = $task->fdName;
            $url = Url::to(['task/view', 'taskID' => $taskID]);
            $portrait = UserService::factory()->getUserPortrait($userID);
            $username = UserService::factory()->getUserName($userID);
            $title = '把任务指派给了' . UserService::factory()->getUserName($receiverID);

            // 保存操作日志
            LogService::factory()->saveHandleLog([
                'objectID'   => $taskID,
                'companyID'  => $this->companyID,
                'operatorID' => $userID,
                'receiverID' => $receiverID,
                'objectType' => Conf::OBJECT_TASK,
                'content'    => $content,
                'url'        => $url,
                'title'      => $title,
                'portrait'   => $portrait,
                'operator'   => $username
            ]);

            // 保存消息
            MsgService::factory()->saveMessage([
                'senderID'   => $userID,
                'receiverID' => $receiverID,
                'companyID'  => $this->companyID,
                'title'      => $title,
                'content'    => $content,
                'typeID'     => Conf::MSG_HANDLE,
                'url'        => $url,
                'portrait'   => $portrait
            ]);

            // 消息推送
            MsgService::factory()->push('message', [
                'operatorID' => $userID,
                'operator'   => $username,
                'receiverID' => $receiverID,
                'portrait'   => $portrait,
                'title'      => $title,
                'content'    => $content,
                'url'        => $url,
                'typeID'     => Conf::MSG_HANDLE,
            ]);

            // 动态推送
            MsgService::factory()->push('dynamic', [
                'companyID'  => $this->companyID,
                'operatorID' => $userID,
                'operator'   => $username,
                'portrait'   => $portrait,
                'title'      => $title,
                'content'    => $content,
                'url'        => $url,
                'date'       => date('Y-m-d H:i:s'),
            ]);

            ResponseUtil::jsonCORS(null, Conf::SUCCESS);
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
        $userID = Yii::$app->user->id;

        /** @var \common\models\User $user */
        $user = Yii::$app->user->identity;
        $username = UserService::factory()->getUserName($user);
        $portrait = UserService::factory()->getUserPortrait($user);

        if (Yii::$app->request->isAjax) {
            $task = Task::findOne(['id' => $taskID, 'fdStatus' => Conf::ENABLE]);
            if (!$task) {
                throw new NotFoundHttpException('任务不存在或已删除');
            }

            if (!ProjectService::factory()->checkUserAccessProject($userID, $task->fdProjectID)) {
                throw new ForbiddenHttpException(ResponseUtil::$msg[1]);
            }

            if ($task->fdProgress == Conf::TASK_FINISH) {
                $attribute['fdProgress'] = Conf::TASK_STOP;
                $title = '重新开始了任务';
            } else {
                $attribute['fdProgress'] = Conf::TASK_FINISH;
                $title = '完成了任务';
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

            $url = Url::to(['task/view', 'taskID' => $taskID]);
            $content = $task->fdName;

            // 保存操作日志
            LogService::factory()->saveHandleLog([
                'objectID'   => $taskID,
                'companyID'  => $this->companyID,
                'operatorID' => $userID,
                'objectType' => Conf::OBJECT_TASK,
                'content'    => $content,
                'url'        => $url,
                'title'      => $title,
                'portrait'   => $portrait,
                'operator'   => $username
            ]);

            // 动态推送
            MsgService::factory()->push('dynamic', [
                'companyID'  => $this->companyID,
                'operatorID' => $userID,
                'operator'   => $username,
                'portrait'   => $portrait,
                'title'      => $title,
                'content'    => $content,
                'url'        => $url,
                'date'       => date('Y-m-d H:i:s'),
            ]);

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
        $userID = Yii::$app->user->id;

        /** @var \common\models\User $user */
        $user = Yii::$app->user->identity;
        $username = UserService::factory()->getUserName($user);
        $portrait = UserService::factory()->getUserPortrait($user);

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
                throw new ForbiddenHttpException(ResponseUtil::$msg[1]);
            }

            if ($task->fdProgress == Conf::TASK_STOP && $action === 'begin') {
                $attribute['fdProgress'] = Conf::TASK_BEGIN;
                $title = '开始处理任务';
            } elseif ($task->fdProgress == Conf::TASK_BEGIN && $action === 'stop') {
                $attribute['fdProgress'] = Conf::TASK_STOP;
                $title = '暂停了任务';
            } else {
                throw new ForbiddenHttpException('操作失败');
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

            $url = Url::to(['task/view', 'taskID' => $taskID]);
            $content = $task->fdName;

            // 保存操作日志
            LogService::factory()->saveHandleLog([
                'objectID'   => $taskID,
                'companyID'  => $this->companyID,
                'operatorID' => $userID,
                'objectType' => Conf::OBJECT_TASK,
                'content'    => $content,
                'url'        => $url,
                'title'      => $title,
                'portrait'   => $portrait,
                'operator'   => $username
            ]);

            // 动态推送
            MsgService::factory()->push('dynamic', [
                'companyID'  => $this->companyID,
                'operatorID' => $userID,
                'operator'   => $username,
                'portrait'   => $portrait,
                'title'      => $title,
                'content'    => $content,
                'url'        => $url,
                'date'       => date('Y-m-d H:i:s'),
            ]);

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