<?php

namespace common\services;


use common\config\Conf;
use common\models\Company;
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

    /**
     * 新建记录
     * @return int 成功返回记录ID，0表示失败
     * @since 2018-01-19
     */
    public function save()
    {
        $company = new Company();
        $company->fdCreatorID = 0;
        $company->fdStatus = Conf::ENABLE;
        $company->fdName = '';
        $company->fdDescription = '';
        $company->fdCreate = date('Y-m-d H:i:s');

        $res = $company->save() ? $company->id : 0;
        if (!$res) {
            // 调试模式下输出错误信息
            if (YII_DEBUG) {
                var_dump($company->getErrors());
                exit;
            }
        }

        return $res;
    }

    /**
     * 更新操作，以主键ID作为查询条件
     * @param int $id 对应tbCompany.id
     * @param array $args
     * @return bool
     * @since 2018-01-19
     */
    public function update($id, array $args)
    {
        if (empty($id)) {
            return false;
        }

        $company = Company::findOne(['id' => $id]);
        if (!$company) {
            return false;
        }

        if (!empty($args['name'])) {
            $company->fdName = $args['name'];
        }
        if (!empty($args['description'])) {
            $company->fdDescription = $args['description'];
        }
        if (!empty($args['status'])) {
            $company->fdStatus = $args['status'];
        }
        if (!empty($args['creatorID'])) {
            $company->fdCreatorID = $args['creatorID'];
        }

        $company->fdUpdate = date('Y-m-d H:i:s');
        $res = $company->update() ? true : false;

        if (!$res) {
            // 调试模式下输出错误信息
            if (YII_DEBUG) {
                var_dump($company->getErrors());
                exit;
            }
        }

        return $res;
    }
}