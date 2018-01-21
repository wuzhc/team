<?php

namespace frontend\controllers;

use common\config\Conf;
use common\services\TeamService;
use common\utils\ResponseUtil;
use Yii;
use common\models\Team;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;
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
            'data' => TeamService::factory()->getAllTeamMembers($this->companyID)
        ]);
    }

    /**
     * Displays a single Team model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $team = Team::findOne(['id' => $id, 'fdStatus' => Conf::ENABLE]);
        if (!$team) {
            $this->redirectMsgBox(['team/index'], '数据不存在或已删除');
        }

        return $this->render('view', [
            'team' => $team,
            'members' => TeamService::factory()->getHasJoinTeamMembers($id)
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

            $msg = User::updateAll(['fdTeamID' => $teamID], ['in', 'id', $memberIDs]) ? '创建成功' : '创建失败';
            $this->redirectMsgBox(['team/index'], $msg);
        } else {
            return $this->render('create', [
                'members' => TeamService::factory()->getNotJoinTeamMembers($this->companyID),
            ]);
        }
    }

    /**
     * 过滤非法成员ID
     * @param $ids
     * @param $teamID
     * @return array
     */
    private function _filterIllegalMembers($ids, $teamID = 0)
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
                ($member->fdTeamID == 0 || $member->fdTeamID == $teamID)
            ) {
                $data[] = $member->id;
            }
        }

        return $data;
    }

    /**
     * 编辑团队
     * @param $id
     * @return string
     * @throws ForbiddenHttpException
     * @throws \Exception
     */
    public function actionUpdate($id)
    {
        $team = Team::findOne(['id' => $id, 'fdStatus' => Conf::ENABLE]);
        if (!$team) {
            $this->redirectMsgBox(['team/index'], '数据不存在或已删除');
        }

        if ($data = Yii::$app->request->post()) {

            // 过滤非法数据
            if (!empty($data['members'])) {
                $memberIDs = $this->_filterIllegalMembers($data['members'], $id);
            } else {
                $memberIDs = [];
            }

            // 已经加入团队的成员
            $hasJoinMemberIDs = TeamService::factory()->getHasJoinTeamMemberIDs($id);

            $transaction = Yii::$app->db->beginTransaction();
            try {
                // 更新team信息
                TeamService::factory()->update([
                    'id'          => $id,
                    'name'        => !empty($data['name']) ? $data['name'] : '',
                    'description' => !empty($data['desc']) ? $data['desc'] : '',
                    'status'      => Conf::ENABLE
                ]);

                // 移除成员
                $removes = array_diff($hasJoinMemberIDs, $memberIDs);
                if ($removes) {
                    User::updateAll(['fdTeamID' => 0], ['in', 'id', $removes]);
                }

                // 新增成员
                $news = array_diff($memberIDs, $hasJoinMemberIDs);
                if ($news) {
                    User::updateAll(['fdTeamID' => $id], ['in', 'id', $news]);
                }

                $transaction->commit();
                $this->redirectMsgBox(['team/index'], '操作成功');
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        } else {
            $members = User::find()
                ->select(['id', 'fdPortrait', 'fdName', 'fdTeamID'])
                ->andWhere(['fdStatus' => Conf::ENABLE])
                ->andWhere(['in', 'fdTeamID', [$id, 0]])
                ->all();

            return $this->render('update', [
                'team'    => $team,
                'members' => $members,
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
