<?php

namespace App\article;

use Illuminate\Database\Eloquent\Model;

class Article_Relationship extends Model
{
    //
    protected $table='zyz_object_relationship';
    protected $connection ='mysql';
    protected $dateFormat ='U';
    protected $fillable =[
        'id',
        'parent_object_id',
        'child_object_id',
        'order'
    ];
    public function article(){
        return $this->belongsTo('App\article\Article');
    }
}
