<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <link rel="shortcut icon" href="">
    <title>Tournament Admin</title>

    <link href="{{url('tournament-admin/plugins/bootstrap/dist/css/bootstrap.css')}}" rel="stylesheet">
    <link href="{{url('tournament-admin/style/custom-tournament.css')}}" rel="stylesheet">
    <link href="{{url('tournament-admin/style/custom-forum.css')}}" rel="stylesheet">
    <link href="{{url('tournament-admin/style/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{url('tournament-admin/style/style.css')}}" rel="stylesheet">
    <link href="{{url('tournament-admin/style/style-cleaned.css')}}" rel="stylesheet">
    <link href="{{url('tournament-admin/plugins/jasny-bootstrap/dist/css/jasny-bootstrap.css')}}" rel="stylesheet">


    <script src="{{url('tournament-admin/plugins/jquery/dist/jquery.js')}} "></script>
    <script src="{{url('tournament-admin/plugins/jquery-ui/jquery-ui.js')}}"></script>
    <script src="{{url('tournament-admin/plugins/jasny-bootstrap/dist/js/jasny-bootstrap.js')}} "></script>
    <script src="{{url('tournament-admin/plugins/bootstrap/dist/js/bootstrap.js')}} "></script>
    <script src="{{url('tournament-admin/js/loldata.js')}}"></script>
    <script src="{{url('tournament-admin/js/team.js')}}"></script>
    <script src="{{url('tournament-admin/js/countries.js')}}"></script>
    <script src="{{url('tournament-admin/js/followed.js')}}"></script>

    {{--<link href="{{url('tournament-admin/style/custom-forum.css')}}" rel="stylesheet">--}}
    {{--<link href="{{url('tournament-admin/style/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">--}}
    {{--<link href="{{url('tournament-admin/style/style.css')}}" rel="stylesheet">--}}
    {{--<link href="{{url('tournament-admin/style/style-cleaned.css')}}" rel="stylesheet">--}}
    {{--<link href="{{url('tournament-admin/style/jasny-bootstrap.min.css')}}" rel="stylesheet">--}}
    {{--<script src="{{url('tournament-admin/js/jquery-1.11.0.min.js')}}"></script>--}}
    {{--<script src="//code.jquery.com/jquery-1.10.2.js"></script>--}}
    {{--<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>--}}
    @yield('css')
</head>

<body>
<section id="container">
    @include('admin.header')
    @include('admin.sidebar')

    @yield('content')
</section>

<!-- Placed js at the end of the document so the pages load faster -->

{{--<script src="{{url('tournament-admin/js/jasny-bootstrap.min.js')}}"></script>--}}
{{--<script src="{{url('tournament-admin/js/bootstrap.min.js')}}"></script>--}}
{{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>--}}
{{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>--}}
{{--<script src="{{url('tournament-admin/js/jquery.dcjqaccordion.2.7.js')}}"></script>--}}
{{--<script src="{{url('tournament-admin/js/jquery.scrollTo.min.js')}}"></script>--}}
{{--<script src="{{url('tournament-admin/js/jquery.slimscroll.js')}}"></script>--}}
{{--<script src="{{url('tournament-admin/js/jquery.nicescroll.js')}}"></script>--}}
{{--<script src="{{url('tournament-admin/js/icheck.js')}}"></script>--}}
{{--<script src="{{url('tournament-admin/js/jasny-bootstrap.min.js')}}"></script>--}}
{{--<script src="{{url('tournament-admin/js/scripts.js')}}"></script>--}}
{{--<script src="{{url('tournament-admin/js/jquery.form.js')}}"></script>--}}
@yield('js')
</body>
</html>
