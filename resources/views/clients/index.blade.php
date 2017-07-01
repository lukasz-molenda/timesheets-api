@extends('welcome')

@section('content')

  <div class="container">

    <div class="row">
      <div class="col-md-12">
        
      {!! Form::open(array('route' => 'clients.store', 'class' => 'col-md-8 col-md-offset-2')) !!}

        {{ Form::label('name', 'Firma:') }}
        {{ Form::text('name', null, array('class' => 'form-control')) }}

        {{ Form::submit('Dodaj klienta', array('class' => 'btn btn-info btn-block btn-form')) }}

      {!! Form::close() !!}

      </div>
    </div>

    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <table class="table table-index text-center">
          <thead>
            <tr>
              <th>Id klienta</th>
              <th>Nazwa firmy</th>
              <th>Ilość godzin w tym miesiącu</th>
              <th>Zostało godzin</th>
              <th>Kwota</th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($clients as $client)

              <tr>
                <th>{{ $client->id }}</th>
                <td class="text-center">{{ $client->name }}</td>
                <td>{{ $client->hours }}</td>
                <td>{{ $client->max - $client->hours }}</td>
                <td>{{ $client->total }}</td>
                <td>
                  <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary btn-xs">Pokaż</a>
                </td>
                <td>
                  {!! Form::open(['route' => ['clients.destroy', $client->id], 'method' => 'DELETE']) !!}
                    {{ Form::submit('Usuń', ['class' => 'btn btn-danger btn-xs']) }}
                  {!! Form::close() !!}
                </td>
              </tr>

            @endforeach
          </tbody>
        </table>
        </div>
    </div>

  </div><!-- /.container -->

@endsection
