@extends('adminlte::layouts.app')

@section('htmlheader_title')
    @lang('message.section')
@endsection

@section('contentheader_title')
    @lang('message.section')
@endsection

@section('contentheader_description')
    @lang('message.create')
@endsection

@section('main-content')
    <div class="row" id="sectionApp" v-cloak>
        <div class="col-md-12">
            <div class="box box-primary">
                <form id="form-section" action="{{route('sections.store')}}" method="POST" {!! '@submit.prevent="validateBeforeSubmit"'!!}>
                    <div class="box-body">
                        
                        <input type="hidden" name="_method" value="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row">
                            <div class=" col-md-6">@include('HTML_VUE.input_text', ['model'=>'display_name', 'name'=>"'display_name'", 'label'=>trans('message.display_name'),'placeholder'=>trans('message.display_name'), 'validate'=>"'required'"])</div>
                            <div class=" col-md-6">@include('HTML_VUE.input_text', ['model'=>'section_name', 'name'=>"'section_name'", 'label'=>trans('message.section_name'),'placeholder'=>trans('message.section_name'), 'validate'=>"'required'"])</div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">@include('HTML_VUE.select_simple', ['model'=>'section_type', 'name'=>"'section_type'", 'label'=>trans('message.section_type'), 'placeholder'=>trans('message.section_type'), 'validate'=>"'required'", 'options'=>'options_multiply'])</div>
                            <div class="col-md-8">@include('HTML_VUE.checkbox_inline',['model'=>'section_includes','name'=>"'section_includes'",'label'=>'Includes', 'options'=>'options_includes','validate'=>"'required'"])</div>
                        </div>

                        <div class="form-group">
                            <div class="pull-right">
                                <a role="button" type="button" v-on:click="addAttribute" class="btn btn-primary btn-xs">
                                    <span class="glyphicon-plus glyphicon"></span>
                                </a>
                                <a role="button" v-on:click="removeAttribute" v-show="attributes.length > 1" class="btn btn-primary btn-xs">
                                    <span class="glyphicon-minus glyphicon"></span>
                                </a>
                            </div>
                            <label for="attributes">{{trans('message.attributes')}}</label>
                        </div>

                        <div class="row" v-show="attributes.length > 0">
                            <div class="col-md-2">Nombre</div>
                            <div class="col-md-2">Tipo</div>
                            <div class="col-md-2">Componente</div>
                            <div class="col-md-2">DB Modificadores</div>
                            <div class="col-md-2">Validación</div>
                            <div class="col-md-2">V Validación</div>
                        </div>

                        <div class="row" v-for="(item, index) in attributes">

                            <div class="col-md-2">
                                @include('HTML_VUE.input_text', ['model'=>"attributes[index]['name']", 'name'=>"'attributes['+index+'][name]'", 'placeholder'=>trans('message.attribute_name'), 'validate'=>"'required'"])
                            </div>

                            <div class="col-md-2">
                                @include('HTML_VUE.select_simple', ['model'=>"attributes[index]['type']", 'name'=>"'attributes['+index+'][type]'", 'options'=>'optionsTypes', 'placeholder'=>trans('message.section_type'), 'validate'=>"'required'"])
                            </div>

                            <div class="col-md-2">
                                @include('HTML_VUE.select_simple', ['model'=>"attributes[index]['element']", 'name'=>"'attributes['+index+'][element]'", 'options'=>'optionsElements', 'placeholder'=>trans('message.select_one'), 'validate'=>"'required'"])
                            </div>

                            <div class="col-md-2">
                                @include('HTML_VUE.input_text', ['model'=>"attributes[index]['modifier']", 'name'=>"'attributes['+index+'][modifier]'", 'placeholder'=>trans('message.modifiers_string'), 'validate'=>"''"])
                            </div>

                            <div class="col-md-2">
                                @include('HTML_VUE.input_text', ['model'=>"attributes[index]['validate']", 'name'=>"'attributes['+index+'][validate]'", 'placeholder'=>trans('message.rules_string'), 'validate'=>"'required'"])
                            </div>

                            <div class="col-md-2">
                                @include('HTML_VUE.input_text', ['model'=>"attributes[index]['v_validate']", 'name'=>"'attributes['+index+'][v_validate]'", 'placeholder'=>trans('message.rules_string'), 'validate'=>"'required'"])
                            </div>

                            <div class="col-md-12" v-show="attributes[index]['element'] == 'json' ">
                                @include('HTML_VUE.input_text', ['model'=>"attributes[index]['json']", 'name'=>"'attributes['+index+'][json]'", 'placeholder'=>trans('message.json_structure'), 'validate'=>"''"])
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="pull-right">
                                <a role="button" type="button" v-on:click="addRelationship" class="btn btn-primary btn-xs">
                                    <span class="glyphicon-plus glyphicon"></span>
                                </a>
                                <a role="button" v-on:click="removeRelationship" class="btn btn-primary btn-xs">
                                    <span class="glyphicon-minus glyphicon"></span>
                                </a>
                            </div>
                            <label for="relations">{{trans('message.relationships')}}</label>
                        </div>


                        <div class="row" v-show="relationships.length > 0">
                            <div class="col-md-2">Nombre</div>
                            <div class="col-md-3">Tipo</div>
                            <div class="col-md-7">Parametros de la relación</div>
                        </div>

                        <div class="row" v-for="(item, index) in relationships">

                            <div class="col-md-2">
                                @include('HTML_VUE.input_text', ['model'=>"relationships[index]['name']", 'name'=>"'relationships['+index+'][name]'", 'placeholder'=>trans('message.relationship_name'), 'validate'=>"'required'"])
                            </div>
                            <div class="col-md-3">
                                @include('HTML_VUE.select_simple', ['model'=>"relationships[index]['type']", 'name'=>"'relationships['+index+'][type]'", 'options'=>'optionsRelationships', 'placeholder'=>trans('message.select_one'), 'validate'=>"'required'"])
                            </div>
                            <div class="col-md-7">
                                @include('HTML_VUE.input_text', ['model'=>"relationships[index]['args']", 'name'=>"'relationships['+index+'][args]'", 'placeholder'=>trans('message.relationship_arguments'), 'validate'=>"'required'"])
                            </div>
                        </div>
                    </div> 

                    

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">{{trans('message.submit')}}</button>
                    </div>     
                </form>

            </div>
        </div>
    </div>
    
