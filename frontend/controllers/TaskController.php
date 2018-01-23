<?php

namespace frontend\controllers;


use common\config\Conf;
use common\models\Project;
use common\models\Task;
use common\services\ProjectService;
use common\services\TaskService;
use common\services\UserService;
use common\utils\ResponseUtil;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\StringHelper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\User;

class TaskController extends Controller
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
                                throw new NotFoundHttpException('参数错误');
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
     * @param int $projectID
     * @param int $categoryID
     * @param int $labelID
     * @param int $me
     * @return string
     * @since 2018-01-23
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 列表数据
     * @param $projectID
     * @param $categoryID
     * @param int $labelID
     * @param int $me
     */
    public function actionList($projectID)
    {
//        if (Yii::$app->request->isAjax) {
        if (true) {
            $params = Yii::$app->request->get();
            $args = [
                'userID'     => isset($params['me']) && $params['me'] == 1 ? Yii::$app->user->id : null,
                'labelID'    => !empty($params['labelID']) ? $params['labelID'] : null,
                'categoryID' => !empty($params['categoryID']) ? $params['categoryID'] : null,
            ];

            $total = null;
            if (!empty($params['totalInit'])) {
                $total = TaskService::factory()->countTasks($projectID, $args);
            }

            $args['limit'] = !empty($params['limit']) ? $params['limit'] : 10;
            $args['offset'] = !empty($params['offset']) ? $params['offset'] : 0;
            $args['order'] = !empty($params['order']) ? $params['order'] : 'id desc';

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
                $temp['process'] = $task->fdProgress;
                $temp['creator'] = UserService::factory()->getUserName($task->fdCreatorID);
                $list[] = $temp;
            }

            ResponseUtil::jsonCORS([
                'data' => [
                    'total' => $total,
                    'list'  => $list
                ]
            ]);
        }
    }

    /**
     * 创建任务清单
     */
    public function actionCreateTaskCategory($projectID)
    {
        if ($name = Yii::$app->request->get('name') && Yii::$app->request->isAjax) {
            $res = TaskService::factory()->saveTaskCategory($projectID, $name);
            ResponseUtil::jsonCORS(['status' => (int)$res]);
        }
    }


    public function actionCreate($projectID)
    {
        if (!Yii::$app->user->can('createTask')) {
            throw new ForbiddenHttpException('禁止操作');
        }

        if ($data = Yii::$app->request->post()) {

        } else {
            return $this->render('create', [
                'labels'     => TaskService::factory()->getTaskLabels($projectID),
                'categories' => TaskService::factory()->getTaskCategories($projectID)
            ]);
        }
    }

    public function actionUpdate($projectID)
    {
        if (Yii::$app->user->can('editTask')) {
            throw new ForbiddenHttpException('禁止操作');
        }

        if ($data = Yii::$app->request->post()) {

        } else {
            return $this->render('update');
        }
    }

    public function actionView($projectID)
    {
        return $this->render('view', [
            'labels'     => TaskService::factory()->getTaskLabels($projectID),
            'categories' => TaskService::factory()->getTaskCategories($projectID)
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
            'status'  => Conf::ENABLE,
            'group'   => ['fdTaskCategoryID'],
            'asArray' => true,
            'process' => Conf::TASK_FINISH,
            'select'  => ['count(id) as completeTotal', 'fdTaskCategoryID as cid']
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
}