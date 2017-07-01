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
	<div class="panel panel-info">
				<div class="panel-heading text-center">Edycja projektów</div>
				<div class="panel-body">
					<p class="lead">Dodaj projekt:</p>
					{!! Form::open(['route' => ['project.store', $client->id], 'method' => 'POST', 'class' => 'form-group']) !!}
						{{ Form::label('name', 'Nazwa projektu') }}
						{{ Form::text('name', null, ['class' => 'form-control']) }}

						{{ Form::label('description', 'Opis projektu') }}
						{{ Form::textarea('description', null, ['class' => 'form-control']) }}

						{{ Form::button("Dodaj", ['class' => 'btn btn-info', 'type' => 'submit']) }}

					{!! Form::close() !!}

					<p class="lead">Lista projektów klienta:</p>
					<table class="table">
						<thead>
							<tr>
								<th>Nazwa</th>
								<th>Opis</th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($projects as $project)
								<tr>
									<td>{{$project->name}}</td>
									<td>{{$project->description}}</td>
									<td><a href="{{ route('project.edit', [$client->id, $project->id] ) }}" class="btn btn-primary btn-xs">Pokaż</a></td>
									<td>
										{!! Form::open(['route' => ['projects.destroy', $client->id, $project->id], 'method' => 'DELETE']) !!}
						                    {{ Form::submit('Usuń', ['class' => 'btn btn-danger btn-xs']) }}
						                {!! Form::close() !!}
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
	
			
@endsection