@endsection

@section('local-css')
    <style type="text/css">
    	.table-responsive{
    		overflow-x: inherit;
    	}

        [v-cloak] {
          display: none;
        }
        .grid-images-item{
            width: 100%;
            padding-top: 100%;
            border:solid 1px;
            background-size: contain;
            background-position: 50% 50%;
            background-repeat: no-repeat;
        }
        .container-sortable{
            background: transparent;
            border: 1px solid #3c8dbc;
            border-top: 3px solid #3c8dbc;
        }
    </style>
@endsection

@section('local-script')

    {{-- <script src="{{url('/js/dist/locale/es.js')}}" type="text/javascript"></script>
    <script src="{{url('js/moment.min.js')}}"></script> --}}

    <script type="text/javascript">
        
        //Vue.use(VeeValidate, {locale:'es'});

        var section = new Vue({
            el: '#sectionApp',
            data: {
                options_includes: {
                    index:'Index',
                    create:'Create',
                    edit:'Edit',
                    show:'Show',
                    route:'Route',
                    model:'Model',
                    migration:'Migration',
                    controller:'Controller',
                    permissions:'Permissions',
                    api:'Api'
                },
                section_includes: {!!old('section_includes')?json_encode(old('section_includes')):'[]'!!},
            	options_multiply: {!!json_encode(['unica'=>trans('message.section_single'),'multiple'=>trans('message.section_multiple')])!!},
                display_name: {!!"'".old('display_name')."'"!!},
                section_name: {!!"'".old('section_name')."'"!!},
                section_type: {!!"'".old('section_type')."'"!!},
                attributes:
                    @if(old('attributes')&& is_array(old('attributes')))
                    {!!collect(old('attributes'))->toJson()!!}
                    @else
                    [{ name: '', type:'', element:'', modifier:'', validate:'', v_validate:'', json:''}]
                    @endif
                ,
                relationships:
                    @if(old('relationships')&& is_array(old('relationships')))
                    {!!collect(old('relationships'))->toJson()!!}
                    @else
                    [{ name: '', type:'', args:''}]
                    @endif
                ,
                optionsTypes: {!!collect($types)->toJson()!!},
                optionsElements: {!!collect($components)->toJson()!!},
                optionsRelationships: {!!collect($relationships)->toJson()!!},
                    
            },
            methods: {

                addAttribute(){
                    this.attributes.push({ name: '', type:'', element:'', modifier:'', validate:'',json:''});
                },
                removeAttribute(){
                    this.attributes.pop();
                },
                addRelationship(){
                    this.relationships.push({ name: '', type:'', args:''});
                },
                removeRelationship(){
                    this.relationships.pop();
                },
                validateBeforeSubmit() {
                    this.$validator.validateAll().then((result) => {
				        if (result) {
				          document.querySelector('#form-section').submit();
				          
				        }else{
				        	alert('Existen errores en el formulario!!!');
				        }
				    });
                },
                addGridImage(e){

                    var model= $(e.currentTarget).data('model');
                    var idx= $(e.currentTarget).data('idx');
                    if(idx!=undefined){
                        model= model.replace('index',idx);
                    }
                    console.log('model '+model);
                    console.log('idx '+idx);
                    var files = e.target.files || e.dataTransfer.files;
                    var vm = this;

                    for (var i = 0; i < files.length; i++) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            var is_array;
                            eval('is_array= Array.isArray(vm.'+model+');');

                            if(is_array){
                                eval('vm.'+model+".push(e.target.result);");
                            }else{
                                eval('vm.'+model+"= e.target.result;");
                            }
                            //vm[model].push(e.target.result);
                        }
                        reader.readAsDataURL(files[i]);
                    }
                },
                removeGridImage(key,model,index=undefined){
                    console.log('key: '+key+' model: '+model+' index:'+index);
                    if(index != undefined){
                        model= model.replace('index',index);
                    }
                    eval('this.'+model+".splice(key,1);");
                    //this[model].splice(key,1);
                },
                addComponent(type){
                    elements= {
                        'texto-dos-columnas':{
                            type:'texto-dos-columnas',
                            data:{ titulo:'', texto:''}
                        },
                        'galeria':{
                            type:'galeria',
                            data:[]
                        },
                        'video':{
                            type:'video',
                            data:{ url:'',descripcion:''}
                        }
                    };
                    this.components.push(elements[type]);
                    
                },
                removeComponent(index){
                    this.components.splice(index,1);
                }
            },
            mounted(){
                $(document).on('change', '.input-image', this.addGridImage);
                $(document).on('click', '.box-header .box-tools button.sortable-button', function(){
                    $('i', this).toggleClass('fa-window-minimize');
                    $('i', this).toggleClass('fa-window-maximize');
                });
            }
        });

        //section.$validator.installDateTimeValidators(moment);
        
        @foreach($errors->toArray()?:[] as $key=>$value)
            section.errors.add('{{dot_to_html($key)}}', '{{$errors->first($key)}}');
        @endforeach
        
    </script>
    {{ session()->forget('_old_input') }}
@endsection
