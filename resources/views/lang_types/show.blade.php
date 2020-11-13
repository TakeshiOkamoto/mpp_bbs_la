@extends('layouts.app')

@section('title', 'カテゴリ - 管理画面')

@section('content')
<p></p>
<h1>{{$item->name}}</h1>
<p></p>

<p>
  <strong>ID : </strong>
  {{$item->id}}
</p>

<p>
  <strong>{{trans('validation.attributes.keywords')}} : </strong>
  {{$item->keywords}}
</p>

<p>
  <strong>{{trans('validation.attributes.description')}} : </strong>
  {{$item->description}}
</p>

<p>
  <strong>{{trans('validation.attributes.sort')}} : </strong>
  {{$item->sort}}
</p>

<p>
  <strong>{{trans('validation.attributes.show')}} : </strong>
  {{$item->show}}
</p>

<p>
  <strong>{{trans('validation.attributes.created_at')}} : </strong>
  {{$item->created_at}}
</p>

<p>
  <strong>{{trans('validation.attributes.updated_at')}} : </strong>
  {{$item->updated_at}}
</p>


<a href="{{ url('lang_types/' . $item->id . '/edit')}}">編集</a> | <a href="{{ url('lang_types')}}">戻る</a>
<p></p>

@endsection
