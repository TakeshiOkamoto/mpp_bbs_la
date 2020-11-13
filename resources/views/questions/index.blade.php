@extends('layouts.app')

@section('title', $lang_type->name)
@section('keywords', $lang_type->keywords)
@section('description', $lang_type->description)

@section('content')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">トップ</a></li> 
    <li class="breadcrumb-item active">{{ $lang_type->name }}</li> 
  </ol> 
</nav>    
<p></p>
<h1>{{ $lang_type->name }}</h1>
<p></p>

<form action="{{ url('questions?lang_id=' . $lang_id) }}" method="get" class="mb-5">
  @csrf
  <input type="hidden" name="lang_id" value="{{ $lang_id }}">
  <div class="form-group row">
    <label for="question_title" class="col-sm-2 col-form-label">{{ trans('validation.attributes.title') }}</label>
    <div class="col-sm-10">
      <input type="search" class="form-control" id="question_title" name="title" placeholder="キーワードを入力 ※複数可" value="{{ $title }}">
    </div>
  </div>
  <div class="form-group row">
    <label for="answer_body" class="col-sm-2 col-form-label">{{ trans('validation.attributes.body') }}</label>
    <div class="col-sm-10">
      <input type="search" class="form-control" id="answer_body" name="body" placeholder="キーワードを入力 ※複数可" value="{{ $body }}">
    </div>  
  </div>

  <input type="submit" value="検索" class="btn btn-outline-primary"> 
</form>  
<p></p>
<a class="btn btn-primary" href="{{ url('questions/create?lang_id=' . $lang_id)}}">質問を新規作成する</a>
<p></p>

{{ $items->appends(['lang_id' => $lang_id, 'title' => $title, 'body' => $body])->links() }}

@if (count($items) >0)
  <p>全{{ $items->total() }}件中 
       {{  ($items->currentPage() -1) * $items->perPage() + 1 }} - 
       {{ (($items->currentPage() -1) * $items->perPage() + 1) + (count($items) -1) }}件の質問が表示されています。</p>
@else
  <p>質問がありません。</p>
@endif 

<p></p>
<table class="table table-hover">
  <thead class="thead-default">
    <tr>
        <th class="text-center" style="width: 65px;">状態</th>    
        <th>{{ trans('validation.attributes.title') }}</th>
        <th class="pc" style="width:120px;">{{ trans('validation.attributes.updated_at') }}</th>
        <th class="text-center pc" style="width: 80px;">件数</th>
        <th class="text-center pc" style="width: 90px;">閲覧数</th>   
        @if (session()->has('name'))
          <th style="width: 110px;"></th>           
        @endif  
    </tr>
  </thead>
  <tbody>  
    @php
      // ヘルパーメソッド
      // ※本来は1つのSQLにすべきです(笑)
      function get_answers_data($db_data, $id){
        $result = []; 
        foreach($db_data as $obj){
          if ($obj->id == $id){
            if (isset($obj->cnt)){
              $result['cnt'] = $obj->cnt;
            }else{
              $result['cnt'] =  "破損";
            }  
            $result['name1'] = $obj->name1; 
            $result['name2'] = $obj->name2;
            return $result; 
          }  
        }
        return $result;
      }
    @endphp  
    
    @foreach ($items as $item)
      @php
        $data = get_answers_data($db_data, $item->id);
      @endphp
      
      <tr>
        {{-- 状態 --}} 
        @if ($item->resolved == 1)    
          <td><span class="badge badge-success">解決</span></td>
        @else
          <td></td>
        @endif
        {{-- タイトル --}} 
        <td>          
          <div>
            <div><a href="{{ url('answers?question_id=' . $item->id) }}">{{ $item->title }}</a></div>              
            @if ($data['cnt'] === 1)
              <div class="text-muted" style="font-size:90%">質問者 {{ $data['name1'] }}</div>
            @else
              <div class="text-muted" style="font-size:90%">質問者 {{ $data['name1'] }} 最終発言者 {{ $data['name2'] }}</div>
            @endif
          </div>
        </td>
        {{-- 更新日時 --}}
        <td class="pc">{{ $item->updated_at }}</td>
        {{-- 件数 --}}
        <td class="text-center pc">{{ number_format($data["cnt"]) }}</td>        
        {{-- 閲覧数 --}}
        <td class="text-center pc">{{ number_format($item->pv) }}</td>   
        {{-- 削除 --}}
        @if (session()->has('name'))
          <td><a href="#" onclick="ajax_delete('「{{ $item->title }}」を削除します。よろしいですか？','{{ url('questions/' . $item->id) }}','{{ url('questions?lang_id=' . $lang_id) }}');return false;" class="btn btn-danger">削除</a></td>
        @endif
      </tr>
    @endforeach
  </tbody>
</table>
<p></p>

{{ $items->appends(['lang_id' => $lang_id, 'title' => $title, 'body' => $body])->links() }}

@if (count($items) >0)
  <p>全{{ $items->total() }}件中 
       {{  ($items->currentPage() -1) * $items->perPage() + 1 }} - 
       {{ (($items->currentPage() -1) * $items->perPage() + 1) + (count($items) -1) }}件の質問が表示されています。</p>
@else
  <p>質問がありません。</p>
@endif 

<p></p>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">トップ</a></li> 
    <li class="breadcrumb-item active">{{ $lang_type->name }}</li> 
  </ol> 
</nav>   
@endsection