<?php

namespace App\Jobs;

use App\article\Article;
use App\article\Article_Term;
use App\search\ArticleSearch;
use App\term\Term;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Redis;
class CreateArticle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $article;
    public $article_term = array();
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Article $article,array $article_term)
    {
        //
        $this->article = $article;
        $this->article_term = $article_term;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        /*$this->article->addToIndex();
        $arr = $this->article->toArray();
        foreach($arr as $key=>$value){
            Redis::hset('article'.$this->article['id'],$key,$value);
        }*/

            $article = $this->article;
            $arr = $this->article->toArray();
            $id = $arr['id'];
            $article= Article::where('zyz_object.id',$id)->first()->toArray();
            $arr_article = array();
            $arr_term = array();
            $arr_character = array();
            foreach ($article as $key=>$value){
                $arr_article[$key] = $value;
            }
            $arr_article['term'] = $this->article_term;
            $arr_article['character'] = $arr_character;
            $ar = new ArticleSearch();
            $desiredResult = $ar->newInstance($arr_article, true);
            $desiredResult->addToIndex();
            //Redis::lPush('zyz_article',$key);
            Redis::zAdd('zyz_article',$id);
            foreach($arr_article as $key1=>$value1){
                Redis::hSet('article'.$id,$key1,$value1);
            }
            foreach ($arr_article['term'] as $term){
                Redis::lPush('article_term'.$id,$term);
            }
    }
}
