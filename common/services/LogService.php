<?php

namespace common\services;


use yii\web\Controller;

class LogService extends AbstractService
{
    /**
     * Returns the static model.
     * @param string $className Service class name.
     * @return LogService the static model class
     */
    public static function factory($className = __CLASS__)
    {
        return parent::factory($className);
    }

    public function saveHandle()
    {

    }
}