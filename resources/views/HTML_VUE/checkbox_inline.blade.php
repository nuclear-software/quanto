<div class="form-group" :class="{'has-error': errors.has({!! $name !!}) }">
	@if(isset($label))
		<label class="control-label">{!!$label!!}</label>
	@endif
	<div class="input-group">
		<label class="{!!isset($chunk)?'col-xs-'.$chunk:'checkbox-inline'!!}" v-for="(text, key) in {!!$options!!}">
			<input type="checkbox" :name="{!!$name!!}+'[]'" v-validate="{!!$validate!!}" v-model="{!!$model!!}" v-bind:value="key">@{{ text }}
		</label>
	</div>
	<span class="help-block" v-if="errors.has({!!$name!!})">{!! "{{errors.first(".$name.")".'}'.'}' !!}</span>
</div>