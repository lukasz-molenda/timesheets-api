@extends('welcome')

@section('content')

<div class="container">
	<div class="row">
		<div class="col-md-4">
			<div class="panel panel-primary">
				<div class="panel-heading text-center">
					Godziny przepracowane w tym miesiącu
				</div>
				<div class="panel-body">
					<div class="well">
						{{ $client->hours }}
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="panel panel-primary">
				<div class="panel-heading text-center">
					Pozostałe godziny
				</div>
				<div class="panel-body">
					<div class="well">
						{{ ($client->max - $client->hours) > 0 ? ($client->max - $client->hours) : 0 }}
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="panel panel-primary">
				<div class="panel-heading text-center">
					Kwota do zapłaty
				</div>
				<div class="panel-body">
					<div class="well">
						{{ $client->total }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection