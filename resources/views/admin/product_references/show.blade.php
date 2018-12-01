@extends('adminlte::layouts.app')

@section('htmlheader_title')
	Product References
@endsection

@section('contentheader_title')
	Product References
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
    				<div class="col-md-12">@include('HTML_VUE.input_text',[ 'label'=>'Account Component Id', 'placeholder'=>'Account Component Id', 'model'=>'account_component_id', 'name'=>"'account_component_id'", 'validate'=>"'required'"])</div>
    				<div class="col-md-12">@include('HTML_VUE.input_text',[ 'label'=>'Product Id', 'placeholder'=>'Product Id', 'model'=>'product_id', 'name'=>"'product_id'", 'validate'=>"'required'"])</div>
    				<div class="col-md-12">@include('HTML_VUE.input_text',[ 'label'=>'Quantity', 'placeholder'=>'Quantity', 'model'=>'quantity', 'name'=>"'quantity'", 'validate'=>"'required'"])</div>
    				                    
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

    <!--<script src="http://quanto.test/js/dist/locale/es.js" type="text/javascript"></script>
    <script src="http://quanto.test/js/moment.min.js"></script>-->

    <script type="text/javascript">
        
        //Vue.use(VeeValidate, {locale:'es'});

        var section = new Vue({
            el: '#sectionApp',
            data: {

            	account_component_id:`{!!old('account_component_id')!!}` ,
                product_id:`{!!old('product_id')!!}` ,
                quantity:`{!!old('quantity')!!}` 
                
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