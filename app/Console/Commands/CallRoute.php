<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\article\Article;
use Illuminate\Support\Facades\Redis;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use App\common\queue\RabbitMQ;
class CallRoute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:article {--task=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function initAMQP($exchange,$key,$callback ='processDefault'){
        try{
            $callback_function = array(&$this,$callback);
            $rabbitmq = new RabbitMQ();
            $rabbitmq->sub($exchange,$key,$callback_function);
        }catch (\Exception $ex){
            echo $ex;
        }
    }
    public function processArticle($msg){
        echo "success";
        $id = json_decode($msg->body,true);
        $article = Article::find($id);
        $article->addToIndex();
        $arr = $article->toArray();
        foreach($arr as $key=>$value){
            Redis::hset('article'.$article->id,$key,$value);
        }
    }
    public function handle()
    {
        $this->initAMQP('','article','processArticle');

        //
        /*$connection = new AMQPStreamConnection('rabbitmq',5672,'zet','qwe123');
        $channel = $connection->channel();
        $channel->queue_declare('article',false,false,false,false);
        $callback = function($msg){
            echo "Success". " ".$msg->body;
            $id = json_decode($msg->body,true);
            $article = Article::find($id);
            $article->addToIndex();
            $arr = $article->toArray();
            foreach($arr as $key=>$value){
                Redis::hset('article'.$article->id,$key,$value);
            }
            $response['success'] = 'Created';
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
        $connection->close();*/
    }
}
