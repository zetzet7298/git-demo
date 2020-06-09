<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vel_veldette extends Model
{
    //
    protected $connection = 'mysql2';
    protected $table='vel_veldette';
    protected $fillable = ['page_id','cluster','app_id'];
    public $timestamps = false;
}
