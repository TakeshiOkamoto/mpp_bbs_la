# 掲示板システム
  
DEMO    
[https://www.petitmonte.com/dev/mpp_bbs_la/](https://www.petitmonte.com/dev/mpp_bbs_la/)  
  
[mpp_bbs_laの意味]  
mpp = My Practice Project  
bbs = 掲示板  
la = Laravel     
    
## 1. 環境
・Laravel 6系  
・MariaDB 10.2.2以上 (MySQL5.5以上でも可)  
 
## 2. インストール方法
  
### プロジェクトの生成  
```rb
cd 任意のディレクトリ
composer create-project --prefer-dist laravel/laravel 任意のプロジェクト名  "6.*"
```
### アプリ名とデータベースの設定
.env 
```rb
APP_NAME="掲示板システム"

DB_CONNECTION=mysql
DB_DATABASE=ココを設定
DB_USERNAME=ココを設定
DB_PASSWORD=ココを設定
```
### タイムゾーン/言語
config\app.php    

これを
```rb
'timezone' => 'UTC',
'locale' => 'en',
```
次のように変更する
```rb
'timezone' => 'Asia/Tokyo',
'locale' => 'ja',
```
### ミドルウェアの設定
app\Http\Kernel.php  
次の1行を$routeMiddlewareの最後に追加する
```rb
protected $routeMiddleware = [

  'login' =>\App\Http\Middleware\LoginMiddleware::class,
]
```
### 不要なルーティングの削除
routes\api.php  
以下をコメントにする
```rb
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
```
### 不要なマイグレーションファイルを手動で削除する
database\migrations
```rb
2014_10_12_000000_create_users_table.php
2014_10_12_100000_create_password_resets_table.php
2019_08_19_000000_create_failed_jobs_table.php
```
### マイグレーション
```rb
php artisan migrate
```
### 管理者アカウントの作成
```rb
php artisan tinker
```
```rb
$param = ['name' => 'ユーザー名',
          'email' => 'admin@example.com',
          'password' => Hash::make('12345678')
         ];
   
DB::table('users')->insert($param);

exit;
```
### 実行する
```rb
php artisan serve
```
メイン    
[http://localhost:8000/](http://localhost:8000/)   
ログイン      
[http://localhost:8000/admin_login](http://localhost:8000/admin_login)  

## 3. Laravelプロジェクトの各種初期設定
その他は次の記事を参照してください。  
  
[Laravelプロジェクトの各種初期設定](https://www.petitmonte.com/php/laravel_project.html)  

## 同梱ファイルのライセンス
Bootstrap v4.3.1 (https://getbootstrap.com/)  
```rb
Copyright 2011-2019 The Bootstrap Authors  
Copyright 2011-2019 Twitter, Inc.
```



