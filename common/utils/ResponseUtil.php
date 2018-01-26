<?php

namespace common\utils;


use Yii;
use yii\helpers\Json;

/**
 * 请求响应工具类
 * Class ResponseUtil
 * @package common\utils
 * @author wuzhc
 * @since 2018-01-15
 */
class ResponseUtil
{
    public static $msg = [
        1 => '你不是我们项目组的，联系下管理员吧',
        2 => '你没有创建任务的权限，联系下管理员吧',
        3 => '你没有编辑任务的权限，联系下管理员吧',
        4 => '你没有删除任务的权限，联系下管理员吧',
    ];

    /**
     * 支持jsonp跨域
     * @param array  $data
     * @param int    $code
     * @param string $msg
     * @param string $callback 跨域请求回调函数
     * @throws \yii\base\ExitException
     * <pre>
     *      ResponseUtil::json(['data'=>$data], 0, 'success', $callback);
     *      ResponseUtil::json(['data'=>$data,'code'=>0,'msg'=>'success,'callback'=>$callback]);
     * </pre>
     */
    public static function json($data = [], $code = 0, $msg = '操作成功', $callback = '')
    {
        if (!isset($data['code'])) {
            $data['code'] = $code;
        }

        if (!isset($data['msg'])) {
            $data['msg'] = $msg;
        }

        if (isset($data['callback'])) {
            $callback = $data['callback'];
        }

        // 跨域请求回调函数
        if ($callback) {
            echo $callback . '(' . Json::encode($data) . ')';
            Yii::$app->end();
        }

        Yii::$app->end(Json::encode($data));
    }

    /**
     * CORS跨域响应数据
     * @param array $data
     * @throws \yii\base\ExitException
     * @since 2017-06-04
     */
    public static function jsonCORS($data = [])
    {
        // 允许跨域域名
        $allowOrigin = [
            'http://zcshop.cm',
            'http://shop.cm',
        ];

        // 当前调用接口域名
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : $_SERVER['HTTP_HOST'];
        if (in_array($origin, $allowOrigin)) {
            // 跨域CORS
            header('Access-Control-Allow-Origin:' . $origin);
            header("Access-Control-Allow-Headers:X-Requested-With");
            header("Access-Control-Allow-Methods:PUT,POST,GET,DELETE,OPTIONS");
        }

        header("Content-Type:application/json;charset=utf-8");
        Yii::$app->end(Json::encode((array)$data));
    }

}