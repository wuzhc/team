<?php

namespace common\services;
use common\config\Conf;
use common\models\Project;
use common\models\Team;
use common\models\User;
use Yii;


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
     * 获取团队成员
     * @param int $companyID
     * @return array
     * @since 2018-01-20
     */
    public function getMembers($companyID)
    {
        $allMembers = User::find()
            ->select(['id', 'fdName', 'fdPortrait', 'fdTeamID', 'fdRoleID'])
            ->andWhere(['fdCompanyID' => $companyID])
            ->andWhere(['fdStatus' => Conf::ENABLE])
            ->orderBy(['fdRoleID' => SORT_ASC])
            ->all();

        if (!$allMembers) {
            return [];
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

        $data = [];

        /** @var Team $team */
        foreach ((array)$allTeams as $team) {
            $temp = [];
            $temp['id'] = $team->id;
            $temp['name'] = $team->fdName;

            if (isset($teamMemberMap[$team->id])) {
                $temp['count'] = count($teamMemberMap[$team->id]);
                $temp['members'] = $teamMemberMap[$team->id];
            } else {
                $temp['count'] = 0;
                $temp['members'] = [];
            }

            $data[] = $temp;
        }

        // 没有所属团队成员
        $data[] = [
            'id' => 0,
            'name' => '其他',
            'count' => count($teamMemberMap[0]),
            'members' => $teamMemberMap[0]
        ];

        return $data;
    }
}