<?php

namespace console\controllers;


use PhpAmqpLib\Connection\AMQPStreamConnection;
use yii\console\Controller;

/**
 * 消费者控制器
 * Class ConsumeController
 * @package console\controllers
 * @author wuzhc
 * @since 2018-01-17
 */
class ConsumeController extends Controller
{
    /**
     * 发送邮件消费
     * @author wuzhc
     * @since 2018-01-17
     */
    public function actionSendEmail()
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('send_email', false, false, false, false);

        echo " [x] You can stop with Ctrl+C \n";

        $callback = function ($msg) {
            $seconds = substr_count($msg->body, '.');
            echo " [x] Wait ", $msg->body, ' ', $seconds, " seconds\n";
            sleep($seconds);
            echo " [x] Done \n";
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };

        $channel->basic_qos(null, 1, null);
        $channel->basic_consume('send_email', '', false, false, false, false, $callback);

        while (count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}