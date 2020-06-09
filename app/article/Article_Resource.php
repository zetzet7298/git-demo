<?php

namespace App\article;

use Illuminate\Database\Eloquent\Model;

class Article_Resource extends Model
{
    //
    protected $table ='zyz_object_resource';
    protected $dateFormat ='U';
    protected $connection='mysql';
    protected $fillable=[
        'id',
        'object_id',
        'type',
        'content',
    ];
    public function article(){
        return $this->belongsTo('App\article\Article');
    }
}
