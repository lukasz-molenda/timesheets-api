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
	</div>
	<div class="panel panel-info">
				<div class="panel-heading text-center">Edycja roboczogodzin dla projektów</div>
				<div class="panel-body">
					<p class="lead">Dodaj godziny do projektu:</p>
					{!! Form::open(['route' => ['time_entry.store', $client->id], 'method' => 'POST', 'class' => 'form-group col-md-12']) !!}

						{{ Form::label('project_id', 'Projekt:') }}
						<select class="form-control" name="project_id">
							@foreach($projects as $project)
								<option value="{{ $project->id }}">{{ $project->name }}</option>
							@endforeach
						</select>

						{{ Form::label('entry_date', 'Data') }}
						{{ Form::date('entry_date', Carbon\Carbon::now(), ['class' => 'form-control']) }}

						{{ Form::label('hours', 'Liczba godzin') }}
						{{ Form::number('hours', null, ['class' => 'form-control']) }}

						{{ Form::hidden('total', $client->total) }}

						{{ Form::button("Dodaj", ['class' => 'btn btn-info', 'type' => 'submit']) }}
						
					{!! Form::close() !!}
					<p class="lead">Lista prac (w godzinach) w tym miesiącu:</p>
					{!! Form::open(['route' => ['projects.change_month', $client->id], 'class' => 'form-group col-md-12']) !!}
						{{ Form::label('month', 'Zmień miesiąc:') }}
						<input type="month" name="month" class="form-control" value="{{$carbon}}">

						{{ Form::submit('Akceptuj', ['class' => 'btn btn-info']) }}
					{!! Form::close() !!}
						<div class="row">
							<div class="container">
							<h3>Łączna liczba godzin w tym miesiącu: {{array_sum(array_column($time_entries->toArray(), 'hours'))}}</h3>
							</div>
						</div>
							<table class="table">
								<thead>
									<th>Data</th>
									<th>Nazwa projektu</th>
									<th>Godziny</th>
									<th></th>
								</thead>
								<tbody>
									@foreach ($time_entries_pag as $time_entry)
										<tr>
											<td>{{$time_entry->entry_date}}</td>
											<td>{{$time_entry->project->name}}</td>
											<td>{{$time_entry->hours}}</td>
											<td>
												{!! Form::open(['route' => ['time_entries.destroy', $client->id, $time_entry->id], 'method' => 'DELETE']) !!}
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