<div class="form-group" :class="{'has-error': errors.has({!! $name !!}) }">
	@if(isset($label))
    <label class="control-label">{!!$label!!}</label>
    @endif
	<textarea class="form-control"
			  v-model="{!!$model!!}"
			  :name="{!! $name !!}"
			  placeholder="{!!isset($placeholder)?$placeholder:''!!}"
			  v-validate="{!!$validate!!}"
			  rows="2" style="resize: vertical;"></textarea>
	<span class="help-block" v-if="errors.has({!!$name!!})">{!! "{{errors.first(".$name.")".'}'.'}' !!}</span>
</div>

