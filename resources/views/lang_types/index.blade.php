@extends('layouts.app')

@section('title', 'カテゴリ - 管理画面')

@section('content')
<p></p>
<h1>カテゴリ</h1>
<p></p>

<form action="{{ url('lang_types') }}" method="get">
  <div class="input-group">
    <input type="search" name="name" class="form-control" placeholder="検索したい名前を入力" value="{{ $name }}">
    <span class="input-group-btn">
      <input type="submit" value="検索" class="btn btn-outline-info"> 
    </span>
  </div>
</form>

<p></p>

<table class="table table-hover">
  <thead class="thead-default">
    <tr>
      <th>{{ trans('validation.attributes.name') }}</th>
      <th class="pc">{{ trans('validation.attributes.keywords') }}</th>
      <th class="pc">ソート</th>
      <th class="pc">表示</th>   
      <th></th>  
    </tr>
  </thead>
  <tbody class="thead-default">
    @foreach ($items as $item)
    <tr>
      <td><a href="{{ url('lang_types/' . $item->id) }}">{{ $item->name }}</a></td>
      <td class="pc">{{ $item->keywords }}</td>
      <td class="pc" style="width:80px;">{{ $item->sort }}</td>
      <td class="pc" style="width:70px;">{{ $item->show }}</td>
      <td style="width:170px;">
        <a href="{{ url('lang_types/' . $item->id . '/edit') }}" class="btn btn-primary">編集</a>
        &nbsp;&nbsp;
        <a href="#" onclick="ajax_delete('「{{ $item->name }}」を削除します。よろしいですか？','{{ url('lang_types/' . $item->id) }}','{{ url('lang_types') }}');return false;" class="btn btn-danger">削除</a>
      </td>      
    </tr>    
    @endforeach
  </tbody>    
</table>

{{ $items->appends(['name' => $name])->links() }}

@if (count($items) >0)
  <p>全{{ $items->total() }}件中 
       {{  ($items->currentPage() -1) * $items->perPage() + 1 }} - 
       {{ (($items->currentPage() -1) * $items->perPage() + 1) + (count($items) -1) }}件のデータが表示されています。</p>
@else
  <p>データがありません。</p>
@endif 

<p></p>
<a href="{{ url('lang_types/create') }}" class="btn btn-primary">カテゴリの新規登録</a>
<p><br></p>
@endsection
