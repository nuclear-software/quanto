<div class="form-group" :class="{'has-error': errors.has('{{ $name }}') }">            
  	<label class="checkbox-inline">
    	<input class="{{ $name.'_si' }}" type="checkbox" name="{{ $name }}" v-validate="{{$validate}}"  v-model="{{ $model }}" value="{!!$value!!}">
        {!!$label!!}
  	</label>
    @if(isset($help))
    <span style="display: inline-block;" class="glyphicon glyphicon-question-sign" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{$help}}"></span>
    @endif
    <span class="help-block" v-if="errors.has('{{ $name }}')">{{ "{{errors.first('".$name."')".'}'.'}' }}</span>
</div>