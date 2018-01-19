<?php

namespace common\services;


use common\config\Conf;
use common\models\User;
use common\services\AbstractService;
use common\utils\ClientUtil;
use common\utils\VerifyUtil;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Yii;
use yii\helpers\StringHelper;

/**
 * Class CompanyService
 * 公司服务类
 * @package common\services
 * @since 2018-01-19
 * @author wuzhc2016@163.com
 */
class CompanyService extends AbstractService
{

    /**
     * Returns the static model.
     * @param string $className Service class name.
     * @return CompanyService the static model class
     */
    public static function factory($className = __CLASS__)
    {
        return parent::factory($className);
    }

    public function save(array $args)
    {
        if (!$args['id']) {

        }
    }
}