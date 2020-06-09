<?php

namespace App\article;

use Illuminate\Database\Eloquent\Model;

class Article_Character extends Model
{
    //
    protected $table='zyz_object_character';
    protected $connection='mysql';
    protected $dateFormat='U';
    protected $fillable =[
        'id',
        'object_id',
        'character_id',
        'note'
    ];
    public function article(){
        return $this->belongsTo('App\article\Article');
    }
    public function character(){
        return $this->belongsTo('App\character\Character');
    }
}
