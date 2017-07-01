<!DOCTYPE html>
<html lang="pl">

<head>
  @include('inc._head')
</head>

  <body>

    @include('inc._nav')

    <div class="container">

      @include('inc._messages')

      @yield('content')
        
    </div>
    @include('inc._js')

    @yield('scripts')

  </body>

</html>
