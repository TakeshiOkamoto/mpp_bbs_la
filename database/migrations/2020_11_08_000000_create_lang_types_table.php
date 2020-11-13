<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLangTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lang_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();     
            $table->string('keywords');  
            $table->text('description')->nullable();           
            $table->integer('sort');          
            $table->boolean('show')->nullable()->default(true);                         
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lang_types');
    }
}
