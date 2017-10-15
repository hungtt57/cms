<!DOCTYPE html>
<html lang="en">
<head>
  @include('_partials/metadata')
  @section('styles_head')
    @include('_partials/styles_head')
  @show
  @section('scripts_head')
    @include('_partials/scripts_head')
  @show
</head>

<body>
  @include('_partials/header')
  @include('_partials/navbar')
  @yield('content')
  @include('_partials/footer')
  @section('styles_foot')
    @include('_partials/styles_foot')
  @show
  @include('_partials/scripts_foot')
</body>
</html>
