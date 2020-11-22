<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// 追加分
use App\LangType;
use App\Question;
use App\Answer;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
  
    public function index(Request $request)
    {
        if (isset($request->id)){
            $lang_types_item = LangType::where('id', $request->id)->get();
        }else{
            $lang_types_item = LangType::where('id', $request->lang_id)->get();
        }
 
        if (count($lang_types_item) === 0){
            return redirect(url('/'));
        }
        
        $title = Controller::trim($request->title);
        $body  = Controller::trim($request->body);         
        
        // LEFT JOIN(bodies)、WHERE(言語)、SELECT(選択)
        $items = Question::leftJoin('bodies','questions.id', '=' ,'bodies.question_id')
                   ->where('lang_type_id', $request->lang_id)
                   ->select('questions.*', 'bodies.matome');
               
        // WHERE(タイトル)
        if ($title != ""){
            $arr = explode(' ', $title);
              for ($i=0; $i<count($arr); $i++){
                $keyword = str_replace('%', '\%', $arr[$i]);            
                $items = $items->where('title', 'like', "%$keyword%");
              }
        }
        
        // WHERE(本文)
        if ($body != ""){
            $arr = explode(' ', $body);
            for ($i=0; $i<count($arr); $i++){
                $keyword = str_replace('%', '\%', $arr[$i]);
                $items = $items->where('matome', 'like', "%$keyword%");
            }
        }    

        //  降順ソート
        $items = $items->orderby('updated_at', 'DESC')->paginate(25);
        
        // 質問者、最終発言者、件数の配列を取得する
        $sql =" SELECT " .
              "  questions.id," .
              "  (SELECT answers.name FROM answers WHERE answers.question_id = questions.id  ORDER BY answers.id ASC LIMIT 1) as name1," .
              "  (SELECT answers.name FROM answers WHERE answers.question_id = questions.id  ORDER BY answers.id DESC LIMIT 1) as name2," .
              "  (SELECT count(id) FROM answers WHERE answers.question_id = questions.id) as cnt " .
              " FROM questions  " .
              " WHERE " .
              "  questions.lang_type_id = ?" .
              " ORDER BY" .                                    
              "  questions.updated_at DESC";
              
        // SQLの発行   
        $db_data = DB::select($sql, [$request->lang_id]);    
        
        // アクセスカウンター(全体)
        Controller::access_counter();  
        
        return view('questions.index', ['items'     => $items,
                                        'db_data'   => $db_data,
                                        'lang_type' => $lang_types_item[0],    
                                                                            
                                        // 検索用
                                        'lang_id'   => $lang_types_item[0]->id,
                                        'title'     => $title,
                                        'body'      => $body                                                                                
                                       ]);
    }
      
    public function create(Request $request)
    { 
        $item = LangType::where('id', $request->lang_id)->get();
        if (count($item) === 0){
            return redirect(url('/'));
        }      
        return view('questions.create',['lang_id'=> $item[0]->id, 'lang_name'=> $item[0]->name]);
    }
    
    public function store(Request $request)
    {
        // trim
        $param = [
            'title' => Controller::trim($request->title),  
            'name'  => Controller::trim($request->name),  
            'url'   => Controller::trim($request->url),  
            'body'  => Controller::trim($request->body),                                  
        ];        
        $request->merge($param);
        
        // バリデーション
        $request->validate(array_merge(Question::Rules(), Answer::Rules()));    
        
        // トランザクション
        DB::transaction(function () use ($request) {
                   
            // Question
            $param = [
                'lang_type_id' => $request->lang_id,  
                'title'        => $request->title,  
                'resolved'     => 0,  
                'pv'           => 0  
            ];
                
            $question = new Question;
            $question->fill($param)->save(); 
            
            // Answer
            $param = [
                'question_id' => $question->id,  
                'name'        => $request->name,  
                'url'         => $request->url,  
                'body'        => $request->body,                                  
                'ip'          => $request->ip()
            ];
                
            $answer = new Answer;
            $answer->fill($param)->save(); 
            
            // (検索用)本文テーブルの更新
            Controller::body_table_update($question->id, false);                
        });
        
        // フラッシュ
        session()->flash('flash_flg', 1);
        session()->flash('flash_msg', '登録しました。');        
        
        return redirect(url('questions?lang_id=' . $request->lang_id));
    }    
    
    public function destroy($id)
    {
        // トランザクション
        DB::transaction(function () use ($id) {
            
            // Answer
            $answers = Answer::where('question_id', $id)->get();            
            foreach($answers as $answer){
                 Answer::where('id', [$answer->id])->delete();
            }
            
            // Question
            Question::where('id', [$id])->delete();                        
            
            // (検索用)本文テーブルの削除
            Controller::body_table_update($id, true);              
        });
        
        // フラッシュ
        session()->flash('flash_flg', 0);
        session()->flash('flash_msg', '削除しました。');       
    }    
}
