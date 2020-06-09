<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\zyz_object;
use Illuminate\Support\Facades\Redis;

class ObjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //.0+
        $objects = zyz_object::where('object_status','=',1)->orderBy('object_date','desc')->paginate(20);
        /*echo "<pre>";
        print_r($objects);
        echo "</pre>";*/
        $keyObject = 'listObjects';
        foreach($objects as $item) {
            $key = 'idObject' . $item->id;
            Redis::hSet($key, 'id', $item->id);
            Redis::hSet($key, 'object_title', $item->object_title);
            Redis::hSet($key, 'object_excerpt', $item->object_excerpt);
            Redis::hSet($key, 'object_author', $item->object_author);
            Redis::hSet($key, 'object_content', $item->object_content);
            Redis::hSet($key, 'object_status', $item->object_status);
            Redis::hSet($key, 'object_comment_status', $item->object_comment_status);
            Redis::hSet($key, 'object_password', $item->object_password);
            Redis::hSet($key, 'object_name', $item->object_name);
            Redis::hSet($key, 'object_content_filltered', $item->object_content_filltered);
            Redis::hSet($key, 'object_parent', $item->object_parent);
            Redis::hSet($key, 'object_guid', $item->object_guid);
            Redis::hSet($key, 'object_type', $item->object_type);
            Redis::hSet($key, 'object_comment_count', $item->object_comment_count);
            Redis::hSet($key, 'object_slug', $item->object_slug);
            Redis::hSet($key, 'object_description', $item->object_description);
            Redis::hSet($key, 'object_keyword', $item->object_keyword);
            Redis::hSet($key, 'object_lang', $item->object_lang);
            Redis::hSet($key, 'object_authorname', $item->object_authorname);
            Redis::hSet($key, 'object_total_number_meta', $item->object_total_number_meta);
            Redis::hSet($key, 'object_total_number_resource', $item->object_total_number_resource);
            Redis::hSet($key, 'object_tags', $item->object_tags);
            Redis::hSet($key, 'object_view', $item->object_view);
            Redis::hSet($key, 'object_like', $item->object_like);
            Redis::hSet($key, 'object_dislike', $item->object_dislike);
            Redis::hSet($key, 'object_rating_score', $item->object_rating_score);
            Redis::hSet($key, 'object_rating_average', $item->object_rating_average);
            Redis::hSet($key, 'object_layout', $item->object_layout);
            Redis::hSet($key, 'created_at', $item->created_at);
            Redis::hSet($key, 'created_gmt', $item->created_gmt);
            Redis::hSet($key, 'updated_gmt', $item->updated_gmt);
            Redis::hSet($key, 'updated_at', $item->updated_at);
            Redis::hSet($key, 'object_date', $item->object_date);
            Redis::sAdd($keyObject, $item->id);
        }
        //$data = Redis::smembers($keyObject);
        $data = Redis::smembers('listObjects');
        foreach($data as $item){
             print_r(Redis::hgetall('idObject'.$item));
        }
        print_r( $data);
        //return response()->json($objects);
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
        Redis::sAdd('listObjects',$request->id);
        $key = 'idObject'.$request->id;
        Redis::hSet($key,$request->all());
        return zyz_object::create($request->all());

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
