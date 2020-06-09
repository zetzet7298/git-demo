<?php

namespace App\character;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    //
    protected $table='zyz_character';
    protected $dateFormat='U';
    protected $connection='mysql';
    protected $fillable =[
        'id',
        'character_name',
        'character_status',
        'character_parent',
        'character_slug',
        'character_description',
        'character_view',
        'character_like',
        'character_dislike',
        'character_follow',
        'character_resource_id',
        'character_avatar_path',
        'character_avatar_base_url',
        'character_banner_path',
        'character_banner_base_url'
    ];
    public function article_character(){
        return $this->hasMany('App\article\Article_Character');
    }
}
