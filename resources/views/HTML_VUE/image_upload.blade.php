<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">{!!isset($label)?$label:'Image'!!}</h3>
        <div class="box-tools pull-right">
            <label class="label label-primary" style="cursor: pointer;">
            	@php
                $idx_for= str_contains($model, 'index')?':data-idx="index"':'';
                @endphp
                <i class="glyphicon glyphicon-open-file"></i> Upload<input type="file" class="input-image hidden" data-model="{!!$model!!}" {!!$idx_for!!}>
            </label>
        </div>
    </div>
    <div class="box-body">        
        <div class="col-md-12">
            <input type="hidden" :name="{!! $name !!}" :value="{!!$model!!}" v-validate="'{!!isset($required)?'required':''!!}'">
            <div class="box-tools text-right">
                <button type="button" class="btn btn-box-tool" style="padding-right: 0">
                    <i class="fa fa-times" @if(! isset($disabled) ) v-on:click=" {!!$model!!}='' "@endif ></i>
                </button>
            </div>
            <div class="grid-images-item" :style="'background-image: url('+{!!$model!!}+');'"></div>
        </div>
    </div>
    <div class="box-footer"></div>
</div>
<div class="form-group" :class="{'has-error': errors.has({!! $name !!}) }">
    <span class="help-block" v-if="errors.has({!! $name !!})">{!! "{{errors.first(".$name.")".'}'.'}' !!}</span>
</div>