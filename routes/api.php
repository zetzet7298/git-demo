<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Article2;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix'=>'/article'],function(){
    Route::get('push','Api\ArticleController@push');
    Route::get('pagination','Api\ArticleController@pagination');
    Route::get('search','Api\ArticleController@search');
    Route::get('test','Api\ArticleController@test');
});
Route::resource('article','Api\ArticleController');

Route::group(['prefix'=>'character'],function(){
    Route::resource('character','Api\CharacterController');
});
Route::group(['prefix'=>'management'],function(){
    Route::resource('management','Api\AppManagementController');
});






Route::resource('objects','Api\ObjectController');
Route::resource('clusters','Api\ClusterController');
Route::resource('home/features','Api\VeldetteController');
Route::get('search-es', function () {
    Article2::addAllToIndex();
});
Route::resource('es','Api\ESController');
Route::resource('user','Api\User');
Route::get('/',function (){
   Article2::createIndex($shards = null,$replicas = null);
   Article2::putMapping($ignoreConflicts = true);
   Article2::addAllToIndex();
   return View('welcome');
});
Route::get('/search', function() {

    $articles = Article2::searchByQuery(['match' => ['title' => 'Sed']]);

   // return $articles;
    //Chúng ta có thể nhận được tổng số lượt truy cập để đếm bằng cách sử dụng mã sau đây.
    $articles = Article2::searchByQuery(['match'=>['title'=>'Sed']]);
    //$return = $articles->totalHits();
    //$return = $articles->getShards();//
    //$return = $articles->maxScore();//Truy cap so diem toi da
    //$return = $articles->timedOut(); //Truy cap thuoc tinh boolean da het thoi gian
    //$return = $articles->took(); //Truy cap tai san da lay
    $return = $articles->getAggregations(); //Truy cập tập hợp tìm kiếm - Xem Tổng hợp để biết chi tiết :
    print_r($return);
    /*Chọn kết quả từ Elastiquent.
    Để có kết quả trong các khối, bạn có thể sử dụng hàm chunk () .*/
    $articles = Article2::searchByQuery(['match'=>['title'=>'Sed']]);
    return $articles->chunk(2);
});
