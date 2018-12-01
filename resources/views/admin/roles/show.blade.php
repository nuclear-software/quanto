@extends('adminlte::layouts.app')

@section('htmlheader_title')
	Roles
@endsection

@section('contentheader_title')
	Roles
@endsection

@section('contentheader_description')
	Show
@endsection

@section('main-content')
	<div class="row" id="sectionApp" v-cloak>
		<div class="col-md-12">
			@include('includes.session-alert')
			<form id="form-section">
                <div class="row">
    				<div class="col-md-12">@include('HTML_VUE.input_text',[ 'label'=>'Name', 'placeholder'=>'Name', 'model'=>'name', 'name'=>"'name'", 'validate'=>"'required'"])</div>                   
                </div>

                <div class="row">
                    <div class="col-md-12">                    
                        @include('HTML_VUE.checkbox_inline',[ 'label'=>'Permissions', 'placeholder'=>'Permissions', 'model'=>'permissions', 'name'=>"'permissions'", 'validate'=>"'required'",'options'=>'options_permissions','chunk'=>'3'])                    
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

    <!--<script src="http://kha.test/js/dist/locale/es.js" type="text/javascript"></script>
    <script src="http://kha.test/js/moment.min.js"></script>-->

    <script type="text/javascript">
        
        //Vue.use(VeeValidate, {locale:'es'});

        var section = new Vue({
            el: '#sectionApp',
            data: {

            	name:`{!!old('name')!!}`,
                permissions: {!!json_encode(old('permissions')?:[])!!},
                options_permissions: {!!$permissions!!}

            }
        });

        //section.$validator.installDateTimeValidators(moment);
        
        @foreach($errors->toArray()?:[] as $key=>$value)
            section.errors.add('{{dot_to_html($key)}}', '{{$errors->first($key)}}');
        @endforeach
        
    </script>
    <script type="text/javascript">
        $(function(){
            $("#sectionApp :input").prop("disabled", true);
        });
    </script>
    {{ session()->forget('_old_input') }}
@endsection