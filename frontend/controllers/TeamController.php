<?php

namespace frontend\controllers;

use common\config\Conf;
use common\services\TeamService;
use common\utils\ResponseUtil;
use Yii;
use common\models\Team;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
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
     * 创建团队
     * @return string
     * @throws ForbiddenHttpException
     * @throws BadRequestHttpException
     * @since 2018-01-20
     */
    public function actionCreate()
    {
        if (!Yii::$app->user->can('createTeam')) {
            throw new ForbiddenHttpException('你没有创建团队的权限，联系下管理员吧');
        }

        if ($data = Yii::$app->request->post()) {
            if (empty($data['name'])) {
                throw new BadRequestHttpException('团队名称不能为空');
            }

            // 团队成员
            $memberIDs = $this->_filterIllegalMembers($data['members']);

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
     * @throws ForbiddenHttpException
     * @return array
     */
    private function _filterIllegalMembers($ids, $teamID = 0)
    {
        $data = [];
        if (empty($ids)) {
            throw new ForbiddenHttpException('没有选择成员，禁止此次操作');
        }

        // 过滤重复和
        $ids = array_unique(array_filter($ids));
        if (count($ids) == 0) {
            throw new ForbiddenHttpException('非法参数，禁止此次操作');
        }

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
        if (!Yii::$app->user->can('editTeam')) {
            throw new ForbiddenHttpException('你没有编辑团队的权限，联系下管理员吧');
        }

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
//                $this->redirectMsgBox(['team/index'], '操作成功');
                Yii::$app->session->setFlash('success', '操作成功');
                $this->redirect(['team/index']);

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
     * @throws ForbiddenHttpException
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('delTeam')) {
            throw new ForbiddenHttpException('你没有创建团队的权限，联系下管理员吧');
        }

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
