<?php

namespace App\Http\Controllers;

use App\zyz_object;
use Illuminate\Http\Request;

class ObjectController extends Controller
{
    //
    public function pushToES(){
        zyz_object::addAllToIndex();
    }
    public function deleteIndex(){
        zyz_object::deleteIndex();
    }
    public function getSearch(){
        return View('search');
    }
    function search($data){
        //Tim kiem multi fields
        //    'fields' => [ "title^5", "content"]Biểu tượng dấu mũ (^) cho phép Tìm kiếm đàn hồi biết rằng chúng tôi muốn trường tiêu đề đã thêm trọng số cho nó theo số theo sau nó.
        /*$rs = zyz_object::searchByQuery([
            'multi_match'=>[
                'query'=>$data,
                'fields'=>['object_title','object_excerpt']
            ],
        ]);*/
        //Tim kiem chinh xac tung chu cai
        /*$rs = zyz_object::searchByQuery([
            'match_phrase'=>[
                'object_title'=>$data
            ]
        ]);*/

        //Truy van tong hop
        /*$rs = zyz_object::searchByQuery([
            'bool'=>[
                'must'=>[
                    'multi_match'=>[
                      'query'=>$data,
                      'fields'=>['object_title^2','object_excerpt']
                    ],
                ],
                'should'=>[
                    'match'=>[
                        'object_title'=>[
                            'query'=>$data,
                            'type'=>'phrase'
                        ]
                    ]
                ],
                'must_not'=>[]
            ]
        ]);*/
        /*$rs = zyz_object::searchByQuery([
            'filtered' => [
                'filter' => [
                    'not' => [
                        'terms' => ['object_title' => ['impedit', 'voluptatem']]
                    ]
                ],
                'query' => [
                    'bool'=>[
                        'must'=>[
                            'multi_match'=>[
                                'query'=>$data,
                                'fields'=>['object_title^2','object_excerpt']
                            ],
                        ],
                        'should'=>[
                            'match'=>[
                                'object_title'=>[
                                    'query'=>$data,
                                    'type'=>'phrase'
                                ]
                            ]
                        ],
                        ]
                ],
            ],
        ]);*/
        /*                'sort'=>[
                    'object_date'=>'desc'
                ]*/
        $rs = zyz_object::searchByQuery([
            'multi_match'=>[
                'query'=>$data,
                'fields'=>['object_title'],
            ],
        ],null,null,null,null,['object_date'=>'desc']);
        return $rs;
    }
    public function getResult(Request $request){
        $data = $this->search($request->dataSearch);
        return View('result',compact('data'));
        //echo "<pre>";print_r($this->search($request->dataSearch));echo "</pre>";
    }
}
