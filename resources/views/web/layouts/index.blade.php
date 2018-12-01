<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
	@section('htmlheader')
	    @include('web.layouts.headhtml')
	@show

	<body>
		@yield('main-content')
		@include('web.layouts.scripts')
	</body>
</html>