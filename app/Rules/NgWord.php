<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NgWord implements Rule
{

    public function __construct($column)
    {
        $this->column = $column;
    }

    public function passes($attribute, $value)
    {       
        // 禁止用語 
        // ※各自で追加して下さい
        $NG_WORDS =[
          "カジノ",
          "ギャンブル"
        ];        
        
        // 判定
        foreach($NG_WORDS as $word) {
            // 禁止用語が含まれていれば
            if(strpos($value, $word) !== false){
                return false;  
            }       
        }
        return true;  
    }

    public function message()
    {
       return trans('validation.attributes.' . $this->column) . 'に禁止用語が含まれています。';
    }
}
