<?php

namespace frontend\controllers;

use common\config\Conf;
use common\services\ProjectService;
use common\services\TeamService;
use common\services\UserService;
use common\utils\ResponseUtil;
use Yii;
use common\models\Project;
use common\models\ProjectSearch;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
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
                        'allow' => true,
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
            'verbs' => [
                'class' => VerbFilter::className(),
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
            $model->fdCompanyID = $this->companyID;
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
     * @param $id
     * @return string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionMembers($id)
    {
        if (empty($id)) {
            throw new ForbiddenHttpException('参数错误');
        }

        /** @var Project $project */
        $project = Project::findOne(['id' => $id, 'fdStatus' => Conf::ENABLE]);
        if (!$project || $project->fdCompanyID != $this->companyID) {
            throw new NotFoundHttpException('项目不存在或已删除');
        }

        if (($data = Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            // 过滤非法成员ID
            $memberIDs = $this->_filterMemberIDs($data['members']);
            if (!$memberIDs) {
                ResponseUtil::jsonCORS(['status' => 0, 'msg' => '设置失败，因为没有选择成员']);
            }

            // 已经加入项目的成员
            $hasJoinMemberIDs = ProjectService::factory()->getHasJoinProjectMemberIDs($id);

            // 新增成员
            $newMemberIDs = array_diff($memberIDs, $hasJoinMemberIDs);

            // 被移除成员
            $removeMemberIDs = array_diff($hasJoinMemberIDs, $memberIDs);

            $transaction = Yii::$app->db->beginTransaction();
            try {
                ProjectService::factory()->saveProjectUserMap($id, $newMemberIDs);
                ProjectService::factory()->removeProjectUserMap($id, $removeMemberIDs);
                $transaction->commit();
                ResponseUtil::jsonCORS(['status' => 1, 'msg' => '设置成功']);
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        } else {
            return $this->render('members', [
                'teams' => TeamService::factory()->getAllTeamMembers($this->companyID)
            ]);
        }
    }

    /**
     * 过滤提交的用户ID
     * @param $memberIDs
     * @return array
     */
    private function _filterMemberIDs($memberIDs)
    {
        if (empty($memberIDs) || !is_array($memberIDs)) {
            return [];
        }

        $memberIDs = array_filter(array_unique($memberIDs));
        $allMemberIDs = UserService::factory()->getUserIDs([
            'companyID' => $this->companyID,
            'status'    => Conf::USER_ENABLE
        ]);

        return array_intersect($memberIDs, $allMemberIDs);
    }

    /**
     * 获取已加入项目的成员
     * @param $id
     */
    public function actionGetHasJoinMemberIDs   ($id)
    {
        ResponseUtil::jsonCORS(['memberIDs' => ProjectService::factory()->getHasJoinProjectMemberIDs($id)]);
    }
}
