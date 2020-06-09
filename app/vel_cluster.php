<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Elasticquent\ElasticquentTrait;
class vel_cluster extends Model
{
    //
    protected $connection = 'mysql2';
    protected $table ='vel_cluster';
    use ElasticquentTrait;
}
