<?php

namespace App;

use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
class zyz_user extends Authenticatable
{
    //
    use Notifiable;
    use ElasticquentTrait;
    protected $table='zyz_user';
    protected $fillable =[
        'username','password_hash',
    ];
    /*protected $hidden = [
        'password_hash','remember_token',
    ];*/

    protected $mappingProperties =array(
        'username'=>[
            'type'=>'text',
            'analyzer'=>'standard'
        ],
        'password_hash'=>[
            'type'=>'text',
            'analyzer'=>'standard'
        ],
    );
    public function username()
    {
        return 'username';
    }
    public function getAuthPassword () {

        return $this->password_hash;

    }
}
