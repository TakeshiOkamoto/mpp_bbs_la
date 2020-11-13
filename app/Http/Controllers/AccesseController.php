<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// 追加分
use Illuminate\Support\Facades\DB;

class AccesseController extends Controller
{
    public function index(Request $request)
    {       
        // LaravelのSQLはデフォルトで厳格モード(true)なのでfalseにする
        // ※これだと毎回、再接続するので必要であればconfig/database.php側でstrictにfalseを設定して下さい
        config(['database.connections.mysql.strict' => false]);
        DB::reconnect();
        
        // 前月、前年
        $month_ago = date("Y-m-d H:i:s",strtotime("-1 month"));
        $yyyy_ago =  date("Y-m-d H:i:s",strtotime("-1 year"));
      
        // 日毎(1か月分)
        $sql= "SELECT  yyyy,mm,dd,pv FROM accesses " .
              "  WHERE (STR_TO_DATE(yyyy,'%Y') + STR_TO_DATE(mm,'%m')+ STR_TO_DATE(dd,'%d')) >= ?" .
              "  ORDER BY yyyy DESC,mm DESC,dd DESC ";
                      
        $yyyymmdd = date('Y',strtotime($month_ago)) . 
                    date('m',strtotime($month_ago)) . 
                    date('d',strtotime($month_ago));
        $one_month_ago = DB::select($sql,[$yyyymmdd]);   

        // 各月(前年以降)
        $sql= "SELECT  yyyy,mm,TRUNCATE(AVG(pv),0) as pv FROM accesses " .
               "  WHERE yyyy >=? " .
               "  GROUP BY yyyy,mm " .
               "  ORDER BY yyyy DESC,mm DESC";        
               
        $yyyy = date('Y',strtotime($yyyy_ago));        
        $one_year_ago = DB::select($sql,[$yyyy]);   
        
        // 曜日の追加
        foreach ($one_month_ago as $item){
            $item->week =  date('w', strtotime($item->yyyy . '-' . $item->mm . '-' . $item->dd)); 
        }

        return view('accesses.index', ['one_month_ago' => $one_month_ago,
                                       'one_year_ago'  => $one_year_ago]);        
    }    
}
