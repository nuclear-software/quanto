@extends('adminlte::layouts.app')

@section('htmlheader_title')
	Sections
@endsection

@section('contentheader_title')
    Sections
@endsection

@section('contentheader_description')
    @lang('message.index')
@endsection

@section('main-content')	
	<div class="row" id="sectionApp">
		<div class="col-md-12">
			@include('includes.session-alert')

            <datatable :columns="{!!$columns!!}" 
            			:url="'{!! route($link) !!}'"
            			:is-unique="false">
            </datatable>
		</div>
        <div class="col-md-12">
            @include('includes.button-create',['permission'=>'sections_create', 'url'=>route('sections.create')])
            <a role="button" href="{{route('sections.create_only')}}" class="btn btn-success">Create Only</a>
        </div>

        @include('includes.delete-modal',['permission'=>'sections_delete'])
	</div>
@endsection
@section('local-script')

    <!--<script src="http://base.test/js/dist/locale/es.js" type="text/javascript"></script>
    <script src="http://base.test/js/moment.min.js"></script>-->

    <script type="text/javascript">
        
        //Vue.use(VeeValidate, {locale:'es'});

        var section = new Vue({
            el: '#sectionApp',
            data:{
                base_url: '{{route('sections.index')}}',
                to_delete: null
            },
            methods:{
                delete(e){
                    var id= $(e.currentTarget).data('id');
                    this.to_delete= id;
                    console.log(this.to_delete);
                    $('#deleteModal').modal('show');
                },
            },
            mounted(){
                $(document).on("click",".delete-modal", this.delete);
            }
        });

        //section.$validator.installDateTimeValidators(moment);
        
    </script>
    {{ session()->forget('_old_input') }}
@endsection