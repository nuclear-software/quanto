<div class="form-group {{$errors->has($input['name'])?'has-error':''}}">
    <label for="{{ $input['name'] }}">{{title_case(str_replace('_', ' ',$input['name']))}}</label> <small>{!! old($input['name'])?'<a target="_blank" href="'.old($input['name']).'"> [Current File]</a>':''!!}</small>
    <input class="form-control"
    	   id="input_file_{{ $input['name'] }}"
    	   name="{{ $input['name'] }}"
    	   type="file">
    @include('HTML.error', ['name' => $input['name'] ])
</div>