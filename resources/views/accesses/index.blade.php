@extends('layouts.app')

@section('title', 'アクセス解析 - 管理画面')

@section('content')
<p></p>
<h1>アクセス解析</h1>
<p></p>

<h2>日毎(1か月分)</h2>
<p></p>

<table class="table table-hover">
  <thead class="thead-default">
    <tr>
      <th class="text-center">年</th>
      <th class="text-center">月</th>
      <th class="text-center">日</th>
      <th class="text-center">PV</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($one_month_ago as $item)
    <tr>
      <td class="text-center">{{ $item->yyyy }}</td>
      <td class="text-center">{{ $item->mm }}</td>
      @if ($item->week == 0 || $item->week == 6)
        <td class="text-center text-danger">{{ $item->dd }}</td>
      @else
        <td class="text-center">{{ $item->dd }}</td>
      @endif
      <td class="text-center">{{ $item->pv }}</td>
    </tr>  
    @endforeach  
  </tbody>
</table>
<p></p>

<h2>月毎(前年以降)</h2>
<p></p>
<table class="table table-hover">
  <thead class="thead-default">
    <tr>
      <th class="text-center">年</th>
      <th class="text-center">月</th>
      <th class="text-center">1日平均PV</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($one_year_ago as $item)
    <tr>
      <td class="text-center">{{ $item->yyyy }}</td>
      <td class="text-center">{{ $item->mm }}</td>
      <td class="text-center">{{ $item->pv }}</td>
    </tr>  
    @endforeach  
  </tbody>
</table>
<p><br></p>
@endsection
