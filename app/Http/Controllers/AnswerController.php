<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// 追加分
use App\LangType;
use App\Question;
use App\Answer;
use Illuminate\Support\Facades\DB;

class AnswerController extends Controller
{
    // index/store共通
    public function common(Request $request)
    {
        // 質問
        $questions_item = Question::where('id', $request->question_id)->get(); 
        if (count($questions_item) === 0){
            return redirect(url('/'));
        }
        
        // 言語
        $lang_types_item = LangType::where('id', $questions_item[0]->lang_type_id)->get();
        if (count($lang_types_item) === 0){
            return redirect(url('/'));
        }
        
        // 回答
        $items = Answer::where('question_id', $request->question_id)->orderby('created_at', 'ASC')->get();
        
        // タイトル毎の閲覧数の更新
        $param =[
            'pv'         => ($questions_item[0]->pv + 1),
            'updated_at' =>  $questions_item[0]->updated_at  // 自動で更新させない
        ];
        DB::transaction(function () use ($param, $request) {
            Question::where('id' ,[$request->question_id])->update($param);
        });
               
        // アクセスカウンター(全体)
        Controller::access_counter();
        
        // 文字列をHTML(RAW)に変換する
        for($i=0;$i<count($items);$i++){
            $items[$i]->body = Controller::html($items[$i]->body);  
        }
              
        return view('answers.index', ['lang_id'   => $lang_types_item[0]->id,
                                      'lang_name' => $lang_types_item[0]->name,
                                      'question'  => $questions_item[0],                                      
                                      'items'     => $items
                                       ]);
    }
      
    public function index(Request $request)
    {   
        // データ一覧      
        $view = $this->common($request);
        return  $view; 
    }
        
    public function store(Request $request)
    {
        // データ一覧
        $view = $this->common($request);
        
        // バリデーション
        $request->validate(Answer::Rules());    
        
        // トランザクション
        DB::transaction(function () use ($request) {
                   
            // Answer
            $param = [
                'question_id' => $request->question_id,
                'name'        => Controller::trim($request->name),  
                'url'         => Controller::trim($request->url),                  
                'body'        => Controller::trim($request->body),  
                'ip'          => $request->ip()
            ];
                
            $answer = new Answer;
            $answer->fill($param)->save(); 
            
            // Question
            if (isset($request->resolved)){
                // ※ユーザー側の処理では一度、解決にしたらそのままとする
                Question::where('id', $request->question_id)->update(['resolved' => 1]);
            }            
            Question::where('id', $request->question_id)->update(['updated_at' => $answer->updated_at]); // 最終更新日時
            
            // (検索用)本文テーブルの更新
            Controller::body_table_update($request->question_id, false);            
        });
        
        // フラッシュ
        session()->flash('flash_flg', 1);
        session()->flash('flash_msg', '返信しました。');        
        
        return redirect(url('answers?question_id=' . $request->question_id));
    }
    
    public function edit($id)
    {      
        // 回答
        $item = Answer::where('id', $id)->get();
        if (count($item) === 0){
            return redirect(url('/'));
        }
        
        // 質問
        $questions_item = Question::where('id', $item[0]->question_id)->get(); 
        if (count($questions_item) === 0){
            return redirect(url('/'));
        }

        return view('answers.edit',['item'      => $item[0],
                                    'question'  => $questions_item[0] 
                                   ]);
    }

    public function update(Request $request, $id)
    {
        // バリデーション
        $request->validate(Answer::Rules());  
        
        // 更新前の情報         
        $item = Answer::where('id', $id)->get();
        if (count($item) === 0){
            return redirect(url('/'));
        }
                                  
        // トランザクション      
        DB::transaction(function () use ($request, $id, $item) {        

            // パラメータ               
            $param = [
               'name'        => Controller::trim($request->name),  
               'url'         => Controller::trim($request->url),                  
               'body'        => Controller::trim($request->body),  
               'updated_at' =>  $item[0]->updated_at  // 自動で更新させない               
            ];
              
            // Answer                    
            Answer::where('id' ,[$id])->update($param);
            
            // Question
            // ※管理側の処理では解決、未解決を再設定可能とする
            $question = Question::where('id', $item[0]->question_id)->get();
            $param = [
                'resolved'   => isset($request->resolved)? 1 : 0,
                'updated_at' => $question[0]->updated_at  // 自動で更新させない
            ];

            Question::where('id', $item[0]->question_id)->update($param);
            
            // (検索用)本文テーブルの更新
            Controller::body_table_update($item[0]->question_id, false);              
        });
                
        // フラッシュ
        session()->flash('flash_flg', 1);
        session()->flash('flash_msg', '更新しました。');
        
        return redirect(url('answers?question_id=' . $item[0]->question_id));
    }

    public function destroy($id)
    {
        // トランザクション
        DB::transaction(function () use ($id) {
            
            // 削除前の情報
            $answer = Answer::where('id' ,[$id])->get();
            
            // 削除
            Answer::where('id', [$id])->delete();
            
            // 質問テーブルの最終更新日の再設定
            $answers = Answer::where('question_id' ,$answer[0]->question_id)->orderby('updated_at','DESC')->get();
            if (count($answers) != 0){
                $param = [
                    'updated_at' => $answers[0]->updated_at  
                ];
                Question::where('id', $answer[0]->question_id)->update($param);
            }            
            
            // (検索用)本文テーブルの更新
            Controller::body_table_update($answer[0]->question_id, false);              
        });
        
        // フラッシュ
        session()->flash('flash_flg', 0);
        session()->flash('flash_msg', '削除しました。');    
    }    
}
