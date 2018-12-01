<div class="btn-group">
	@foreach($items as $item)
	@if($permissions[$item['permission']])
		@if(! isset($item['data_id']))
		<a href="{!!$item['action']!!}" title="{!!$item['name']!!}" @if(isset($item['target'])) target="{!!$item['target']!!}" @endif role="button" class="btn btn-flat btn-xs {!!config('mixedmedia.buttons')[$loop->index%19]!!}"><i class="{!!$item['icon']!!}"></i></a>
		@else
		<a data-id="{!!$item['data_id']!!}" title="{!!$item['name']!!}" @if(isset($item['target'])) target="{!!$item['target']!!}" @endif role="button" class="{!!$item['class_modal']!!} btn btn-flat btn-xs {!!config('mixedmedia.buttons')[$loop->index%19]!!}"><i class="{!!$item['icon']!!}"></i></a>
		@endif
	@endif
	@endforeach
</div>