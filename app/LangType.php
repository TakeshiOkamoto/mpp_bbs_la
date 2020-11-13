<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LangType extends Model
{
   protected $guarded = array('id');

   public static $rules = [
      'name' => 'required|max:50|unique:lang_types',
      'keywords' => 'required|max:100',
      'description' => 'max:512',      
      'sort' => 'required|integer|min:0|max:1000'
   ];
}
