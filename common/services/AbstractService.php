<?php

namespace common\services;


class AbstractService
{

    private static $_models = array();

    /**
     * 禁止实例化
     */
    private function __construct()
    {

    }

    /**
     * 禁止克隆
     */
    private function __clone()
    {

    }

    /**
     * 实例化对象
     * @param string $className
     * @return mixed
     */
    public static function factory($className = __CLASS__)
    {
        if (isset(self::$_models[$className])) {
            return self::$_models[$className];
        } else {
            $model = self::$_models [$className] = new $className (null);
            return $model;
        }
    }
}