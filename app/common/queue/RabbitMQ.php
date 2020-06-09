<?php

namespace App\common\queue;

use App\article\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
class RabbitMQ extends Model
{
    //
    public $server =[
        'host'=>'rabbitmq',
        'port'=>5672,
        'username'=>'zet',
        'password'=>'qwe123',
        'vhost'=>'/'
    ];
    private $currentConnection = false;
    private $isConnected = false;
    public function isConnected(){
        return $this->isConnected;
    }
    public function init(){
        if($this->currentConnection == false){
            $this->currentConnection = $this->initConnection();
        }
        $this->isConnected = true;
    }
    public function initConnection(){
        try{
            return new AMQPStreamConnection('rabbitmq',5672,'zet','qwe123');
        }
        catch (\Exception $ex){
            echo "Connection fail. Please check server";
            echo $ex;
        }
    }
    public function pub($message,$exchange,$key='all',$print=true){
        $connection = $this->initConnection();
        $channel = $connection->channel();
        $msg = new AMQPMessage($message,['delivery_mode'=>2]);
        $channel->basic_public($msg,$exchange,$key);
        if($print){
            echo " [*] Sent ", $key, ': ', $message, " \n";
        }
        $channel->close();
        $connection->close();
    }

    public function sub($exchange,$key,$callback=false){
        $connection = $this->initConnection();
        $channel = $connection->channel();
        $channel->queue_declare('article',false,false,false,false);
        if ($callback == false)
            $callback = function ($msg) {
                echo ' [x] ', $msg->delivery_info['routing_key'], ': ', $msg->body, "\n";
                echo "Done \n";
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
            };
        $channel->basic_qos(null,1,null);
        $channel->basic_consume(
            $queue = 'article',
            $consumer_tag = '',
            $no_local = false,
            $no_ack = false,
            $exclusive = false,
            $nowait = false,
            $callback
        );
        $timeout =0.1;
        while(count($channel->callbacks)){
            try{
                $channel->wait();
            }catch(\PhpAmqpLib\Exception\AMQPTimeoutException $e){
                $channel->close();
                $connection->close();
                exit;
            }
        }
        $channel->close();
        $connection->close();
        /*$connection = $this->initConnection();
        $channel = $connection->channel();
        $queue_name = 'Queue.for.'.$key;
        $channel->queue_declare($queue_name, false, true, false, false);
        $channel->queue_bind($queue_name, $exchange, $key);
        echo ' [*] Waiting for processing. To exit press CTRL+C', " \n";
        if ($callback == false)
            $callback = function ($msg) {
                echo ' [x] ', $msg->delivery_info['routing_key'], ': ', $msg->body, "\n";
                echo "Done \n";
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
            };
        $channel->basic_qos(null,1,null);
        $channel->basic_consume(
            $queue = $queue_name,
            $consumer_tag = '',
            $no_local = false,
            $no_ack = false,
            $exclusive = false,
            $nowait = false,
            $callback
        );
        $timeout =0.1;
        while(count($channel->callbacks)){
            try{
                $channel->wait();
            }catch(\PhpAmqpLib\Exception\AMQPTimeoutException $e){
                $channel->close();
                $connection->close();
                exit;
            }
        }
        $channel->close();
        $connection->close();*/
    }
}
