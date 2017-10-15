<!DOCTYPE html>
<!--

 o8o    .oooooo.   oooo                            oooo
 `"'   d8P'  `Y8b  `888                            `888
oooo  888           888 .oo.    .ooooo.   .ooooo.   888  oooo
`888  888           888P"Y88b  d88' `88b d88' `"Y8  888 .8P'
 888  888           888   888  888ooo888 888        888888.
 888  `88b    ooo   888   888  888    .o 888   .o8  888 `88b.
o888o  `Y8bood8P'  o888o o888o `Y8bod8P' `Y8bod8P' o888o o888o

-->
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

<body class="login-container">
  @yield('content')
  @include('_partials/footer')
  @section('styles_foot')
    @include('_partials/styles_foot')
  @show
  @include('_partials/scripts_foot')
</body>
</html>
