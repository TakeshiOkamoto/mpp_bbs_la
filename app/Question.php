<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// 追加分
use App\Rules\NgWord;

class Question extends Model
{
    protected $guarded = array('id');   
      
    public static function Rules()
    {
      return [
          'title' => ['required','unique:questions','max:150', new NgWord('title')]
          ];
    }
}
