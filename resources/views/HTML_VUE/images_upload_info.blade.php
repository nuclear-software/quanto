<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">{!!isset($label)?$label:'Gallery'!!}</h3>
        <div class="box-tools pull-right">
            <label class="label label-primary" style="cursor: pointer;">
                @php
                $idx_for= str_contains($model, 'index')?':data-idx="index"':'';
                @endphp
                <i class="glyphicon glyphicon-open-file"></i> Upload<input multiple="" type="file" class="input-image hidden" data-model="{!!$model!!}" {!!$idx_for!!} :data-info="1">
            </label>
        </div>
    </div>
    <div class="box-body">
        <draggable v-model="{!!$model!!}" v-if="{!!$model!!}.length>0">
            <div class="col-md-2 col-sm-3 col-xs-4" v-for="(item,index) in {!!$model!!}">
                <input type="hidden" :name="{!! $name !!}+'['+index+'][image]'" :value="item['image']" v-validate="'{!!isset($required)?'required':''!!}'">
                <div class="box-tools text-right">
                    <button type="button" class="btn btn-box-tool" style="padding-right: 0">
                        @if( str_contains($model, 'index') )
                        <i class="fa fa-times" v-on:click="removeGridImage(index,`{!!$model!!}`, index)"></i>
                        @else
                        <i class="fa fa-times" v-on:click="removeGridImage(index,`{!!$model!!}`)"></i>
                        @endif
                    </button>
                </div>
                <div class="grid-images-item" :style="'background-image: url('+item['image']+');'"></div>
                @include('HTML_VUE.input_text',['model'=>$model."[index]['text1']", 'name'=>$name."+'['+index+'][text1]'", 'placeholder'=>'Texto 1', 'validate'=>"''"])
                @include('HTML_VUE.input_text',['model'=>$model."[index]['text2']", 'name'=>$name."+'['+index+'][text2]'", 'placeholder'=>'Texto 2', 'validate'=>"''"])
                @include('HTML_VUE.input_text',['model'=>$model."[index]['text3']", 'name'=>$name."+'['+index+'][text3]'", 'placeholder'=>'Texto 3', 'validate'=>"''"])
            </div>
        </draggable>
        <input v-if="{!!$model!!}.length==0" type="hidden" :name="{!! $name !!}" value="" v-validate="'{!!isset($required)?'required':''!!}'">
    </div>
    <div class="box-footer"></div>
</div>
<div class="form-group" :class="{'has-error': errors.has({!! $name !!}) }">
    <span class="help-block" v-if="errors.has({!! $name !!})">{!! "{{errors.first(".$name.")".'}'.'}' !!}</span>
</div>