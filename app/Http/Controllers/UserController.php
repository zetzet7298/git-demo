<?php

namespace App\Http\Controllers;

use App\Article2;
use App\zyz_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Elasticsearch\ClientBuilder;
class UserController extends Controller
{
    //
    public function learnESPHP(){
        phpinfo(); exit();
        $params = [
            'index'=>'my_index',
            'id'=>'my_id',
            'body'=>['body'=>'abc']
        ];
        $article = Article2::all();
        $response = $client->index($params);
        print_r($response);
    }
    public function getLogin(){
        return View('login');
    }
    public function saveToES(){
        zyz_user::addAllToIndex();
    }
    public function removeFromES(){
        zyz_user::reindex();
    }
    public function mapping(){
        //zyz_user::rebuildMapping();
        /*zyz_user::deleteMapping();
        zyz_user::putMapping($ignoreConflicts = true);*/
        $user = zyz_user::search('a');
        echo "<pre>";
        print_r($user);
        echo "</pre>";
        exit();
        echo zyz_user::mappingExists();
        echo "<pre>";
        print_r(zyz_user::getMapping());
        echo "</pre>";
    }
    public function getRegister(){
        return View('register');
    }
    public function postRegister(Request $request){
        $username = $request->username;
        $pass = bcrypt($request->password);
        $insert = zyz_user::insert(['username'=>$username,'password_hash'=>$pass,'auth_key'=>'test','access_token'=>'test','email'=>'test']);
        zyz_user::addAllToIndex();
    }
    public function postLogin(Request $request){
        $data =[
            'username'=>$request->username,
            'password'=>$request->password,
        ];
        /*$user = zyz_user::all();
        echo "<pre>";
        print_r($user);
        echo "</pre>";
        exit();*/
        $user = zyz_user::searchByQuery(['match'=>['username'=>$request->username]])->first();
        echo "<pre>";
        //print_r($data);
        echo "</pre>";
        if($request->remember ===trans('remember.Remember Me')){
            $remember = true;
        }
        else{
            $remember = false;
        }
        if(\Hash::check($request->password,$user->password_hash)){
            dd('Dang nhap thanh cong');
        }
        else{
            dd('Tai khoan hoac mat khau khong dung');
        }
        exit();







        if(Auth::guard('zyz_user')->attempt(array('username'=>$request->username,'password'=>$request->password))){
            dd('Dang nhap thanh cong');
        }
        else{
            dd('Tai khoan hoac mat khau khong dung');
        }
        exit();
    }
}
