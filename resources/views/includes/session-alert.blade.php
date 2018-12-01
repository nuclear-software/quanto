@if(session('alert'))
    <div class="alert alert-{{session('alert')['status']}} alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{session('alert')['message']}}
    </div>
@endif