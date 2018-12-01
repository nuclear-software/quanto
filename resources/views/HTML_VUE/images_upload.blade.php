<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">{!!isset($label)?$label:'Gallery'!!}</h3>
        <div class="box-tools pull-right">
            <label class="label label-primary" style="cursor: pointer;">
                @php
                $idx_for= str_contains($model, 'index')?':data-idx="index"':'';
                @endphp
                <i class="glyphicon glyphicon-open-file"></i> Upload<input multiple="" type="file" class="input-image hidden" data-model="{!!$model!!}" {!!$idx_for!!}>
            </label>
        </div>
    </div>
    <div class="box-body">
        <draggable v-model="{!!$model!!}" v-if="{!!$model!!}.length>0">
            <div class="col-md-2 col-sm-3 col-xs-4" v-for="(element,key) in {!!$model!!}">
                <input type="hidden" :name="{!! $name !!}+'[]'" :value="element" v-validate="'{!!isset($required)?'required':''!!}'">
                <div class="box-tools text-right">
                    <button type="button" class="btn btn-box-tool" style="padding-right: 0">
                        @if( str_contains($model, 'index') )
                        <i class="fa fa-times" v-on:click="removeGridImage(key,`{!!$model!!}`, index)"></i>
                        @else
                        <i class="fa fa-times" v-on:click="removeGridImage(key,`{!!$model!!}`)"></i>
                        @endif
                    </button>
                </div>
                <div class="grid-images-item" :style="'background-image: url('+element+');'"></div>                            
            </div>
        </draggable>
        <input v-if="{!!$model!!}.length==0" type="hidden" :name="{!! $name !!}" value="" v-validate="'{!!isset($required)?'required':''!!}'">
    </div>
    <div class="box-footer"></div>
</div>
<div class="form-group" :class="{'has-error': errors.has({!! $name !!}) }">
    <span class="help-block" v-if="errors.has({!! $name !!})">{!! "{{errors.first(".$name.")".'}'.'}' !!}</span>
</div>