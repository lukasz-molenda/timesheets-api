@extends('welcome')

@section('content')

	<div class="container">
		{!! Form::model($project, ['route' => ['projects.update', $project->id, $client->id], 'method' => 'PUT', 'class' => 'form-group']) !!}

			{{ Form::label('name', 'Nazwa projektu:') }}
			{{ Form::text('name', $project->name, ['class' => 'form-control']) }}

			{{ Form::label('description', 'Opis projektu:') }}
			{{ Form::textarea('description', $project->description, ['class' => 'form-control']) }}

			{{ Form::submit('Akceptuj', ['class' => 'btn btn-info']) }}

		{!! Form::close() !!}
	</div>

@endsection