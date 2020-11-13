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

{{-- フォーム --}} 
<form action="{{ $form_action }}" method="post">
  @csrf
  
  {{-- 初期表示(編集) --}}
  @if(isset($item) && is_null(old('_token')))  
    <input type="hidden" name="id" value="{{ $item->id }}">
  {{-- 新規/編集 --}}    
  @else
    <input type="hidden" name="id" value="{{ old('id') }}">
  @endif   
      
  <div class="form-group">
    <label for="lang_type_name">{{ trans('validation.attributes.name') }}</label>
    @error('name')
      <input type="text" class="form-control is-invalid" id="lang_type_name" name="name" value="{{ old('name') }}">
    @else
      @if(isset($item) && is_null(old('_token')))
        <input type="text" class="form-control" id="lang_type_name" name="name" value="{{ $item->name }}">
      @else
        <input type="text" class="form-control" id="lang_type_name" name="name" value="{{ old('name') }}">
      @endif
    @enderror  
  </div>    
    
  <div class="form-group">
    <label for="lang_type_keywords">{{ trans('validation.attributes.keywords') }}</label>
    @error('keywords')
      <input type="text" class="form-control is-invalid" id="lang_type_keywords" name="keywords" value="{{ old('keywords') }}">
    @else
      @if(isset($item) && is_null(old('_token')))
        <input type="text" class="form-control" id="lang_type_keywords" name="keywords" value="{{ $item->keywords }}">
      @else
       <input type="text" class="form-control" id="lang_type_keywords" name="keywords" value="{{ old('keywords') }}">
      @endif      
    @enderror  
  </div>      

  <div class="form-group">
    <label for="lang_type_description">{{ trans('validation.attributes.description') }}</label>
    @error('description')
      <input type="text" class="form-control is-invalid" id="lang_type_description" name="description" value="{{ old('description') }}">
    @else
      @if(isset($item) && is_null(old('_token')))
        <input type="text" class="form-control" id="lang_type_description" name="description" value="{{ $item->description }}">
      @else
        <input type="text" class="form-control" id="lang_type_description" name="description" value="{{ old('description') }}">
      @endif            
    @enderror  
  </div>    

  <div class="form-group">
    <label for="lang_type_sort">{{ trans('validation.attributes.sort') }}</label>
    @error('sort')
      <input type="number" class="form-control is-invalid" id="lang_type_sort" name="sort" value="{{ old('sort') }}">
    @else
      @if(isset($item) && is_null(old('_token')))
        <input type="number" class="form-control" id="lang_type_sort" name="sort" value="{{ $item->sort }}">
      @else
        <input type="number" class="form-control" id="lang_type_sort" name="sort" value="{{ old('sort') }}">
      @endif            
    @enderror  
  </div>    
   
  <div class="form-group" style="float: left;">
    <label for="lang_type_show">{{ trans('validation.attributes.show') }}</label>
    @if(isset($item) && is_null(old('_token')))
      <input type="checkbox" class="form-control" id="lang_type_show" name="show" value="1" {!! $item->show == "1" ? 'checked="checked"' : '' !!}>
    @else
      <input type="checkbox" class="form-control" id="lang_type_show" name="show" value="1" {!! (old('show') == "1" || is_null(old('_token'))) ? 'checked="checked"' : '' !!}>
    @endif    
  </div>  
  <div style="clear:both;"></div>
  
  <p></p>  
  
  @if(isset($item))
    <input type="hidden" name="_method" value="PUT">
    <input type="submit" value="更新する" class="btn btn-primary">    
  @else
    <input type="submit" value="登録する" class="btn btn-primary">    
  @endif
</form>
