@extends('layouts.app')

@section('title', '掲示板システム')
@section('keywords', 'キーワード')
@section('description', '説明')

@section('content')
<p></p>
<table class="table table-hover">
  <thead class="thead-default">
    <tr>
        <th>カテゴリ</th>    
        <th class="text-center pc">質問数</th>
        <th class="text-center pc">コメント数</th>
        <th class="text-center pc">回答率</th> 
        <th class="text-center pc">解決率</th>  
        <th class="text-center pc">閲覧数</th>  
    </tr>  
  </thead>
  
  <tbody>
     @foreach ($items as $item)
       @if ($item->show === 1)
       <tr>
         <td><a href="{{ url('questions?lang_id=' . $item->id) }}">{{ $item->name }}</a></td>
         <td class="text-center pc">{{ number_format($counts[$loop->index]->A) }}</td>
         <td class="text-center pc">{{ number_format($counts[$loop->index]->B) }}</td>
         
         @if ($counts[$loop->index]->A == 0)
           <td class="text-center pc">0</td>
         @else
           <td class="text-center pc">{{ round((($counts[$loop->index]->A - $counts[$loop->index]->C) * 1.0 / $counts[$loop->index]->A * 1.0)  * 100, 2) }}%</td>
         @endif
         
         @if ($counts[$loop->index]->D == 0)
           <td class="text-center pc">0</td>
         @else
           <td class="text-center pc">{{ round(($counts[$loop->index]->D * 1.0 / $counts[$loop->index]->A * 1.0) * 100, 2) }}%</td>
         @endif         
         
         <td class="text-center pc">{{ number_format($counts[$loop->index]->E) }}</td>
       </tr>
       @endif
     @endforeach
  </tbody>
</table>
<p></p>
@endsection