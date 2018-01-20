<?php

namespace common\services;
use common\config\Conf;
use common\models\Project;
use Yii;


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

}