<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Elasticquent\ElasticquentTrait;
class Post extends Model
{
    //
    protected $table = 'posts';
    protected $connection = 'mysql2';
    use ElasticquentTrait;

    protected $fillable = ['title', 'body', 'tags'];

    protected $mappingProperties = array(
        'title' => [
            'type' => 'text',
            "analyzer" => "standard",
        ],
        'body' => [
            'type' => 'text',
            "analyzer" => "standard",
        ],
        'tags' => [
            'type' => 'text',
            "analyzer" => "standard",
        ],
    );
}
