<?php

namespace App\Http\Controllers;

use App\RabbitMQ;
use Illuminate\Http\Request;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPConnection;
class RabbitMQController extends Controller
{
    //
    public function index(){
        return View('rabbitmq');
    }
    public function test(){
        require_once '../vendor/autoload.php';
        define("RABBITMQ_HOST", "docker_rabbitmq_1");
        define("RABBITMQ_PORT", 5672);
        define("RABBITMQ_USERNAME", "zet");
        define("RABBITMQ_PASSWORD", "qwe123");
        define("RABBITMQ_QUEUE_NAME", "test");

        $connection = new \PhpAmqpLib\Connection\AMQPStreamConnection(
            RABBITMQ_HOST,
            RABBITMQ_PORT,
            RABBITMQ_USERNAME,
            RABBITMQ_PASSWORD
        );

        $channel = $connection->channel();
        $channel->queue_declare('hello', false, false, false, false);

        $msg = new AMQPMessage('Hello World!');
        $channel->basic_publish($msg, '', 'hello');

        echo " [x] Sent 'Hello World!'\n";
        $channel->close();
        $connection->close();
        return redirect('receive');
    }
    public function receive(){
        require_once '../vendor/autoload.php';
        define("RABBITMQ_HOST", "docker_rabbitmq_1");
        define("RABBITMQ_PORT", 5672);
        define("RABBITMQ_USERNAME", "zet");
        define("RABBITMQ_PASSWORD", "qwe123");
        define("RABBITMQ_QUEUE_NAME", "test");

        $connection = new \PhpAmqpLib\Connection\AMQPStreamConnection(
            RABBITMQ_HOST,
            RABBITMQ_PORT,
            RABBITMQ_USERNAME,
            RABBITMQ_PASSWORD
        );


        $channel = $connection->channel();

# Create the queue if it doesnt already exist.
        $channel->queue_declare(
            $queue = RABBITMQ_QUEUE_NAME,
            $passive = false,
            $durable = true,
            $exclusive = false,
            $auto_delete = false,
            $nowait = false,
            $arguments = null,
            $ticket = null
        );


        echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
        };

        $channel->basic_consume('hello', '', false, true, false, false, $callback);

        $timeout =0.1;
        while(count($channel->callbacks)){
            try{
                $channel->wait(null, false , $timeout);
            }catch(\PhpAmqpLib\Exception\AMQPTimeoutException $e){
                $channel->close();
                $connection->close();
                exit;
            }
        }

        $channel->close();
        $connection->close();
    }
}
