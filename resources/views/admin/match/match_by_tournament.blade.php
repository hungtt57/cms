@extends('admin.base')

@section('content')
<!-- Main Content -->
<section id="main-content" class="no-transition">
    <section class="wrapper">

        <div class="row">
            <div class="col-md-8">
                <section class="panel">
                    <!-- Panel Header -->
                    <header class="panel-heading">
                        Matches
                    </header>
                    <!-- End of Panel Header -->
                    <!-- Panel body -->
                    <div class="panel-body">
                        <div>
{{--                            <label>{{ last_match_date }} </label>--}}
                        </div>
                        <div id="match_list">

                            @include('admin.match.match_by_tournament_list')
                        </div>
                    </div>
                    <!-- End of Panel body -->
                </section>
            </div>
            <div class="col-md-4">
                <section class="panel">
                    <!-- Panel Header -->
                    <header class="panel-heading">
                        Actions
                    </header>
                    <!-- End of Panel Header -->
                    <!-- Panel body -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">

                                <button type="button" class="btn btn btn-info btn-block"
                                        onclick="window.open('{{url('admin/create_match/'.$id)}}','_self')">
                                    Create Match
                                </button>

                            </div>
                        </div>

                    </div>
                </section>
            </div>
        </div>

    </section>
</section>
<!-- End of Main Content -->
@endsection
@section('js')
<script type="text/javascript">


    {{--show_schedules({{last_match_timestamp}}, {{ last_match_date }});--}}

    {{--function show_schedules(last_match_timestamp, last_match_date) {--}}
        {{--$.get('/esports/manage_match_by_tournament_ajax/{{ tournament_id }}/' + last_match_timestamp + '/' + last_match_date + '/', function (data) {--}}
            {{--$('#match_list')[0].innerHTML += data;--}}

            {{--$('html, body').animate({--}}
                {{--scrollTop: $("#today_place").offset().top - 200--}}
            {{--}, 200);--}}
        {{--});--}}
    {{--}--}}
</script>

@endsection