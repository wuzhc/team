<?php

namespace frontend\controllers;


use common\config\Conf;
use common\models\Project;
use common\models\Task;
use common\services\ProjectService;
use common\services\TaskService;
use common\utils\ResponseUtil;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

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
     * @return string
     * @since 2018-01-22
     */
    public function actionIndex($projectID)
    {
        return $this->render('index', [
            'labels'     => TaskService::factory()->getTaskLabels($projectID),
            'categories' => TaskService::factory()->getTaskCategories($projectID)
        ]);
    }

    public function actionList($projectID, $categoryID)
    {

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
}