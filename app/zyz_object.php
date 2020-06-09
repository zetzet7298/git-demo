<?php

namespace App;

use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\Model;

class zyz_object extends Model
{
    //
    use ElasticquentTrait;
    protected $table = 'zyz_object';
    protected $mappingProperties=[
        'object_title'=>[
            'type'=>'text',
            'analyzer'=>'standard'
        ],
        'object_excerpt'=>[
            'type'=>'text',
            'analyzer'=>'standard'
        ],
        'object_date'=>[
            'type'=>'date',
            'analyzer'=>'standard'
        ]
    ];
    function getIndexName(){
        return 'index_object';
    }
}
