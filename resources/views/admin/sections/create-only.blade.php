@extends('adminlte::layouts.app')

@section('htmlheader_title')
	Sections
@endsection

@section('contentheader_title')
	Sections
@endsection

@section('contentheader_description')
	Create Only
@endsection

@section('main-content')
	<div class="row" id="sectionApp" v-cloak>
		<div class="col-md-12">
			@include('includes.session-alert')
			<form id="form-section" action="{{ route('sections.store_only') }}" method="POST" enctype="multipart/form-data" @submit.prevent="validateBeforeSubmit">
				<input type="hidden" name="_method" value="POST">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">

				<div class="col-md-12">@include('HTML_VUE.input_text',[ 'label'=>'Display Name', 'placeholder'=>'Display Name', 'model'=>'display_name', 'name'=>"'display_name'", 'validate'=>"'required'"])</div>
                <div class="col-md-12">@include('HTML_VUE.input_text',[ 'label'=>'Icon', 'placeholder'=>'Icon', 'model'=>'icon', 'name'=>"'icon'", 'validate'=>"'required'"])</div>
                <div class="col-md-12">@include('HTML_VUE.input_text',[ 'label'=>'Route', 'placeholder'=>'Route', 'model'=>'route', 'name'=>"'route'", 'validate'=>"'required'"])</div>
                <div class="col-md-12">@include('HTML_VUE.input_text',[ 'label'=>'Permission', 'placeholder'=>'Permission', 'model'=>'permission', 'name'=>"'permission'", 'validate'=>"'required'"])</div>
                
				<div class="col-md-12">
					<button type="submit" class="btn btn-primary">Save</button>
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

            	display_name:`{!!old('display_name')!!}` ,
                icon:`{!!old('icon')!!}` ,
                route:`{!!old('route')!!}`,
                permission:`{!!old('permission')!!}` 
                
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
                removeGridImage(key,model){
                    console.log('key: '+key+' model: '+model);
                    eval('this.'+model+".splice(key,1);");
                    //this[model].splice(key,1);
                }
            },
            mounted(){
                $('.input-image').on('change', this.addGridImage);
            }
        });

        //section.$validator.installDateTimeValidators(moment);
        
        @foreach($errors->toArray()?:[] as $key=>$value)
            section.errors.add('{{dot_to_html($key)}}', '{{$errors->first($key)}}');
        @endforeach
        
    </script>
    {{ session()->forget('_old_input') }}
@endsection