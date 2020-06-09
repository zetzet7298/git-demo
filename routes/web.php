<?php

use Illuminate\Support\Facades\Route;
use App\test;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/checkDB', function ()
{
    $user = App\test::get();
    print_r($user);
});
Route::post('loginUser','UserController@postLogin');
Route::get('loginUser','UserController@getLogin');
Route::post('registerUser','UserController@postRegister');
Route::get('registerUser','UserController@getRegister');
Route::get('saveToES','UserController@saveToES');
Route::get('removeFromES','UserController@removeFromES');
Route::get('learnESPHP','UserController@learnESPHP');
Route::get('mapping','UserController@mapping');
Route::get('pushToES','ObjectController@pushToES');
Route::get('search','ObjectController@getSearch');
Route::get('deleteIndex','ObjectController@deleteIndex');
Route::get('result/{dataSearch?}','ObjectController@getResult');
Route::get('rabbitmq','RabbitMQController@index');
Route::post('rabbitmq','RabbitMQController@test');
Route::get('receive','RabbitMQController@receive');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
