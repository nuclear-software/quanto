@if(Auth::user()->can($permission))
<div class="modal fade" id="deleteModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><i class="icon fa fa-warning fa-lg text-yellow"></i> {{trans('message.warning')}}</h4>
            </div>
            <div class="modal-body">
                <h5>{{trans('message.warning_delete')}} <b><span>@{{to_delete}}</span></b> ?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn pull-left" data-dismiss="modal">{{trans('message.close')}}</button>
                <form :action="base_url+'/'+to_delete" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="btn btn-warning">{{trans('message.continue')}}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif