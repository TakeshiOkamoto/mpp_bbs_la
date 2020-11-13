<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// 追加分
use App\Rules\NgWord;

class Answer extends Model
{
   protected $guarded = array('id');   
     
   public static function Rules()
   {
      return [
          'name'  => ['required','max:50', new NgWord('name')],
          'url'   => 'nullable|url|max:250', 
          'body'  => ['required', new NgWord('body')]
          ];
   }
}
