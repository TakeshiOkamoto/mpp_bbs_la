<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// 追加分
use App\LangType;
use Illuminate\Support\Facades\DB;

class LangTypeController extends Controller
{
    public function index(Request $request)
    {   
        $items = LangType::whereRaw('1=1');
        $name = Controller::trim($request->name);
           
        if ($name != ""){
            $arr = explode(' ', $name);
            for ($i=0; $i<count($arr); $i++){
                $keyword = str_replace('%', '\%', $arr[$i]);            
                $items = $items->where('name', 'like', "%$keyword%");
            }
        }
        $items = $items->orderby('sort','ASC')->paginate(10); 
        
        return view('lang_types.index',['items' => $items, 'name'=> $name]);
    }

    public function create()
    {
        return view('lang_types.create');
    }

    public function store(Request $request)
    {
        // trim
        $param = [
            'name'        => Controller::trim($request->name),  
            'keywords'    => Controller::trim($request->keywords),  
            'description' => Controller::trim($request->description),  
        ];
        $request->merge($param);
              
        // バリデーション
        $request->validate(LangType::$rules);        
        
        // パラメータ
        $param = [
            'name'        => $request->name,  
            'keywords'    => $request->keywords,  
            'description' => $request->description,  
            'sort'        => $request->sort,  
            'show'        => isset($request->show)? "1" : "0"
        ];
        
        // トランザクション
        DB::transaction(function () use ($param) {
            $langtype = new LangType;
            $langtype->fill($param)->save(); 
        });

        // フラッシュ
        session()->flash('flash_flg', 1);
        session()->flash('flash_msg', '登録しました。');
        
        return redirect(url('lang_types'));
    }

    public function show($id)
    {
        $item = LangType::where('id' ,[$id])->get();
        if(count($item) === 1){
           return view('lang_types.show',['item' => $item[0]]);
        }else{
           return redirect(url('/'));
        }
    }

    public function edit($id)
    {
        $item = LangType::where('id' ,[$id])->get();
        if(count($item) === 1){
            return view('lang_types.edit',['item' => $item[0]]);
        }else{
            return redirect(url('/'));
        }
    }

    public function update(Request $request, $id)
    {
        // trim
        $param = [
            'name'        => Controller::trim($request->name),  
            'keywords'    => Controller::trim($request->keywords),  
            'description' => Controller::trim($request->description),  
        ];
        $request->merge($param); 
        
        // 自分自身のnameのユニークを確認しない
        $rules = LangType::$rules;
        $rules['name'] = 'required|max:50|unique:lang_types,name,' . $id . ',id';   

        // バリデーション     
        $request->validate($rules);  
               
        // パラメータ               
        $param = [
            'name'        => $request->name,  
            'keywords'    => $request->keywords,  
            'description' => $request->description,  
            'sort'        => $request->sort,  
            'show'        => isset($request->show)? "1" : "0"
        ];
                          
        // トランザクション      
        DB::transaction(function () use ($param, $id) {
            LangType::where('id' ,[$id])->update($param);
        });
        
        // フラッシュ
        session()->flash('flash_flg', 1);
        session()->flash('flash_msg', '更新しました。');
        
        return redirect(url('lang_types'));
    }

    public function destroy($id)
    {
        // トランザクション
        DB::transaction(function () use ($id) {
            LangType::where('id' ,[$id])->delete();
        });
        
        // フラッシュ
        session()->flash('flash_flg', 0);
        session()->flash('flash_msg', '削除しました。');
    }
}
