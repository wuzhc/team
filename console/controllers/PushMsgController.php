<?php

namespace console\controllers;


use PHPSocketIO\Client;
use PHPSocketIO\SocketIO;
use Workerman\Lib\Timer;
use Workerman\Worker;
use Yii;
use yii\console\Controller;
use yii\redis\Connection;

/**
 * 消息推送
 * 命令： ./yii push-msg/run -t=start|stop|restart|reload|status -d=true
 * Class PushMsgController
 * @package console\controllers
 * @author wuzhc <wuzhc2016@163.com>
 * @since 2018-01-27
 */
class PushMsgController extends Controller
{
    public $last_online_count;

    public $companyID;

    public function actionStart()
    {
        // PHPSocketIO服务
        $io = new SocketIO(2120);
        // 客户端发起连接事件时，设置连接socket的各种事件回调
        $io->on('connection', function ($socket) use ($io) {

            /** @var Connection $redis */
            $redis = Yii::$app->redis;

            // 当客户端发来登录事件时触发
            $socket->on('login', function ($data) use ($socket, $redis, $io) {

                $uid = isset($data['uid']) ? $data['uid'] : null;
                $companyID = isset($data['companyID']) ? $data['companyID'] : null;

                // 参数检测 TODO uid和companyID应该做下加密 by wuzhc 2018-01-28
                if (empty($uid) || empty($companyID)) {
                    return ;
                }
                $this->companyID = $companyID;

                // 已经登录过了
                if (isset($socket->uid)) {
                    return;
                }

                $key = 'socketio:company:' . $companyID;
                $field = 'uid:' . $uid;

                // 用hash方便统计用户打开页面数量
                if (!$redis->hget($key, $field)) {
                    $redis->hset($key, $field, 0);
                }

                // 同个用户打开新页面时加1
                $redis->hincrby($key, $field, 1);

                // 加入uid分组，方便对同个用户的所有打开页面推送消息
                $socket->join('uid:'. $uid);

                // 加入companyID，方便对整个公司的所有用户推送消息
                $socket->join('company:'.$companyID);

                $socket->uid = $uid;
                $socket->companyID = $companyID;

                // 整个公司在线人数更新
                $io->to('company:'.$companyID)->emit('update_online_count', $redis->hlen($key));
            });

            // 当客户端断开连接是触发（一般是关闭网页或者跳转刷新导致）
            $socket->on('disconnect', function () use ($socket, $redis, $io) {
                if (!isset($socket->uid)) {
                    return;
                }

                $key = 'socketio:company:' . $socket->companyID;
                $field = 'uid:' . $socket->uid;
                $redis->hincrby($key, $field, -1);

                if ($redis->hget($key, $field) <= 0) {
                    $redis->hdel($key, $field);

                    // 某某下线了，刷新整个公司的在线人数
                    $io->to('company:'.$socket->companyID)->emit('update_online_count', $redis->hlen($key));
                }
            });
        });

        // 开始进程时，监听2121端口，用户数据推送
        $io->on('workerStart', function () use ($io) {
            /** @var Connection $redis */
            $redis = Yii::$app->redis;

            $httpWorker = new Worker('http://0.0.0.0:2121');
            // 当http客户端发来数据时触发
            $httpWorker->onMessage = function ($conn, $data) use ($io, $redis) {
                $response = [];
                $_POST = $_POST ? $_POST : $_GET;
                switch (@$_POST['action']) {
                    case 'publish':
                        $to = 'uid:' . @$_POST['to'];
                        $_POST['content'] = htmlspecialchars(@$_POST['content']);
                        $_POST['title'] = htmlspecialchars(@$_POST['title']);

                        // 有指定uid则向uid所在socket组发送数据
                        if ($to) {
                            $io->to($to)->emit('new_msg', $_POST);
                        }

                        $companyID = @$_POST['companyID'];
                        $key = 'socketio:company:' . $companyID;
                        $field = 'uid:' . $to;

                        // http接口返回，如果用户离线socket返回fail
                        if ($to && $redis->hget($key, $field)) {
                            return $conn->send('offline');
                        } else {
                            return $conn->send('ok');
                        }
                        break;
                    case 'dynamic':
                        $to = 'company:' . @$_POST['companyID'];
                        $_POST['content'] = htmlspecialchars(@$_POST['content']);
                        $_POST['title'] = htmlspecialchars(@$_POST['title']);

                        // 有指定uid则向uid所在socket组发送数据
                        if ($to) {
                            $io->to($to)->emit('update_dynamic', $_POST);
                        }

                        $companyID = @$_POST['companyID'];
                        $key = 'socketio:company:' . $companyID;

                        // http接口返回，如果用户离线socket返回fail
                        if ($to && $redis->hlen($key)) {
                            return $conn->send('offline');
                        } else {
                            return $conn->send('ok');
                        }
                }

                return $conn->send('fail');
            };

            // 执行监听
            $httpWorker->listen();
        });

        // 运行所有的实例
        global $argv;
        array_shift($argv);
        Worker::runAll();
    }
}