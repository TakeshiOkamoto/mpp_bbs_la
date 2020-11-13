@extends('layouts.app')

@section('title', 'カテゴリ - 管理画面')

@section('content')
<p></p>
<h1>編集</h1>
<p></p>

@include('lang_types._form', ['form_action' => url('lang_types/' . $item->id)])

<p></p>
<a href="{{ url('lang_types') }}">戻る</a>
<p></p>
@endsection