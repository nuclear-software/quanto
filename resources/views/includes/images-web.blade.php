<div class="col-md-12 container-admin-gallery">
	<div class="row">
		@foreach($images as $image)
		<div class="col-md-2 container-admin-img">
			<img src="{{ $image }}" alt="" class="img-responsive">
		</div>
		@endforeach
	</div>
</div>