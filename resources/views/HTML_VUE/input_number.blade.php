<div class="form-group" :class="{'has-error': errors.has({!! $name !!}) }">
    @if(isset($label))
    <label class="control-label">{!!$label!!}</label>
    @endif
    <input class="form-control input-sm"
           v-model="{!!$model!!}"
           :name="{!!$name!!}"
           type="number"
           v-validate="{!!$validate!!}"
           min="{!!isset($min)?$min:0!!}"
           max="{!!isset($max)?$max:100!!}"
           step="{!!isset($step)?$step:1!!}"
           placeholder="{!!isset($placeholder)?$placeholder:''!!}"
           autocomplete="off">
    <span class="help-block" v-if="errors.has({!!$name!!})">{!! "{{errors.first(".$name.")".'}'.'}' !!}</span>
</div>