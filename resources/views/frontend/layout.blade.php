<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Schedule</title>
    <link rel="shortcut icon" href="/tournament/img/favicon.ico"/>
    <!-- Bootstrap -->
    <link href="/tournament/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/tournament/css/style.css"/>
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link href="http://fonts.googleapis.com/css?family=Dosis:400,500,700" rel="stylesheet" type="text/css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- google fonts -->
    @yield('meta')

</head>
@yield('content')

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/tournament/js/bootstrap.min.js"></script>
<script src="/tournament/js/main.js"></script>

<script>
    var baseUrl = '{{url('/')}}';
    $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
    });

</script>

@stack('scripts')
</html>