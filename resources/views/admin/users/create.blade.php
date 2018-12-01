@extends('adminlte::layouts.app')

@section('htmlheader_title')
	Users
@endsection

@section('contentheader_title')
	Users
@endsection

@section('contentheader_description')
	@lang('message.create')
@endsection

@section('main-content')
	<div class="row" id="sectionApp" v-cloak>
		<div class="col-md-12">
			@include('includes.session-alert')
			<form id="form-section" action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" @submit.prevent="validateBeforeSubmit">
				<input type="hidden" name="_method" value="POST">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="row">
                    <div class="col-md-12">@include('HTML_VUE.input_text',[ 'label'=>'Name', 'placeholder'=>'Name', 'model'=>'name', 'name'=>"'name'", 'validate'=>"'required'"])</div>
                    <div class="col-md-12">@include('HTML_VUE.input_text',[ 'label'=>'Email', 'placeholder'=>'Email', 'model'=>'email', 'name'=>"'email'", 'validate'=>"'required|email'"])</div>
                    <div class="col-md-12">                    
                        @include('HTML_VUE.checkbox_inline',[ 'label'=>'Roles', 'placeholder'=>'Roles', 'model'=>'roles', 'name'=>"'roles'", 'validate'=>"'required'",'options'=>'options_roles'])
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">{{trans('message.create')}}</button>
                    </div>
                </div>
			</form>
		</div>
	</div>
@endsection

@section('local-css')
	<style type="text/css">
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

    <!--<script src="http://base.test/js/dist/locale/es.js" type="text/javascript"></script>
    <script src="http://base.test/js/moment.min.js"></script>-->

    <script type="text/javascript">
        
        //Vue.use(VeeValidate, {locale:'es'});

        var section = new Vue({
            el: '#sectionApp',
            data: {

            	name:`{!!old('name')!!}` ,
                email:`{!!old('email')!!}` ,
                roles: {!!json_encode(old('roles')?:[])!!},
                options_roles: {!!$roles!!}
                
            },
            methods: {
	            
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