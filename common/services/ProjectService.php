<?php

namespace common\services;

use common\config\Conf;
use common\models\Project;
use common\models\ProjectUserMap;
use common\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;


/**
 * 项目服务类
 * Class ProjectService
 * @package common\services
 * @author wuzhc <wuzhc2016@163.com>
 * @since 2018-01-19
 */
class ProjectService extends AbstractService
{
    /**
     * Returns the static model.
     * @param string $className Service class name.
     * @return ProjectService the static model class
     */
    public static function factory($className = __CLASS__)
    {
        return parent::factory($className);
    }

    /**
     * 保存项目和用户的关系
     * @param $projectID
     * @param $userIDs
     * @return bool
     * @since 2018-01-22
     */
    public function saveProjectUserMap($projectID, $userIDs)
    {
        if (empty($projectID) || !is_array($userIDs)) {
            return false;
        }

        $rows = [];
        foreach ($userIDs as $k => $userID) {
            $rows[$k] = [$projectID, $userID];
        }

        if ($rows) {
            $res = Yii::$app->db->createCommand()->batchInsert(ProjectUserMap::tableName(), [
                'fdProjectID',
                'fdUserID'
            ], $rows)->execute();

            if (!$res && YII_DEBUG) {
                var_dump((new ProjectUserMap())->getErrors());
                exit;
            }

            return $res ? true : false;
        }

        return false;
    }

    /**
     * 从项目移除成员
     * @param int $projectID
     * @param array $memberIDs
     * @return bool
     * @since 2018-01-22
     */
    public function removeProjectUserMap($projectID, array $memberIDs)
    {
        if (!$projectID || empty($memberIDs) || !is_array($memberIDs)) {
            return false;
        }

        return ProjectUserMap::deleteAll(['fdUserID' => $memberIDs, 'fdProjectID' => $projectID]) ? true : false;
    }

    /**
     * 已经加入到项目成员
     * @param int $projectID 项目ID，对应tbProject.id
     * @return array|\yii\db\ActiveRecord[]
     * @since 2018-01-21
     */
    public function getHasJoinProjectMembers($projectID)
    {
        return ProjectUserMap::find()
            ->from(ProjectUserMap::tableName() . 'as projectUserMap')
            ->innerJoin(User::tableName() . 'as user', 'user.id = projectUserMap.fdUserID')
            ->andWhere(['user.fdStatus' => Conf::USER_ENABLE])
            ->andWhere(['projectUserMap.fdProjectID' => $projectID])
            ->select(['user.fdName as name', 'user.id as id'])
            ->asArray()
            ->all();
    }

    /**
     * 获取已经加入项目的成员ID
     * @param int $projectID
     * @return array
     * @since 2018-01-21
     */
    public function getHasJoinProjectMemberIDs($projectID)
    {
        $members = $this->getHasJoinProjectMembers($projectID);
        return $members ? ArrayHelper::getColumn($members, 'id') : [];
    }

    /**
     * 检查用户访问项目权限
     * @param int $userID 用户ID
     * @param int $projectID 项目ID
     * @param bool $isAllowByAdmin 是否允许被管理员访问，默认为true
     * @return bool
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @since 2018-01-22
     */
    public function checkUserAccessProject($userID, $projectID, $isAllowByAdmin = true)
    {
        if (empty($userID) || empty($projectID)) {
            throw new BadRequestHttpException('参数错误');
        }

        /** @var User $user */
        $user = User::findOne(['id' => $userID, 'fdStatus' => Conf::USER_ENABLE]);
        if (!$user) {
            throw new ForbiddenHttpException('用户数据不存在');
        }

        if (!(Project::findOne(['id' => $projectID, 'fdStatus' => Conf::ENABLE]))) {
            throw new NotFoundHttpException('项目不存在或已删除');
        }

        // 允许管理员访问
        if (true === $isAllowByAdmin) {
            if ($user->fdRoleID == Conf::ROLE_SUPER || $user->fdRoleID == Conf::ROLE_ADMIN) {
                return true;
            }
        }

        $projectUserMap = ProjectUserMap::findOne(['fdUserID' => $user->id, 'fdProjectID' => $projectID]);
        return $projectUserMap ? true : false;
    }
}