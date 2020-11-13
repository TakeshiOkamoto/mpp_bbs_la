@extends('layouts.app')

@section('title', 'カテゴリ - 管理画面')

@section('content')
<p></p>
<h1>新規登録</h1>
<p></p>

@include('lang_types._form', ['form_action' => url('lang_types')])

<p></p>
<a href="{{ url('lang_types') }}">戻る</a>
<p></p>
@endsection