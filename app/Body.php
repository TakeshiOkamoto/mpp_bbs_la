<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Body extends Model
{
   protected $guarded = array('id');
   
   // タイムスタンプなし(created_at/updated_at)
   public $timestamps = false;
}
