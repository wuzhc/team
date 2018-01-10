## 例子一： hello world

## 例子二：worker queue
- 可以用在异步请求里面（例如需要耗时间的请求）
- 默认情况下，RabbitMQ将会发送的每一条消息给下一个消费者，在序列。平均每个消费者将得到相同数量的消息。这种分发消息的方式称为循环轮询。试着用三个或更多的工人。
- 当消费速度慢时，可以开启多个消费者(worker)

#### 消息确认机制
当把basic_consume的参数no_ack设置为true时，消息达到消费者时就立刻被标记为删除状态，如果这时
一个worker的消息来不及执行完成就被中止掉，那么这条消息就会丢失，所以需要一个消息确认机制，当worker
die掉后，把消息重新分发给另一个woker执行  
方法：将no_ack设置为false，然后在回调函数中确认消息
```php
$callback = function($msg){
  echo " [x] Received ", $msg->body, "\n";
  sleep(substr_count($msg->body, '.'));
  echo " [x] Done", "\n";
  $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};

$channel->basic_consume('task_queue', '', false, false, false, false, $callback);
```
需要特别注意的是，如何忘记确认消息，将耗尽内存。查看未确认消息命令：
```bash
sudo rabbitmqctl list_queues name messages_ready messages_unacknowledged
```

#### 消息持久化问题
虽然消息确认机制能够保证消费者挂掉时消息不丢失，但是当rabbitmq挂掉时，那就没法保证了，这时就需要持久化
了。
方法：队列和消息必须设置为持久化  
(1) 队列持久化：生成者和消费者声明队列参数durable设置为true，已存在的队列不能重新设置参数值。命令如下：
```php
$channel->queue_declare('task_queue', false, true, false, false);
```
(2) 消息持久化：消息delivery_mode设置为2，如下：
```php
$msg = new AMQPMessage($data,
    array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
);
```

#### 公平分发
问题：当多个worker处理队列消息，rabbitmq循环均匀分配消息到分一个worker，如果此时其中一个worker分配到比较
耗时的任务，那么这个worker会比较忙碌，而其他worker比较清闲的情况。
方法：在worker处理和确认消息之前，不要再向worker发送新消息，而是向下一个清闲的worker发送，把prefetch参数设置为1.
当然，如果所有的worker都很忙，这时候应该增加worker数量
```php
$channel->basic_qos(null, 1, null);
```