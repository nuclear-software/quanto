<div class="form-group" :class="{'has-error': errors.has({!! $name !!}) }">
    @if(isset($label))
    <label class="control-label">{!!$label!!}</label>
    @endif
    <input class="form-control input-sm"
           v-model="{!!$model!!}"
           :name="{!!$name!!}"
    	   type="text"
    	   v-validate="{!!$validate!!}"
           placeholder="{!!isset($placeholder)?$placeholder:'YYYY-MM-DD'!!}"
    	   autocomplete="off">
   	<span class="help-block" v-if="errors.has({!!$name!!})">{!! "{{errors.first(".$name.")".'}'.'}' !!}</span>
</div>