<?php

namespace App\article;

use Carbon\Carbon;
use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Article extends Model
{
    //
    use SoftDeletes;
    use ElasticquentTrait;
    protected $table='zyz_object';
    protected $connection = 'mysql';
    protected $dates = ['deleted_at'];
    protected $dateFormat = 'U';
    public function setUpdatedAt($value)
    {
        // Do nothing.
    }
    protected $fillable=[
        'id',
        'object_title',
        'object_excerpt',
        'object_author',
        'object_content',
        'object_status',
        'object_comment_status',
        'object_password',
        'object_name',
        'object_content_filtered',
        'object_parent',
        'object_guid',
        'object_type',
        'object_comment_count',
        'object_slug',
        'object_description',
        'object_keyword',
        'object_lang',
        'object_author_name',
        'object_total_number_meta',
        'object_total_number_resource',
        'object_tags',
        'object_view',
        'object_like',
        'object_dislike',
        'object_rating_score',
        'object_rating_average',
        'object_layout',
        'created_at',
        'created_gmt',
        'updated_gmt',
        'updated_at',
        'object_date'
    ];
    protected $mappingProperties =[
        'object_title'=>[
            'type'=>'text',
            'analyzer'=>'standard'
        ],
        'object_excerpt'=>[
            'type'=>'text',
            'analyzer'=>'standard'
        ],
        'object_date'=>[
            'type'=>'integer',
            'analyzer'=>'standard'
        ],
    ];
    public function getIndexName(){
        return 'zyz_article_term';
    }
    public function getTypeName()
    {
        return 'doc';
    }
    public function article_character(){
        return $this->hasMany('App\article\Article_Character');
    }
    public function article_child(){
        return $this->hasMany('App\article\Article_Child');
    }
    public function article_relationship(){
        return $this->hasMany('App\article\Article_Relationship');
    }
    public function article_resource(){
        return $this->hasMany('App\article\Article_Resource');
    }
    public function article_term(){
        return $this->hasMany('App\article\Article_Term');
    }
}
