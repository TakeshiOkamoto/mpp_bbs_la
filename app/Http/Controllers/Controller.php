<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

// 追加分
use App\Access;
use App\Answer;
use App\Body;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    // 全角 => 半角変換 + trim
    public static function trim($str){
        if (isset($str)){
            // a 全角英数字を半角へ
            // s 全角スペースを半角へ
            return trim(mb_convert_kana($str, 'as'));
        }else{
            return "";
        }
    }    
    
    // URLをリンクに変換する(簡易的)
    public static function auto_link($text){      
        $result = $text;
        
        if(isset($result)){
                          
            // URLパターン(予約文字 + 非予約文字 + %)
            //
            // <参考>
            // https://www.asahi-net.or.jp/~ax2s-kmtn/ref/uric.html
            // https://www.petitmonte.com/php/regular_expression_matome.html
            // 
            // 次のvalidateUrl()も参考になる ※先頭の^ と末尾の$を削除して使用する
            // laravel\framework\src\Illuminate\Validation\Concerns/ValidatesAttributes.php 
            $pattern ='/(http|https):\/\/[!#$%&\'()*+,\/:;=?@\[\]0-9A-Za-z-._~]+/';
            
            // URLをaタグに変換する
            $result = preg_replace_callback($pattern, function ($matches) {   
                        return '<a href="' . $matches[0] . '">'. $matches[0] . '</a>';
                      }, $result);
        }
        return $result;            
    }
          
    // 文字列をHTML(RAW)に変換する
    public static function html($text){       
        $result = "";
        
        if(isset($text)){
            // エスケープ
            $result = htmlspecialchars($text);            
            // 半角スペース 
            $result = str_replace(" ", '&nbsp;', $result);  
            // タブ 
            $result = str_replace("	", '&nbsp;&nbsp;', $result);  
            // 改行 
            $result = str_replace("\r\n", '<br>', $result);  
            $result = str_replace("\r",   '<br>', $result);  
            $result = str_replace("\n",   '<br>', $result);  
            // URLをaタグに変換する
            // ※既知の問題点 ---> 最初から<a href=""></a>のタグがある場合はその自動リンクが不自然となる             
            $result = Controller::auto_link($result);
        }
        return $result;
    }
    
    // (検索用)本文テーブルの更新
    public static function body_table_update($question_id, $isDeleteOnly){      
        
        // 本文を削除
        $body_item = Body::where('question_id', $question_id)->get(); 
        if (count($body_item) === 1){
            Body::where('question_id' ,[$question_id])->delete();
        }        
     
        // 本文を最新の状態にする                   
        if(!$isDeleteOnly){
          
            $answers = Answer::where('question_id', $question_id)->get();
            $matome = "";
            
            foreach($answers as $answer){
                $matome = $matome . ' ' . $answer->body;
            }
            
            // Body
            $param = [
                'question_id' => $question_id,
                'matome'      => $matome  
            ];
            
            $body = new Body;
            $body->fill($param)->save(); 
        }           
    }
        
    // アクセスカウンター(全体)
    public static function access_counter(){
        $yyyy = date("Y");
        $mm   = date("m");
        $dd   = date("d");
        
        $item = Access::whereRaw('yyyy=? and mm=? and dd=?', [$yyyy, $mm, $dd])->get();

        // 新規
        if(count($item) === 0){
            $param = [
                'yyyy' => $yyyy,
                'mm'   => $mm,
                'dd'   => $dd,
                'pv'   => 1
            ];
            
            DB::transaction(function () use ($param) {
                $access = new Access;
                $access->fill($param)->save();             
            });
            
        // 更新    
        }else{
            DB::transaction(function () use ($yyyy, $mm, $dd) {
                Access::whereRaw('yyyy=? and mm=? and dd=?' ,[$yyyy,$mm, $dd])->increment('pv');         
            });
        }
    }       
}
