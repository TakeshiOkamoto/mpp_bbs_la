<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// 追加分
use App\LangType;
use Illuminate\Support\Facades\DB;

class RootController extends Controller
{
    public function index(Request $request)
    {       
        // LaravelのSQLはデフォルトで厳格モード(true)なのでfalseにする
        // ※これだと毎回、再接続するので必要であればconfig/database.php側でstrictにfalseを設定して下さい
        config(['database.connections.mysql.strict' => false]);
        DB::reconnect();
       
        $items = LangType::orderby('sort','ASC')->get();
               
        // 質問数 コメント数、未返信数、解決数 閲覧数
        $sql = "SELECT ".
               "  (SELECT COUNT(id) FROM questions WHERE Z.id = questions.lang_type_id) AS A," .
               "   " .
                        
               "(SELECT COUNT(answers.id) FROM answers " .
               "   LEFT JOIN questions ON answers.question_id = questions.id " .
               "   WHERE Z.id = questions.lang_type_id) AS B, " .
     
               // 未返信数 ※返信がないもの =  1件のみ 
               "  (SELECT" .
               "     COUNT(cnt) " .
               "   FROM " .
               "     ( " .
               "      SELECT " .
               "        count(question_id) as cnt,questions.lang_type_id " .
               "      FROM " .
               "        answers " .
               "      INNER JOIN " .
               "        questions ON answers.question_id = questions.id " .
               "      GROUP BY question_id   " .
               "      HAVING  " .
               "       cnt =1  " .
               "      ) AS X " .
               "    WHERE " .
               "     X.lang_type_id = Z.id) AS C, " .
               " " .
               "   (SELECT IFNULL(SUM(CASE WHEN resolved=0 THEN 0 ELSE 1 END),0) FROM questions WHERE Z.id = questions.lang_type_id) AS D, " .
               "   (SELECT IFNULL(SUM(pv),0) FROM questions WHERE Z.id = questions.lang_type_id) AS E " .
               "FROM  " .
               "  lang_types as Z " .
               "ORDER BY ".
               "  Z.sort ASC";
               
        // SQLの発行       
        $counts = DB::select($sql);          

        // アクセスカウンター(全体)
        Controller::access_counter();       
           
        return view('index', ['items' => $items , 'counts' => $counts]);
    }    
}
