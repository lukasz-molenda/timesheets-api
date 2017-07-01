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
		<div class="panel-heading text-center">Edycja przedziałów</div>
		<div class="panel-body">
			<div class="row">
				<table class="table text-center">

					<thead>
						<th>Od</th>
						<th>Do</th>
						<th>Cena za godzinę (zł)</th>
						<th></th>
						<th></th>
					</thead>

					<tbody>

						@foreach ($client->timesheets->sortBy('min') as $timesheet)
							<tr>
								{!! Form::model($timesheet, ['route' => ['timesheets.update', $timesheet->id, $client->id], 'method' => 'PUT', 'class' => 'form-inline']) !!}
								<td>
									{{ Form::number('min', $timesheet->min, ['class' => 'form-control form-margin', 'readonly' => 'readonly']) }}
								</td>
								<td>
									@if ($timesheet->max != $min_value - 1)
									{{ Form::number('max', $timesheet->max, ['class' => 'form-control form-margin', 'readonly' => 'readonly']) }}
									@else
									{{ Form::number('max', $timesheet->max, ['class' => 'form-control form-margin']) }}
									@endif
								</td>
								<td>
									@if ($timesheet->max != $min_value - 1)
									{{ Form::number('price_per_hour', $timesheet->price_per_hour, ['class' => 'form-control form-margin', 'readonly' => 'readonly']) }}
									@else
									{{ Form::number('price_per_hour', $timesheet->price_per_hour, ['class' => 'form-control form-margin']) }}
									@endif
								</td>
								<td>
									@if ($timesheet->max == $min_value - 1)
										{{ Form::submit('Zmień', ['class' => 'btn btn-primary btn-sm']) }}
									@endif
								</td>
								{{ Form::close() }}
								<td>
									{!! Form::open(['route' => ['timesheets.destroy', $timesheet->id, $client->id], 'method' => 'DELETE']) !!}
									@if ($timesheet->max == $min_value - 1)
										{{ Form::submit('Usuń', ['class' => 'btn btn-danger btn-sm']) }}
									@endif
									{!! Form::close() !!}
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			<div class="well" style="padding:10px;">
				<h3>Dodawanie przedziału:</h3>
				<!-- Adding Timesheets -->
				{!! Form::open(array('route' => array('timesheets.store', $client->id), 'method' => 'PUT', 'class' => 'form-group')) !!}

					{{ Form::label('min', 'Min: ') }}
					{{ Form::number('min', $min_value, ['readonly' => 'readonly', 'class' => 'form-control']) }}

					{{ Form::label('max', 'Max: ') }}
					{{ Form::number('max', null, ['class' => 'form-control']) }}

					{{ Form::label('price_per_hour', 'Cena: ') }}
					{{ Form::number('price_per_hour', null, ['step' => 'any', 'class' => 'form-control']) }}
					{{ Form::button("Dodaj przedział", ['class' => 'btn btn-info', 'type' => 'submit']) }}

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection