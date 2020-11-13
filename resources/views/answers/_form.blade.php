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
    <label for="answer_name">{{ trans('validation.attributes.name') }}</label>
    @error('name')
      <input type="text" class="form-control is-invalid" id="answer_name" name="name" value="{{ old('name') }}">
    @else
      @if(isset($item) && is_null(old('_token')))
        <input type="text" class="form-control" id="answer_name" name="name" value="{{ $item->name }}">
      @else
        <input type="text" class="form-control" id="answer_name" name="name" value="{{ old('name') }}">
      @endif
    @enderror  
  </div>   
  
  <div class="form-group">
    <label for="answer_url">ホームページ(ブログ、Twitterなど)のURL (省略可)</label>
    @error('url')
      <input type="text" class="form-control is-invalid" id="answer_url" name="url" value="{{ old('url') }}">
    @else
      @if(isset($item) && is_null(old('_token')))
        <input type="text" class="form-control" id="answer_url" name="url" value="{{ $item->url }}">
      @else
        <input type="text" class="form-control" id="answer_url" name="url" value="{{ old('url') }}">
      @endif
    @enderror  
  </div>      
    
  <div class="form-group">
    <label for="answer_body">{{ trans('validation.attributes.body')}}</label>
    @error('body')
      <textarea rows="5" class="form-control is-invalid" id="answer_body" name="body">{{ old('body') }}</textarea>
    @else
      @if(isset($item) && is_null(old('_token')))
        <textarea rows="5" class="form-control" id="answer_body" name="body">{{ $item->body }}</textarea>
      @else
        <textarea rows="5" class="form-control" id="answer_body" name="body">{{ old('body') }}</textarea>
      @endif      
    @enderror  
  </div>      
  
  <div class="form-check">
    <label class="form-check-label" for="question_resolved">
      @if(isset($item) && is_null(old('_token')))
        <input class="form-check-input" type="checkbox" value="1" name="resolved" id="question_resolved" {!! $question->resolved == "1" ? 'checked="checked"' : '' !!}>
      @else
        <input class="form-check-input" type="checkbox" value="1" name="resolved" id="question_resolved" {!! old('resolved') == "1" ? 'checked="checked"' : '' !!}>
      @endif    
      ←解決時は質問者本人がここをチェックしてください。
    </label>
    <p></p>
  </div>
  
  <br>  
  
  @if(isset($item))
    <input type="hidden" name="_method" value="PUT">
    <input type="submit" value="更新する" class="btn btn-primary">    
  @else
    <input type="submit" value="返信する" class="btn btn-primary" onclick="DisableButton(this);">    
  @endif
</form>
