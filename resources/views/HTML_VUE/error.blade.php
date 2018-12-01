@if ($errors->has($name))
<span class="help-block">
	@foreach($errors->get($name) as $error)
    	* {{$error}}<br>
	@endforeach
</span>
@endif