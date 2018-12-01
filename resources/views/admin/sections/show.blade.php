@extends('adminlte::layouts.app')

@section('htmlheader_title')
	Sections
@endsection

@section('contentheader_title')
	Sections
@endsection

@section('contentheader_description')
	Show
@endsection

@section('main-content')
	<div class="row" id="sectionApp" v-cloak>
		<div class="col-md-12">
			@include('includes.session-alert')
			<form id="form-section">

				<div class="col-md-12">@include('HTML_VUE.input_text',[ 'label'=>'Display Name', 'placeholder'=>'Display Name', 'model'=>'display_name', 'name'=>"'display_name'", 'validate'=>"'required'"])</div>
                <div class="col-md-12">@include('HTML_VUE.input_text',[ 'label'=>'Icon', 'placeholder'=>'Icon', 'model'=>'icon', 'name'=>"'icon'", 'validate'=>"'required'"])</div>
                <div class="col-md-12">@include('HTML_VUE.input_text',[ 'label'=>'Route', 'placeholder'=>'Route', 'model'=>'route', 'name'=>"'route'", 'validate'=>"'required'"])</div>
                <div class="col-md-12">@include('HTML_VUE.input_text',[ 'label'=>'Permission', 'placeholder'=>'Permission', 'model'=>'permission', 'name'=>"'permission'", 'validate'=>"'required'"])</div>
                
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