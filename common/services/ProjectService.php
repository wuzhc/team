<?php

namespace common\services;
use common\config\Conf;
use common\models\Project;


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
     * 新建项目记录
     * @param array $args
     * @return bool
     * @since 2018-01-19
     */
    public function save($args)
    {
        $project = new Project();
        $project->fdCreatorID = $args['creatorID'];
        $project->fdCompanyID = $args['companyID'];
        $project->fdName = $args['name'];
        $project->fdDescription = $args['description'];
        $project->fdStatus = Conf::ENABLE;
        $project->fdCreate = date('Y-m-d H:i:s');
        $project->fdUpdate = date('Y-m-d H:i:s');

        $res = $project->save() ? $project->id : 0;
        if (!$res) {
            if (YII_DEBUG) {
                var_dump($project->getErrors());
                exit;
            }
        }

        return $res;
    }

    /**
     * 更新项目记录
     * @param array $args
     * @return bool
     * @since 2018-01-19
     */
    public function update(array $args)
    {
        if (!empty($args['id'])) {
            return false;
        }

        $project = Project::findOne(['id' => $args['id']]);
        $project->fdName = $args['name'];
        $project->fdDescription = $args['description'];
        $project->fdStatus = $args['status'];
        $project->fdUpdate = date('Y-m-d H:i:s');

        $res = $project->update() ? true : false;
        if (!$res) {
            if (YII_DEBUG) {
                var_dump($project->getErrors());
                exit;
            }
        }

        return $res;
    }
}