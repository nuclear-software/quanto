<div class="form-group {{$errors->has($input['name'])?'has-error':''}}">
    <label for="{{ $input['name'] }}">{{$input['name']}}</label>
    <input class="form-control"
    	   id="input_range_{{ $input['name'] }}"
    	   name="{{ $input['name'] }}"
    	   type="range"    	   
    	   value="{{old("$input['name']")}}"
    	   min="{{$input['min']}}" max="{{$input['min']}}">
   	@include('HTML.error', ['name' => $input['name'] ])
</div>