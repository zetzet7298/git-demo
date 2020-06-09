<?php

namespace App\Http\Controllers\Api;

use App\article\Article_Term;
use App\Http\Controllers\Controller;
use App\Jobs\CreateArticle;
use App\search\ArticleSearch;
use App\term\Term;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\article\Article;
use Illuminate\Support\Facades\Redis;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function push(){
        /*Article::addAllToIndex();
        Article_Term::addAllToIndex();*/
        $articles = Article::all();
        $list_article = array();
        foreach ($articles as $item){
            $id = $item->id;
            $data = Article::where('zyz_object.id',$id)
                ->join('zyz_object_character as c','zyz_object.id','=','c.object_id')
                ->join('zyz_object_term as t','zyz_object.id','=','t.object_id')
                ->join('zyz_term as term','term.id','=','t.term_id')
                ->join('zyz_character as cha','cha.id','=','c.character_id')
                ->select(['term.name','cha.character_name'])
                ->get();
            $article= Article::where('zyz_object.id',$id)->first()->toArray();
            $arr_article = array();
            $arr_term = array();
            $arr_character = array();
            foreach ($data as $item){
                array_push($arr_term,$item->name);
                array_push($arr_character,$item->character_name);
            }
            foreach ($article as $key=>$value){
                $arr_article[$key] = $value;
            }
            $arr_article['term'] = $arr_term;
            $arr_article['character'] = $arr_character;
            $list_article[] = $arr_article;
        }
        foreach($list_article as $key=>$value){
            $ar = new ArticleSearch();
            $desiredResult = $ar->newInstance($value, true);
            $desiredResult->addToIndex();
            //Redis::lPush('zyz_article',$key);
            Redis::zAdd('zyz_article',$value['id']);
            foreach($value as $key1=>$value1){
                Redis::hSet('article'.$value['id'],$key1,$value1);
            }
            foreach ($value['term'] as $term){
                Redis::lPush('article_term'.$value['id'],$term);
            }
        }
        return count($list_article);
        //return response()->json($list_article);
        /*$connection = new AMQPStreamConnection('rabbitmq',5672,'zet','qwe123');
        $channel = $connection->channel();
        $channel->queue_declare('article',false,false,false,false);
        Article::addAllToIndex();
        $articles = Article::all()->toArray();
        $i = 0;
        foreach($articles as $key=>$value){
            //Redis::lPush('zyz_article',$key);
            $i++;
            Redis::zAdd('zyz_article',$value['id']);
            foreach($value as $key1=>$value1){
                Redis::hSet('article'.$value['id'],$key1,$value1);
            }
        }
        return $i;*/
    }
    public function pagination(){
        $page = $_GET['page'];
        $size = $_GET['size'];
        $articles = Redis::zRange('zyz_article',$size*$page,$size*$page+$size);
        $data= array();
        foreach($articles as $value){
           $data[] = Redis::hGetAll('article'.$value);
        }
        return response()->json(['current_page'=>$page,'data'=>$data]);
        //$num = $this->NumPage($size);
    }
    public function NumPage($size){
        $count = Redis::zCard('zyz_article');
        $links = array();
        $i = 1;
        while($count>0){
            $links[] = $i;
            $count-=$size;
            $i++;
        }
        return $links;
    }
    public function index()
    {
        //
        try{
            $articles=array();
            //$len = Redis::llen('zyz_article');
            $datas = Redis::zRangeByScore('zyz_article','-inf','+inf');
            foreach($datas as $item){
                $articles[] = Redis::hgetall('article'.$item);
            }
            //$articles = Article::paginate(10);
            return response()->json($articles);
        }catch(ModelNotFoundException $e){
            echo $e->getMessage();
        }
    }
    public function search(){
        $dataSearch = $_GET['dataSearch'];
        $articles = Article::searchByQuery([
            'multi_match'=>[
                'query'=>$dataSearch,
                'fields'=>['object_title','object_excerpt']
            ]
        ]);
        return response()->json($articles);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function test(){
        $i = 1;
        while (true) {
            file_put_contents('message.log', 'Hello '.++$i.PHP_EOL, FILE_APPEND);

            sleep(3);
        }
        /*
        $connection = new AMQPStreamConnection('rabbitmq',5672,'zet','qwe123');
        $channel = $connection->channel();
        $channel->queue_declare('article',false,false,false,false);
        $callback = function($msg){
            echo "Success". " ".$msg->body;
            $id = json_decode($msg->body,true);
            $article = Article::find($id);
            $article->addToIndex();
            $arr = $article->toArray();
            foreach($arr as $key=>$value){
                Redis::hset('article'.$article->id,$key,$value);
            }
            $response['success'] = 'Created';
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };
        $channel->basic_qos(null,1,null);
        $channel->basic_consume(
            $queue = 'article',
            $consumer_tag = '',
            $no_local = false,
            $no_ack = false,
            $exclusive = false,
            $nowait = false,
            $callback
        );
        $timeout =0.1;
        while(count($channel->callbacks)){
            try{
                $channel->wait();
            }catch(\PhpAmqpLib\Exception\AMQPTimeoutException $e){
                $channel->close();
                $connection->close();
                exit;
            }
        }
        $channel->close();
        $connection->close();*/
    }
    public function store(Request $request)
    {
        //print_r($arr_article);exit();
        //$terms = Term::whereIn('idd',$arr_term)->get();
        //return json_encode($arr_article);
        //
        $rules=[
            'object_title'=>'required',
            'object_excerpt'=>'required',
            'object_status'=>'required',
            'object_comment_status'=>'required',
            'object_password'=>'max:20',
            'object_name'=>'required|max:255',
            'object_parent'=>'required',
            'object_guid'=>'required|max:255',
            'object_type'=>'required|max:20',
            'object_comment_count'=>'required',
            'object_slug'=>'required|max:255',
            'object_author_name'=>'max:255',
            'object_total_number_meta'=>'required',
            'object_total_number_resource'=>'required',
            'object_view'=>'required',
            'object_like'=>'required',
            'object_dislike'=>'required',
            'object_rating_score'=>'required',
        ];
        $response = array('response'=>'','success'=>false);
        $validator = \Validator::make($request->all(),$rules);
        //echo "<pre>"; print_r($request->all());echo "<'/pre>";exit();
        if($validator->fails()){
            $response['response'] = $validator->messages();
        }else{
            $article = Article::create($request->all());
            if($article){
                $response['success'] = 'Created';
                foreach ($request->term as $item){
                    Article_Term::insert(['object_id'=>$article->id,'term_id'=>$item,'data'=>0]);
                }
                $this->dispatch(new CreateArticle($article,$request->term));
                /*$connection = new AMQPStreamConnection('rabbitmq',5672,'zet','qwe123');
                $channel = $connection->channel();
                //$channel->queue_declare('article',false,false,false,false);
                $data = json_encode($article->id);
                $msg = new AMQPMessage($data,array('delivery_mode'=>2));
                $channel->basic_publish($msg,'','article');
                $channel->close();
                $connection->close();
                $response['success'] = 'Created';
                $callback = function($msg){
                    $id = json_decode($msg->body,true);
                    $article = Article::find($id);
                    $article->addToIndex();
                    $arr = $article->toArray();
                    foreach($arr as $key=>$value){
                        Redis::hset('article'.$article->id,$key,$value);
                    }
                    $response['success'] = 'Created';
                    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
                };
                $channel->basic_qos(null,1,null);
                $channel->basic_consume(
                    $queue = 'article',
                    $consumer_tag = '',
                    $no_local = false,
                    $no_ack = false,
                    $exclusive = false,
                    $nowait = false,
                    $callback
                );
                exit();
                while(count($channel->callbacks)){
                    $channel->wait();
                }
                $channel->close();
                $connection->close();*/
            }
        }
        return $response;
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
        //$article = Article::findOrFail($id);
        $article = ArticleSearch::searchByQuery(['match'=>['id'=>$id]]);

        /*$article = Redis::hgetall('article'.$id);
        $article['term'] = Redis::lRange('article_term'.$id,0,-1);*/
        return response()->json($article);
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
        $rules=[
            'object_title'=>'required',
            'object_excerpt'=>'required',
            'object_status'=>'required',
            'object_comment_status'=>'required',
            'object_password'=>'max:20',
            'object_name'=>'required|max:255',
            'object_parent'=>'required',
            'object_guid'=>'required|max:255',
            'object_type'=>'required|max:20',
            'object_comment_count'=>'required',
            'object_slug'=>'required|max:255',
            'object_author_name'=>'max:255',
            'object_total_number_meta'=>'required',
            'object_total_number_resource'=>'required',
            'object_view'=>'required',
            'object_like'=>'required',
            'object_dislike'=>'required',
            'object_rating_score'=>'required',
        ];
        $response =array('response'=>'','success'=>'false');
        $validator = \Validator::make($request->all(),$rules);
        if($validator->fails()){
            $response['response']=$validator->messages();
        }
        else{
            $article = Article::findOrFail($id);
            $article->fill($request->all());
            if($article->save()){
                $article->addToIndex();
                Redis::del('article'.$article->id);
                $arr = $article->toArray();
                foreach($arr as $key=>$value){
                    Redis::hset('article'.$article->id,$key,$value);
                }
                $response['success'] = 'Updated';
            }
        }
        return $response;
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
        $article = Article::findOrFail($id);
        //$article->timestamps('deleted_at')->default(strtotime(Carbon::now()));
        if($article->delete()){
            $query = array('match' => array('ID' => $id));
            $article->removeFromIndex();
            Redis::zRem('article',$id);
            Redis::del('article'.$id);
            return response()->json("Deleted");
        }
    }
}
