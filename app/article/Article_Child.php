<?php

namespace App\article;

use Illuminate\Database\Eloquent\Model;

class Article_Child extends Model
{
    //
    protected $table='zyz_child_object';
    protected $dateFormat ='U';
    protected $connection ='mysql';
    protected $fillable =[
        'id',
        'object_name',
        'object_title',
        'object_excerpt',
        'object_author',
        'object_content',
        'object_status',
        'object_slug',
        'object_type',
        'object_view',
        'object_like',
        'object_dislike',
        'object_comment_status',
        'object_score',
        'object_parent',
        'object_character',
        'object_resource',
        'object_term',
        'object_date',
        'object_guid',
        'app_id',
        'taxanomy_id',
    ];
    public function article(){
        return $this->belongsTo('App\article\Article');
    }
}
