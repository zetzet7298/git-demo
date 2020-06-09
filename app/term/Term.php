<?php

namespace App\term;

use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    //
    protected $table='zyz_term';
    use ElasticquentTrait;
    protected $connection ='mysql';
    protected $dateFormat ='U';
    protected $fillable = [
        'id',
        'name',
        'description',
        'slug',
        'order',
        'status',
        'parent',
        'taxanomy_id'
    ];
    public function article_term(){
        return $this->hasMany('App\article\Article_Term');
    }
}
