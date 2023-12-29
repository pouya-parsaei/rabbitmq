<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('hello', false, false, false, false);

$messageId = 1;

while(true){
    $msg = new AMQPMessage('Sending messageId:' . $messageId);

    $channel->basic_publish($msg, '', 'hello');

    echo " [x] Sent message:" . $msg->getBody() . "\n";

    sleep(random_int(1,3));

    $messageId++;
}