<div class="form-group" :class="{'has-error': errors.has({!! $name !!}) }">
	@if(isset($label))
    <label class="control-label">{{$label}}</label>
    @endif
	<select class="form-control input-sm" v-validate="{!!$validate!!}" v-model="{!!$model!!}"  :name="{!!$name!!}">
		<option value="">{!!isset($placeholder)?$placeholder:'Select'!!}</option>
		<option v-for="(text, key) in {!!$options!!}" v-bind:value="key">@{{ text }}</option>
	</select>
	<span class="help-block" v-if="errors.has({!! $name !!})">{!! "{{errors.first(".$name.")".'}'.'}' !!}</span>
</div>