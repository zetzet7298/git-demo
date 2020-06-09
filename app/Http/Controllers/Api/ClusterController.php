<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\vel_cluster;
use App\zyz_user;
use DB;
use App\User;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Elasticsearch\ClientBuilder;
class ClusterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = zyz_user::insert(['username'=>'admin','password_hash'=>bcrypt('admin'),'auth_key'=>'test','access_token'=>'test','email'=>'test']);
        //
        /*Redis::hSet('connguoi','tay','dai');
        $value = Redis::hGet('connguoi','tay');
        Redis::del('connguoi','tay');
        Redis::hmset('connguoi',[
           'chan'=>'to',
            'mat'=>'to',
            'mui'=>'cao',
            'tuoi'=>'20'
        ]);
        Redis::hIncrBy('connguoi','tuoi',15);
        $data = Redis::hGetAll('connguoi');
        //print_r($data);
        $key ='countries';
        //Redis::sAdd($key,';china;');
        Redis::sAdd($key,array('china','Japan'));
        Redis::sAdd($key,array('china','Japan'));
        Redis::sAdd($key,array('china','Japan'));
        Redis::sAdd($key,array('china','Japan'));
        //Redis::sAdd($key,'china');
        //Redis::sAdd($key,['china','Japan']);
        //Redis::smembers($key);
        $data2 = Redis::smembers('countries');
        print_r($data2);
        exit();
        $key = 'testzadd';
        Redis::zAdd($key,1,'value2');
        Redis::zAdd($key,2,'value3');
        Redis::zAdd($key,3,'value1');
        Redis::zAdd($key,4,'value5');
        echo Redis::sCard($key)."<br>";
        $data = Redis::zRange($key,0,10,'WITHSCORES');
        print_r($data)."<br>";
        $data = Redis::zRangeByScore($key,'1','3');
        print_r($data);
        exit();
        $clusters = vel_cluster::get();
        return response()->json($get);*/
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
