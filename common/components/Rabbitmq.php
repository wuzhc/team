<?php

namespace common\components;


use Yii;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use yii\base\Component;

class Rabbitmq extends Component
{
    private $_conn;

    public $host = 'localhost';

    public $port = 5672;

    public $user = 'guest';

    public $password = 'guest';
    
    public function init()
    {
        parent::init();
    }

    public function getConnect()
    {
        $this->setConnect();

        return $this->_conn;
    }

    public function setConnect()
    {
        if (null === $this->_conn) {
            $this->_conn = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        }
    }
    
}