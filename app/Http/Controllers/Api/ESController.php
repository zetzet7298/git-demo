<?php

namespace App\Http\Controllers\Api;

use App\Article2;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ESController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //print_r(App\Article2::getMapping());
       // exit();
        //
        //Bạn cũng có thể lập chỉ mục một bộ sưu tập các mô hình:
        $articles = Article2::where('id','<',200)-get();
        $articles->addToIndex();
        //Bạn cũng có thể lập chỉ mục các mục riêng lẻ:
        $articles = Article2::find(3);
        $articles->addToIndex();
        exit();
        //Bạn cũng có thể reindex toàn bộ mô hình:
        Article2::reindex();
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
