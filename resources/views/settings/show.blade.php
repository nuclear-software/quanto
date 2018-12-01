{!!"@@extends('adminlte::layouts.app')"!!}

{!!"@@section('htmlheader_title')"!!}
	{!!$show['title']!!}
{!!"@@endsection"!!}

{!!"@@section('contentheader_title')"!!}
	{!!$show['header']!!}
{!!"@@endsection"!!}

{!!"@@section('contentheader_description')"!!}
	Show
{!!"@@endsection"!!}

{!!"@@section('main-content')"!!}
	<div class="row" id="sectionApp" v-cloak>
		<div class="col-md-12">
			{!!"@@include('includes.session-alert')"!!}
			<form id="form-section">
                <div class="row">
    				@foreach($show['columns'] as $column)@if($column['element']!='json')<div class="col-md-12">{!!"@@include('HTML_VUE.".$column['element']."',[ 'label'=>'".$column['display_name']."', 'placeholder'=>'".$column['display_name']."', 'model'=>'".$column['name']."', 'name'=>\"'".$column['name']."'\", 'validate'=>\"'".$column['v_validate']."'\"])"!!}</div>@else @include('HTML_VUE.'.$column['element'] , ['model'=>$column['name'],'name'=>$column['name'],'readonly'=>'', 'elements'=>$column['json']])@endif

    				@endforeach
                    
                </div>

			</form>
		</div>
	</div>
{!!"@@endsection"!!}

{!!"@@section('local-css')"!!}
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
{!!"@@endsection"!!}

{!!"@@section('local-script')"!!}

    <!--<script src="{{url('/js/dist/locale/es.js')}}" type="text/javascript"></script>
    <script src="{{url('js/moment.min.js')}}"></script>-->

    <script type="text/javascript">
        
        //Vue.use(VeeValidate, {locale:'es'});

        var section = new Vue({
            el: '#sectionApp',
            data: {

            	@foreach($show['columns'] as $column){!!$column['name']!!}:@if( !($column['element']=='json' || $column['element']=='images_upload'))`{!! '{!'.'!'."old('".$column['name']."')".'!'.'!}'!!}`@else {!! '{!'.'!'."json_encode(old('".$column['name']."')?:[])".'!'.'!}'!!} @endif @if (!$loop->last),@endif

                @endforeach

            }
        });

        //section.$validator.installDateTimeValidators(moment);
        
        {!!'@@foreach($errors->toArray()?:[] as $key=>$value)'!!}
            section.errors.add('@{{dot_to_html($key)}}', '@{{$errors->first($key)}}');
        {!!"@@endforeach"!!}
        
    </script>
    <script type="text/javascript">
        $(function(){
            $("#sectionApp :input").prop("disabled", true);
        });
    </script>
    @{{ session()->forget('_old_input') }}
{!!"@@endsection"!!}