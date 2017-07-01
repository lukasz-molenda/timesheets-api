@if (Session::has('success'))

	<div class="alert alert-success" role="alert">
		<strong>Sukces:</strong> {{ Session::get('success') }}
	</div>

@endif

@if (Session::has('error'))

	<div class="alert alert-danger" role="alert">
		<strong>Błąd:</strong> {{ Session::get('error') }}
	</div>

@endif

@if (count($errors) > 0)

	<div class="alert alert-danger" role="alert">
		<strong>Błędy:</strong>
		<ul>
		@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
		</ul>
	</div>

@endif