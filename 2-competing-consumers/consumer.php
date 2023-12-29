<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('hello', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
    $processingTime = random_int(1, 6);
    echo ' [x] Received ' . $msg->body . ' will take ' . $processingTime . ' to process' . "\n";
    sleep($processingTime);
    $msg->delivery_info['channel']->basic_ack(delivery_tag: $msg->delivery_info['delivery_tag']);
    echo 'finished processing the message' . "\n";
};


//$channel->basic_qos(prefetch_size: 0, prefetch_count: 1, a_global: false);

$channel->basic_consume('hello', '', false, false, false, false, $callback);

try {
    $channel->consume();
} catch (\Throwable $exception) {
    echo $exception->getMessage();
}