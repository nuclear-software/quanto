<div class="form-group {{$errors->has($input['name'])?'has-error':''}}">
    <label for="{{ $input['name'] }}">{{title_case(str_replace('_', ' ',$input['name']))}}</label>
    <input id="input_text_{{ $input['name'] }}"
    	   name="{{ $input['name'] }}"
    	   type="checkbox"
    	   value="{{old($input['name'])}}">
   	{!! Form::checkbox($input['name'], old($input['name']), old($input['name'])?true:false) !!}

    @include('HTML.error', ['name' => $input['name'] ])
</div>