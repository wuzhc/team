<?php

namespace console\controllers;


use Workerman\Worker;
use Yii;
use yii\console\Controller;

/**
 * workerman 基类
 * Class WorkermanController
 * @package console\controllers
 * @author wuzhc <wuzhc2016@163.com>
 * @since 2018-01-27
 */
class WorkermanBaseController extends Controller
{

    /** @var string 是否以守护进程模式运行，true是，false否 */
    public $daemon;

    /** @var string 命令参数，可选参数有start|stop|restart|reload|status */
    public $task;

    /**
     * 命令选项别名
     * @return array
     */
    public function optionAliases()
    {
        return ['d' => 'daemon', 't' => 'task'];
    }

    /**
     * 选项
     * @param string $actionID
     * @return array
     */
    public function options($actionID)
    {
        return ['daemon', 'task'];
    }

    /**
     * 启动入口
     */
    public function actionRun()
    {
        global $argv;
        if (!$argv[2] || strcasecmp($argv[2], 'help') === 0) {
            echo sprintf("Usage: ./yii %s -t=[start|stop|restart|reload|status] -d=[true] \n", $argv[1]);
            exit;
        }

        // 为workerman重置全局遍历$argv
        array_shift($argv);
        $argv[1] = $this->task;
        $argv[2] = $this->daemon === 'true' ? '-d' : '';

        $this->checkENV();
        define('GLOBAL_START', 1);
        $this->requireStartFile();
        Worker::runAll();
    }

    /**
     * 环境检测
     */
    private function checkENV()
    {
        if (!extension_loaded('pcntl')) {
            exit("Please install pcntl extension. See http://doc3.workerman.net/install/install.html\n");
        }

        if (!extension_loaded('posix')) {
            exit("Please install posix extension. See http://doc3.workerman.net/install/install.html\n");
        }
    }

    /**
     * 启动文件
     */
    protected function requireStartFile()
    {
        // 由子类实现
        exit('there are not start files');
    }
}