@extends('welcome')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-2">
		<h1>{{ $client->name }}</h1>
		</div>
		<div class="col-md-10" style="position:relative;">
			@include('inc._internal_nav')
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="well">
				<p>Link dla klienta: <a href="{{ $link }}" target="_blank">{{ $link }}</a></p>
				<p class="lead">Łącznie do zapłaty: <strong>{{$client->total}}</strong> zł</p>
			</div>
		</div>
	</div>
		<div class="row">
		<div class="col-md-12">
		<!-- Hours Panel -->
		<div class="well">
			<div class="row">
			{!! Form::open(['route' => ['clients.update', $client->id], 'method' => 'POST', 'class' => 'form-group col-md-12'] ) !!}

				{{ Form::label('max', 'Miesięczny limit godzin klienta: ') }}
				{{ Form::number('max', $client->max, ['class' => 'form-control'] ) }}

				{{ Form::button("Akceptuj", ['class' => 'btn btn-info', 'type' => 'submit']) }}

			{!! Form::close() !!}
			</div>
		</div>
			
		</div>
			
				
		</div><!-- end of.row -->

</div>

@endsection