{{-- {!!"\t\t\t\t"!!}<div class="col-md-12">
{!!"\t\t\t\t"!!}	@php echo"@include('HTML_VUE.json_buttons', ['name'=>'$name','required'=>''])\n" @endphp
{!!"\t\t\t\t"!!}	<div class="row" v-show="{!!$name!!}.length > 0">
{!!"\t\t\t\t"!!}
{!!"\t\t\t\t"!!}		@foreach($columns as $column)<div class="col-md-12">{!!$column['name']!!}</div>
{!!"\t\t\t\t"!!}		@endforeach
{!!"\t\t\t\t"!!}
{!!"\t\t\t\t"!!}	</div>
{!!"\t\t\t\t"!!}
{!!"\t\t\t\t"!!}	<div class="row" v-for="(item, index) in {!!$name!!}">
{!!"\t\t\t\t"!!}
{!!"\t\t\t\t"!!}		@foreach($columns as $column)<div class="col-md-12">@php echo "@include('HTML_VUE.".$column['element']."',['model'=>\"".$name."[index]['".$column['name']."']\", 'name'=>\"'".$name."['+index+'][".$column['name']."]'\", 'placeholder'=>'".$column['placeholder']."', 'validate'=>\"'".$column['v_validate']."'\"])" @endphp</div>
{!!"\t\t\t\t"!!}		@endforeach
{!!"\t\t\t\t"!!}
{!!"\t\t\t\t"!!}	</div>
{!!"\t\t\t\t"!!}    <input v-if="{!!$name!!}.length==0" type="hidden" :name="'{!! $name !!}'" value="" v-validate="'required'">
{!!"\t\t\t\t"!!}	<div class="form-group" :class="{'has-error': errors.has('{!! $name !!}') }">                        
{!!"\t\t\t\t"!!}        <span class="help-block" v-if="errors.has('{!! $name !!}')">{!! "@{{errors.first('".$name."')".'}'.'}' !!}</span>
{!!"\t\t\t\t"!!}    </div>
{!!"\t\t\t\t"!!}</div> --}}

                <div class="col-md-12">
                    <div class="box box-primary container-sortable">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{title_case($name)}}</h3>
                            <div class="box-tools pull-right">
                                @foreach($elements as $element)<button type="button" class="btn btn-primary btn-xs" v-on:click="add{!! studly_case( str_slug($name, '_'))!!}('{!!$element['name']!!}')">{!!title_case($element['name'])!!}</button>                              
                                @endforeach

                            </div>
                        </div>
                        <div class="box-body">
                            <draggable v-model="{!!$name!!}" :options="{draggable:'.box'}">
                                <div v-for="(item, index) in {!!$name!!}" class="box">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">
                                            <span class="handle">
                                                <i class="fa fa-ellipsis-v"></i>
                                                <i class="fa fa-ellipsis-v"></i>
                                            </span>
                                            @@{{item['type']}}
                                            
                                        </h3>
                                        <div class="box-tools pull-right">
                                            <button type="button" class="btn btn-box-tool sortable-button" :data-target="'#{!!$name!!}_'+index" data-toggle="collapse" title="Collapse"><i class="fa fa-window-maximize"></i></button>
                                            <button type="button" class="btn btn-box-tool" title="Remove" v-on:click="remove{!!studly_case( str_slug($name, '_'))!!}(index)"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                    <div class="box-body {!!isset($readonly)?'collapse in':'collapse'!!}" :id="'{!!$name!!}_'+index">
                                        <div class="row">
                                            @foreach($elements as $element)<div v-if="item['type'] =='{!!$element['name']!!}'">
                                                <input type="hidden" :name="'{!!$name!!}['+index+'][type]'" value="{!!$element['name']!!}">
                                                
                                                @foreach($element['components'] as $column)<div class="col-md-12">@php echo "@include('HTML_VUE.".$column['element']."',['model'=>\"".$name."[index]['data']['".$column['name']."']\", 'name'=>\"'".$name."['+index+'][data][".$column['name']."]'\", 'placeholder'=>'".$column['placeholder']."', 'label'=>'".$column['placeholder']."', 'validate'=>\"'".$column['v_validate']."'\"])" @endphp</div>
                                                @endforeach
                                                
                                            </div>
                                            @endforeach
                                            
                                        </div>
                                    </div>
                                </div>
                            </draggable>
                        </div>
                    </div>
                </div>