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
    public function getMembers($companyID)
    {
        $data = [];

        $allMembers = User::find()
            ->select(['id', 'fdName', 'fdPortrait', 'fdTeamID', 'fdRoleID'])
            ->andWhere(['fdCompanyID' => $companyID])
            ->andWhere(['fdStatus' => Conf::ENABLE])
            ->orderBy(['fdRoleID' => SORT_ASC])
            ->all();

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

        // 剩余未加入团队的成员
        $data[] = [
            'id' => 0,
            'name' => '其他',
            'members' => $teamMemberMap[0]
        ];

        return $data;
    }

    /**
     * 获取未加入团队的成员
     * @param $companyID
     * @return array|\yii\db\ActiveRecord[]
     * @since 2018-01-21
     */
    public function getNotTeamMembers($companyID)
    {
        return User::find()
            ->select(['id', 'fdName', 'fdPortrait'])
            ->andWhere(['fdCompanyID' => $companyID])
            ->andWhere(['fdTeamID' => 0])
            ->andWhere(['fdStatus' => Conf::ENABLE])
            ->all();
    }
}