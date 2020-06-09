<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\vel_veldette;
use App\zyz_object;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class VeldetteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $veldettes = vel_veldette::where([['page_id','=',62],['status',1]])->orderBy('order','desc')->select('object_id')
            ->get();
        $arr = array();
        $keyNB = 'listIdNoiBat';
        foreach($veldettes as $item){
            Redis::sAdd($keyNB,$item->object_id);
            //array_push($arr,$item->object_id);
        }
        $dataIdNoiBat = Redis::smembers($keyNB);
        $items = zyz_object::whereIn('id',$dataIdNoiBat)->get()->toArray();
        foreach($items as $item=>$value){
            $keyLishNB = 'listNoiBat';
            $keyObject='idObjectNoiBat'.$value['id'];
            Redis::sAdd($keyLishNB,$value['id']);
            foreach($value as $key=>$value2){

                Redis::hset($keyObject,$key,$value2);
            }
        }
        $arr = array();
        foreach($dataIdNoiBat as $item){
            $data = Redis::hgetall('idObjectNoiBat'.$item);
            array_push($arr,$data);
        }
        return response()->json($arr);
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
        //echo $request->all();
        $insert = vel_veldette::create($request->all());
        $id = $insert->id;
        Redis::sAdd('listVeldette',$id);
        $keyAdd = 'idVeldette'.$id;
        foreach($request->all() as $key=>$value){
        echo $key ." ". $value;
        Redis::hSet($keyAdd,$key,$value);
        echo $id;
        }
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
        $key = 'idVeldette'.$id;
        print_r(Redis::hgetall("idVeldette13757"));exit();
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
        $vel = vel_veldette::findOrFail($id);
        $vel->delete();
        $key = 'idVeldette'.$id;
        Redis::del($key);
        return 204;
    }
}
