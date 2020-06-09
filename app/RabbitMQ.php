<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPConnection;
require_once '../vendor/autoload.php';
class RabbitMQ extends Model
{
    //
    private $conn;
    protected $host ='docker_rabbitmq_1';
    protected $port =5672;
    protected $user ='zet';
    protected $pass='administrator';
    public function __construct(array $attributes = [])
    {
        if(!$this->conn){
            $this->conn = new AMQPConnection($this->host,$this->port,$this->user,$this->pass);
        }
    }

    function connect(){

    }
    function dis_connect(){
        $this->conn->close();
    }
}
