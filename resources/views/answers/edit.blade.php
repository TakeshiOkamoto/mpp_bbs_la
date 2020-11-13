@extends('layouts.app')

@section('title', '編集')

@section('content')
<p></p>
<h1>{{ $question->title }} (ID:{{ $item->id }})</h1>
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

@include('answers._form', ['form_action' => url('answers/' . $item->id)])

<p></p>
<a href="{{ url('answers?question_id=' . $question->id) }}">戻る</a>
<p></p>
@endsection