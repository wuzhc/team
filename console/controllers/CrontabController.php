<?php

namespace console\controllers;

use Yii;

/**
 * Class CrontabController
 * workerman-crontab 定时任务
 *
 * 命令如下:
 * ./yii crontab/run --task=start|stop|restart|reload|status --daemon=true
 * 使用别名选项:
 * ./yii crontab/run -t=start|stop|restart|reload|status -d=true
 *
 * @see workermanController->actionRun()
 * @since 2018-01-26
 * @author wuzhc <wuzhc2016@163.com>
 * @package console\controllers
 */
class CrontabController extends WorkermanBaseController
{
    /**
     * 引入启动文件
     */
    protected function requireStartFile()
    {
        $path = Yii::getAlias('@ext/Crontab/start*.php');
        foreach(glob($path) as $start_file) {
            require_once $start_file;
        }
    }
}