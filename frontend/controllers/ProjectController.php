<?php

namespace frontend\controllers;

use common\config\Conf;
use common\services\TaskService;
use common\services\TeamService;
use Yii;
use common\models\Project;
use common\models\ProjectSearch;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends BaseController
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
                            // 管理员身份检测
                            if (!Yii::$app->user->can('admin')) {
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
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Project model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Project();

        if ($model->load(Yii::$app->request->post())) {
            // 默认数据
            $model->fdCreatorID = Yii::$app->user->id;
            $model->fdCompanyID = Yii::$app->user->identity->fdCompanyID;
            $model->fdCreate = date('Y-m-d H:i:s');
            $model->fdUpdate = date('Y-m-d H:i:s');
            $model->fdStatus = Conf::ENABLE;

            if ($model->save()) {
                Yii::$app->redis->hmset(Conf::R_COUNTER_PROJ_TASK_NUM . $model->id, 'completeTasks', 0, 'allTasks', 0);
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $this->redirectMsgBox(['project/index'], '操作失败');
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->fdUpdate = date('Y-m-d H:i:s');
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $this->redirectMsgBox(['project/index'], '操作失败');
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Project model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->updateAttributes(['fdStatus' => Conf::DISABLE]);
        Yii::$app->redis->del(Conf::R_COUNTER_PROJ_TASK_NUM . $id);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 成员管理
     * @since 2018-01-22
     */
    public function actionMembers($id)
    {
        if (empty($id)) {
            throw new InvalidParamException('参数错误');
        }

        /** @var Project $project */
        $project = Project::findOne(['id' => $id]);
        if (!$project || $project->fdCompanyID != $this->companyID) {
            throw new NotFoundHttpException('页面不存在');
        }

        if ($data = Yii::$app->request->post() && Yii::$app->request->isAjax) {

        } else {
            return $this->render('members', [
                'teams' => TeamService::factory()->getAllTeamMembers($this->companyID)
            ]);
        }
    }
}
