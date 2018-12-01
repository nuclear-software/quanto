<div class="form-group" :class="{'has-error': errors.has('{{ $name }}') }">
	<label for="{{ $name }}">{{$label}}</label>
	<div class="input-group date">
		<div class="input-group-addon">
		    <i class="glyphicon glyphicon-calendar"></i>
		</div>
		<input type="text" 
			   class="form-control pull-right input-sm" 
			   id="{{$name}}"
			   v-validate="{{$validate}}"
			   readonly=""
			   name="{{$name}}"
			   value="{{old($name)}}">
	</div>
	<span class="help-block" v-if="errors.has('{{ $name }}')">{{ "{{errors.first('".$name."')".'}'.'}' }}</span>
	{{-- @include('HTML.error', ['name' => $name ]) --}}
</div>	

@section('local-script')
	@parent
    <script>
	  	$( function() {
	  		$('#{{$name}}').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                language:'es'
            });
	  	});
	</script>
@endsection