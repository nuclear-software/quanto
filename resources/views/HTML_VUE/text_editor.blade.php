<div class="form-group {{$errors->has($input['name'])?'has-error':''}}">
	<label for="{{ $input['name'] }}">{{ title_case(str_replace('_', ' ',$input['name'])) }}</label>
	<textarea class="form-control"
			  name="{{ $input['name'] }}"
			  id="text_editor_{{$input['name']}}"
			  rows="10" cols="80">{{old($input['name'])}}</textarea>
	@include('HTML.error', ['name' => $input['name'] ])
</div>

@section('local-script')
	@parent
    <script type="text/javascript">
        $(function(){
            CKEDITOR.replace( 'text_editor_{{$input['name']}}' );
        });
    </script>
@endsection

