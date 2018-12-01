<div class="container-ficha">
	<div class="container">
		<div class="row cont-ficha">
			@foreach ($project['ficha'] as $ficha)
				<div class="col-md-4 box-ficha">
					<p class="title-ficha"> {{ $ficha['title'] }} </p>
					<p class="description-ficha"> {{ $ficha['description'] }} </p>
				</div>
			@endforeach
		</div>
	</div>
</div>