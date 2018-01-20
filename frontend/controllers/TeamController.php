<?php

namespace frontend\controllers;

use common\config\Conf;
use common\services\TeamService;
use common\utils\ResponseUtil;
use Yii;
use common\models\Team;
use yii\base\InvalidParamException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\User;

/**
 * TeamController implements the CRUD actions for Team model.
 * @author wuzhc <wuzhc2016@163.com>
 * @since 2018-01-20
 */
class TeamController extends BaseController
{
    public $layout = 'main-member';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Team models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 获取团队成员
     * @since 2018-01-20
     */
    public function actionGetMembers()
    {
        ResponseUtil::jsonCORS([
            'data' => TeamService::factory()->getMembers($this->companyID)
        ]);
    }

    /**
     * Displays a single Team model.
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
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionCreate()
    {
        if ($data = Yii::$app->request->post()) {
            // 过滤非法数据
            if (!$memberIDs = $this->_filterIllegalMembers($data['members'])) {
                throw new ForbiddenHttpException('非法参数');
            }

            $teamID = TeamService::factory()->save([
                'name'        => $data['name'],
                'creatorID'   => Yii::$app->user->id,
                'companyID'   => $this->companyID,
                'description' => $data['desc']
            ]);

            if (!$teamID) {
                $this->redirectMsgBox(['team/index'], '创建失败');
            }

            $msg = User::updateAll(['fdTeamID' => $teamID], ['in', 'id', $memberIDs])
                ? '创建成功' : '创建失败';

            $this->redirectMsgBox(['team/index'], $msg);

        } else {
            return $this->render('create', [
                'members' => TeamService::factory()->getNotTeamMembers($this->companyID),
            ]);
        }
    }

    private function _filterIllegalMembers($ids)
    {
        $data = [];

        $members = User::find()
            ->select(['id', 'fdStatus', 'fdCompanyID', 'fdTeamID'])
            ->andWhere(['in', 'id', $ids])
            ->all();

        if (!$members) {
            return $data;
        }

        /** @var User $member */
        foreach ($members as $member) {
            if ($member->fdStatus == Conf::ENABLE &&
                $member->fdCompanyID == $this->companyID &&
                $member->fdTeamID == 0
            ) {
                $data[] = $member->id;
            }
        }

        return $data;
    }

    /**
     * Updates an existing Team model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Team model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Team model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Team the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Team::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
