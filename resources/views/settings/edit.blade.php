{!!"@@extends('adminlte::layouts.app')"!!}

{!!"@@section('htmlheader_title')"!!}
	{!!$edit['title']!!}
{!!"@@endsection"!!}

{!!"@@section('contentheader_title')"!!}
	{!!$edit['header']!!}
{!!"@@endsection"!!}

{!!"@@section('contentheader_description')"!!}
	{!!"@@lang('message.edit')"!!}
{!!"@@endsection"!!}

{!!"@@section('main-content')"!!}
	<div class="row" id="sectionApp" v-cloak>
		<div class="col-md-12">
			{!!"@@include('includes.session-alert')"!!}
			<form id="form-section" action="{!! "@{{ route(".$edit['route'].") }}" !!}" method="POST" enctype="multipart/form-data" {!! '@submit.prevent="validateBeforeSubmit"'!!}>
				<input type="hidden" name="_method" value="PUT">
				<input type="hidden" name="_token" value="@{{ csrf_token() }}">
                <div class="row">
    				@foreach($edit['columns'] as $column)@if($column['element']!='json')<div class="col-md-12">{!!"@@include('HTML_VUE.".$column['element']."',[ 'label'=>'".$column['display_name']."', 'placeholder'=>'".$column['display_name']."', 'model'=>'".$column['name']."', 'name'=>\"'".$column['name']."'\", 'validate'=>\"'".$column['v_validate']."'\"])"!!}</div>@else @include('HTML_VUE.'.$column['element'] , ['model'=>$column['name'],'name'=>$column['name'], 'elements'=>$column['json']])@endif

    				@endforeach
                    
                </div>
                <div class="row">
    				<div class="col-md-12">
    					<button type="submit" class="btn btn-primary">@{{trans('message.edit')}}</button>
    				</div>
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

            	@foreach($edit['columns'] as $column){!!$column['name']!!}:@if( !($column['element']=='json' || $column['element']=='images_upload'))`{!! '{!'.'!'."old('".$column['name']."')".'!'.'!}'!!}`@else {!! '{!'.'!'."json_encode(old('".$column['name']."')?:[])".'!'.'!}'!!} @endif @if (!$loop->last),@endif

                @endforeach

            },
            methods: {
            	@foreach($edit['columns'] as $column)@if ($column['element']=='json')add{!! up_camel_case($column['name']) !!}(type){
                    elements= {
                        @foreach($column['json'] as $elements)'{!!$elements['name']!!}': {
                            type:'{!!$elements['name']!!}',
                            data: { 
                                @foreach($elements['components'] as $component)  {!!$component['name']!!}:''@if (!$loop->last),@endif @endforeach
                                
                            }
                        },                            
                        @endforeach

                    };
                    this.{!! $column['name'] !!}.push(elements[type]);
                },
                remove{!! up_camel_case($column['name']) !!}(index){
                    this.{!! $column['name'] !!}.splice(index,1);
                },@endif

	            @endforeach

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
                    var info= $(e.currentTarget).data('info');
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
                                if(info!=undefined){
                                    console.log('info '+info);
                                    let data={
                                        image: e.target.result,
                                        text1:'',
                                        text2:'',
                                        text3:'',
                                    }
                                    eval('vm.'+model+".push(data);");
                                }else{
                                    eval('vm.'+model+".push(e.target.result);");
                                }
                            }else{
                                eval('vm.'+model+"= e.target.result;");
                            }
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
        
        {!!'@@foreach($errors->toArray()?:[] as $key=>$value)'!!}
            section.errors.add('@{{dot_to_html($key)}}', '@{{$errors->first($key)}}');
        {!!"@@endforeach"!!}
        
    </script>
    @{{ session()->forget('_old_input') }}
{!!"@@endsection"!!}