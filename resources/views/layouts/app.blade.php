<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="UTF-8">
<title>@yield('title')</title>
<meta name="keywords" content="@yield('keywords')">
<meta name="description" content="@yield('description')">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" media="all" href="{{url('css/bootstrap.min.css')}}">
<link rel="stylesheet" media="all" href="{{url('css/terminal.css')}}">
<script src="{{url('js/common.js')}}"></script>
</head>
<body>

{{-- ヘッダ --}}
<nav class="navbar navbar-expand-md navbar-light bg-primary">
  <div class="navbar-brand text-white">
    {{ config('app.name') }}
  </div>
  @if (session()->has('name'))
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" style="color:#fff;" href="{{ url('/') }}">ホーム</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" style="color:#fff;" href="{{ url('accesses') }}">アクセス解析</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" style="color:#fff;" href="{{ url('lang_types') }}">カテゴリ</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" style="color:#fff;" href="{{ url('admin_logout') }}">ログアウト</a>
      </li>
    </ul>  
  @endif
</nav>

<div class="container">

  {{-- フラッシュ --}}
  @if(session()->has('flash_msg'))
    @if (session('flash_flg') === 1)
      <div class="alert alert-success" id="msg_notice">{{session('flash_msg')}}</div>
    @endif
    @if (session('flash_flg') === 0)
      <div class="alert alert-danger" id="msg_alert">{{session('flash_msg')}}</div>  
    @endif
    {{ session()->forget('flash_msg')}}
    {{ session()->forget('flash_flg')}}    
  @endif  
  
  {{-- メイン --}}
  <div>
    @yield('content')
  </div>
  
  {{-- フッタ --}}
  <nav class="container bg-primary p-2 text-center">
    <div class="text-center text-white">
      {{ config('app.name') }}<br>
      Copyright 2020 Takeshi Okamoto All Rights Reserved.
    </div>
  </nav>   
</div>
</body>
</html>
