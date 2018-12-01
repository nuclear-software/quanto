<div class="container">
<section class="slider">	
	@foreach ($project['slider'] as $slider)
		<img src="{{ $slider }}" alt="" class="img-fluid">
	@endforeach
</section>
</div>