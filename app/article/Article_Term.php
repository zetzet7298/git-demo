<?php

namespace App\article;

use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\Model;

class Article_Term extends Model
{
    //
    use ElasticquentTrait;
    protected $table = 'zyz_object_term';
    protected $connection ='mysql';
    protected $dateFormat ='U';
    protected $fillable =[
        'id',
        'term_id',
        'object_id',
        'data'
    ];
    protected $mappingProperties = [
        'id'=>[
            'type'=>'integer',
            'analyzer'=>'standard'
        ],
        'term_id'=>[
            'type'=>'integer',
            'analyzer'=>'standard'
        ],
        'object_id'=>[
            'type'=>'integer',
            'analyzer'=>'standard'
        ],
    ];
    public function getIndexName(){
        return 'zyz_article';
    }
    public function getTypeName()
    {
        return 'doc';
    }
    public function article(){
        return $this->belongsTo('App\article\Article');
    }
    public function term(){
        return $this->belongsTo('App\term\term');
    }
}
