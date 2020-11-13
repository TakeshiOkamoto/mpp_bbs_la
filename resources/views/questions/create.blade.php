@extends('layouts.app')

@section('title', "新規質問の作成 - " . $lang_name)

@section('content')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">トップ</a></li> 
    <li class="breadcrumb-item"><a href="{{ url('questions?lang_id=' . $lang_id) }}">{{ $lang_name }}</a></li> 
    <li class="breadcrumb-item active" aria-current="page">新規質問の作成</li>
  </ol> 
</nav>    
<p></p>
<h1>新規質問の作成 - {{ $lang_name }}</h1>
<p></p>

{{-- エラーメッセージ --}}
@if (count($errors) > 0)
<div id="error_explanation" class="text-danger">
  <ul>
     @foreach ($errors->all() as $error)
       <li>{{ $error }}</li>
     @endforeach
  </ul>
</div>
@endif

<form action="{{ url('questions') }}" method="post">
  @csrf
  
  {{-- 初期表示  --}}
  @if(is_null(old('_token')))  
    <input type="hidden" name="lang_id" value="{{ $lang_id }}">
  {{-- 2回目以降 --}}
  @else
    <input type="hidden" name="lang_id" value="{{ old('lang_id') }}">
  @endif   
  
  <div class="form-group">
    <label for="question_title">{{trans('validation.attributes.title')}}</label>
    @error('title')
      <input type="text" class="form-control is-invalid" id="question_title" name="title" value="{{ old('title') }}">
    @else
      <input type="text" class="form-control" id="question_title" name="title" value="{{ old('title') }}">
    @enderror  
  </div>  
  
  <div class="form-group">
    <label for="answer_name">{{trans('validation.attributes.name')}}</label>
    @error('name')
      <input type="text" class="form-control is-invalid" id="answer_name" name="name" value="{{ old('name') }}">
    @else
      <input type="text" class="form-control" id="answer_name" name="name" value="{{ old('name') }}">
    @enderror  
  </div>  
  
  <div class="form-group">
    <label for="answer_url">ホームページ(ブログ、Twitterなど)のURL (省略可)</label>
    @error('url')
      <input type="text" class="form-control is-invalid" id="answer_url" name="url" value="{{ old('url') }}">
    @else
      <input type="text" class="form-control" id="answer_url" name="url" value="{{ old('url') }}">
    @enderror  
  </div>    
  
  <div class="form-group">
    <label for="answer_body">{{trans('validation.attributes.body')}}</label>
    @error('body')
      <textarea rows="5" class="form-control is-invalid" id="answer_body" name="body">{{ old('body') }}</textarea>    
    @else
      <textarea rows="5" class="form-control" id="answer_body" name="body">{{ old('body') }}</textarea>   
    @enderror  
  </div> 
    
  <input type="submit" value="作成する" class="btn btn-primary">
</form>

<p></p>
<a href="{{ url('questions?lang_id=' . $lang_id ) }}">戻る</a>
<p></p>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">トップ</a></li> 
    <li class="breadcrumb-item"><a href="{{ url('questions?lang_id=' . $lang_id) }}">{{ $lang_name }}</a></li> 
    <li class="breadcrumb-item active" aria-current="page">新規質問の作成</li>
  </ol> 
</nav>  
@endsection