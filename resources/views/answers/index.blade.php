@extends('layouts.app')

@section('title', $question->title . ' - ' . $lang_name)

@section('content')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">トップ</a></li> 
    <li class="breadcrumb-item"><a href="{{ url('questions?lang_id=' . $lang_id) }}">{{ $lang_name }}</a></li> 
    <li class="breadcrumb-item active">{{ $question->title }}</li>     
  </ol> 
</nav>    
<p></p>

{{-- タイトル --}}
@if ($question->resolved)
  <h1>{{ $question->title }}</h1>
  <span class="badge badge-success">解決</span> 
@else
  <h1>{{ $question->title }}</h1>
@endif 
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

@foreach ($items as $item)
  <hr style="margin-bottom:5px;background-color:#c0c0c0;">
  <div class="clearfix mb-2">
    <div class="float-left">
      {{-- 名前 --}}
      <span class="font-weight-bold text-primary">{{ $item->name }}</span>
      {{-- URL  --}}
      @if($item->url != "")
        <span>&nbsp;</span><span><a href="{{ $item->url }}" class="badge badge-info">URL</a></span>
      @endif
      {{-- 更新日時 --}}
      <span>&nbsp;</span><span>{{ $item->updated_at }}</span>
      {{-- NO --}}
      <span class="pc">
        <span>&nbsp;No: </span> 
        <span>{{ $item->id }}</span>      
      </span>  
      {{-- IP --}}
      @if (session()->has('name'))
        <span>&nbsp;IP: </span> 
        <span>[{{ $item->ip }}]</span>      
      @endif
    </div>    
  </div>
  <div class="clearfix">
    <div class="float-none"></div>  
  </div>  
  {{-- 本文 --}} 
  <p>{!! $item->body !!}</p>
  {{-- 管理機能 --}}
  @if (session()->has('name'))
   <span><a href="{{ url('answers/' . $item->id  . '/edit')}}" class="btn btn-primary mr-3">編集</a></span>
   <span><a href="#" onclick="ajax_delete('「No.{{ $item->id }}」を削除します。よろしいですか？','{{ url('answers/' . $item->id) }}','{{ url('answers?question_id=' . $question->id) }}');return false;" class="btn btn-danger">削除</a></span>
  @endif  
@endforeach

@php
  // foreachの$itemが残存してるのでNULLへ
  $item = null;
@endphp
  {{-- 本文 --}} 
  <p>{!! $item->body !!}</p>
  {{-- 管理機能 --}}
  @if (session()->has('name'))
   <span><a href="{{ url('answers/' . $item->id  . '/edit')}}" class="btn btn-primary mr-3">編集</a></span>
   <span><a href="#" onclick="ajax_delete('「No.{{ $item->id }}」を削除します。よろしいですか？','{{ url('answers/' . $item->id) }}','{{ url('answers?question_id=' . $question->id) }}');return false;" class="btn btn-danger">削除</a></span>
  @endif  
<hr style="margin-bottom:5px;background-color:#c0c0c0;"> 
<p></p>
@include('answers._form', ['form_action' => url('answers?question_id=' . $question->id )])  

<br>

<p></p>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">トップ</a></li> 
    <li class="breadcrumb-item"><a href="{{ url('questions?lang_id=' . $lang_id) }}">{{ $lang_name }}</a></li> 
    <li class="breadcrumb-item active">{{ $question->title }}</li>     
  </ol> 
</nav>   
@endsection
