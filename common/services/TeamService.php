<?php

namespace common\services;

use common\config\Conf;
use common\models\Project;
use common\models\Team;
use common\models\User;
use Yii;
use yii\helpers\ArrayHelper;


/**
 * 团队管理服务类
 * Class TeamService
 * @package common\services
 * @author wuzhc <wuzhc2016@163.com>
 * @since 2018-01-19
 */
class TeamService extends AbstractService
{
    /**
     * Returns the static model.
     * @param string $className Service class name.
     * @return TeamService the static model class
     */
    public static function factory($className = __CLASS__)
    {
        return parent::factory($className);
    }

    /**
     * 新建团队记录
     * @param array $args
     * @return bool
     * @since 2018-01-19
     */
    public function save($args)
    {
        $team = new Team();
        $team->fdCreatorID = $args['creatorID'];
        $team->fdCompanyID = $args['companyID'];
        $team->fdName = $args['name'];
        $team->fdDescription = $args['description'];
        $team->fdStatus = Conf::ENABLE;
        $team->fdCreate = date('Y-m-d H:i:s');
        $team->fdUpdate = date('Y-m-d H:i:s');

        $res = $team->save() ? $team->id : 0;
        if (!$res) {
            if (YII_DEBUG) {
                var_dump($team->getErrors());
                exit;
            }
        }

        return $res;
    }

    /**
     * 更新团队记录
     * @param array $args
     * @return bool
     * @since 2018-01-19
     */
    public function update(array $args)
    {
        if (!empty($args['id'])) {
            return false;
        }

        $team = Team::findOne(['id' => $args['id']]);
        $team->fdName = $args['name'];
        $team->fdDescription = $args['description'];
        $team->fdStatus = $args['status'];
        $team->fdUpdate = date('Y-m-d H:i:s');

        $res = $team->update() ? true : false;
        if (!$res) {
            if (YII_DEBUG) {
                var_dump($team->getErrors());
                exit;
            }
        }

        return $res;
    }

    /**
     * 获取团队成员
     * @param int $companyID
     * @return array
     * @since 2018-01-20
     */
    public function getAllTeamMembers($companyID)
    {
        $data = [];

        $allMembers = UserService::factory()->getUsers([
            'select'    => ['id', 'fdName', 'fdPortrait', 'fdTeamID', 'fdRoleID'],
            'companyID' => $companyID,
            'status'    => Conf::USER_ENABLE,
            'order'     => ['fdRoleID' => SORT_ASC]
        ]);
        if (!$allMembers) {
            return $data;
        }

        $teamMemberMap = [];
        /** @var User $member */
        foreach ($allMembers as $member) {
            $temp = [];
            $temp['id'] = $member->id;
            $temp['name'] = $member->fdName;
            $temp['portrait'] = $member->fdPortrait ?: Conf::USER_PORTRAIT;
            $temp['role'] = Yii::$app->params['role'][$member->fdRoleID];
            $teamMemberMap[$member->fdTeamID][] = $temp;
        }

        $allTeams = Team::find()
            ->select(['id', 'fdName'])
            ->andWhere(['fdStatus' => Conf::ENABLE])
            ->orderBy(['fdOrder' => SORT_DESC])
            ->all();

        /** @var Team $team */
        foreach ((array)$allTeams as $team) {
            $temp = [];
            $temp['id'] = $team->id;
            $temp['name'] = $team->fdName;

            if (isset($teamMemberMap[$team->id])) {
                $temp['members'] = $teamMemberMap[$team->id];
            } else {
                $temp['members'] = [];
            }

            $data[] = $temp;
        }

        // 其他未加入团队的成员
        $data[] = [
            'id'      => 0,
            'name'    => '其他',
            'members' => isset($teamMemberMap[0]) ? $teamMemberMap[0] : []
        ];

        return $data;
    }

    /**
     * 获取未加入团队的成员
     * @param $companyID
     * @return array|\yii\db\ActiveRecord[]
     * @since 2018-01-21
     */
    public function getNotJoinTeamMembers($companyID)
    {
        return UserService::factory()->getUsers([
            'select'    => ['id', 'fdName', 'fdPortrait'],
            'teamID'    => 0,
            'status'    => Conf::ENABLE,
            'companyID' => $companyID
        ]);
    }

    /**
     * 已经加入到某个团队成员
     * @param int $teamID 团队ID，对应tbTeam.id
     * @param array $select 需要返回的字段
     * @return array|\yii\db\ActiveRecord[]
     * @since 2018-01-21
     */
    public function getHasJoinTeamMembers($teamID, $select = [])
    {
        return UserService::factory()->getUsers([
            'select'    => $select ?: ['id', 'fdName', 'fdPortrait'],
            'teamID'    => $teamID,
            'status'    => Conf::ENABLE,
        ]);
    }

    /**
     * 获取已经加入团队的成员ID
     * @param int $teamID
     * @return array
     * @since 2018-01-21
     */
    public function getHasJoinTeamMemberIDs($teamID)
    {
        $members = $this->getHasJoinTeamMembers($teamID, ['id']);
        return $members ? ArrayHelper::getColumn($members, 'id') : [];
    }
}