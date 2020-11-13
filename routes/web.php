<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// トップ
Route::get('/', 'RootController@index');

// 質問
Route::get('questions', 'QuestionController@index');
Route::get('questions/create', 'QuestionController@create');
Route::post('questions', 'QuestionController@store');
Route::delete('questions/{question}', 'QuestionController@destroy')->middleware('login');

// 回答
Route::get('answers', 'AnswerController@index');
Route::post('answers', 'AnswerController@store');
Route::get('answers/{answer}/edit', 'AnswerController@edit')->middleware('login');
Route::put('answers/{answer}', 'AnswerController@update')->middleware('login');
Route::delete('answers/{answer}', 'AnswerController@destroy')->middleware('login');

// アクセス解析
Route::get('/accesses', 'AccesseController@index')->middleware('login');

// カテゴリ
Route::resource('lang_types', 'LangTypeController')->middleware('login');

// ログイン
Route::get('/admin_login', function () {
    return view('login');
});
Route::POST('/admin_login', 'LoginController@login');
Route::get('/admin_logout', 'LoginController@logout')->middleware('login');
