<?php

namespace common\services;


use yii\web\Controller;

class CommonService extends AbstractService
{
    /**
     * Returns the static model.
     * @param string $className Service class name.
     * @return CommonService the static model class
     */
    public static function factory($className = __CLASS__)
    {
        return parent::factory($className);
    }

    /**
     * 获取控制器方法名
     * @param      $controller
     * @param bool $isFormat 是否格式化方法名,例如： actionSaveName 将返回 save-name
     * @return array
     * @author wuzhc <wuzhc2016@163.com>
     * @since 2018-01-17
     */
    public function getControllerActions($controller, $isFormat = true)
    {
        if (!$controller instanceof Controller) {
            return [];
        }

        $functions = get_class_methods($controller);
        if (!$functions) {
            return [];
        }

        if (false === $isFormat) {
            return $functions;
        }

        $functions = array_filter($functions, function ($func) {
            if (0 === strpos($func, 'action') && $func !== 'actions') {
                return true;
            }
        });

        array_walk($functions, function (&$func) {
            $func = preg_replace_callback('/([A-Z]{1})/', function ($matches) {
                return '-' . strtolower($matches[0]);
            }, lcfirst(str_replace('action', '', $func)));
        });

        return $functions;
    }
}