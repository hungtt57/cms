@extends('admin.base')

@section('css')
<link rel="stylesheet" type="text/css" href="{{url('tournament-admin/style/tcal.css')}}"/>
<script type="text/javascript" src="{{url('tournament-admin/js/tcal.js')}}"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

@endsection
@section('content')
<!-- Main Content -->
<section id="main-content" class="no-transition">
    <section class="wrapper">
        <div class="row">
            <div class="col-md-8">
                <section class="panel">
                    <!-- Panel Header -->
                    <header class="panel-heading">
                        <a href="{{url('admin/tournaments')}}">Tournaments</a>&nbsp;/&nbsp;{{ $tournament->name }}
                    </header>
                    <!-- End of Panel Header -->
                    <!-- Panel body -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="well">
                                    <h4>Name</h4>
                                    {{ $tournament->name }}
                                </div>
                                <div class="well">
                                    <h4>Suffix</h4>
                                    {{ $tournament->suffix }}
                                </div>
                                <div class="well">
                                    <h4>Description</h4>
                                    {{ $tournament->description }}
                                </div>
                                <div class="well">
                                    <h4>Leader Board</h4>
                                    <img class="img-responsive" src="{{$tournament->Logo }}"
                                         onerror=""/>
                                </div>
                                <div class="well">
                                    <h4>Banner</h4>
                                    <img class="img-responsive" src="{{$tournament->Banner}}"
                                         onerror=""/>
                                </div>
                                <div class="well">
                                    <h4>Icon</h4>
                                    <img class="img-responsive"src="{{$tournament->Icon}}"onerror=""/>
                                </div>
                            </div>
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
                                        onclick="window.open('{{url('admin/tournaments/'.$tournament->id.'/edit')}}','_self')">
                                    Update Tournament
                                </button>


                                {{--<button type="button" class="btn btn-default btn-block" href="#hidebarModal"--}}
                                        {{--data-toggle="modal">Hide Tournament--}}
                                {{--</button>--}}



                                <button type="button" class="btn btn btn-info btn-block"
                                        onclick="window.open('{{url('admin/match_by_tournament/'.$tournament->id)}}','_self')">
                                    Manage Matches
                                </button>

                                <button type="button" class="btn btn btn-info btn-block"
                                        onclick="window.open('{{url('admin/tournament_team/'.$tournament->id)}}','_self')">
                                    Manage Teams
                                </button>

                                <div class="[ form-group ]">
                                 @if($tournament->is_leader_board_enabled)
                                    <input type="checkbox" name="fancy-checkbox-leader-board"
                                           id="fancy-checkbox-leader-board"
                                           autocomplete="off" checked/>
                                  @else
                                    <input type="checkbox" name="fancy-checkbox-leader-board"
                                           id="fancy-checkbox-leader-board"
                                           autocomplete="off"/>
                                  @endif

                                    <div class="[ btn-group ]">
                                        <label for="fancy-checkbox-leader-board" class="[ btn btn-default ]">
                                            <span class="[ glyphicon glyphicon-ok ]"></span>
                                            <span> </span>
                                        </label>
                                        <label for="fancy-checkbox-leader-board" class="[ btn btn-default active ]">
                                            Show Leader Board
                                        </label>
                                    </div>
                                </div>
                                <div class="[ form-group ]">
                                    @if($tournament->status==\App\Tournament::ENABLED)
                                    <input type="checkbox" name="fancy-checkbox-tournament"
                                           id="fancy-checkbox-tournament"
                                           autocomplete="off" checked/>
                                    @else
                                    <input type="checkbox" name="fancy-checkbox-tournament"
                                           id="fancy-checkbox-tournament"
                                           autocomplete="off"/>
                                    @endif

                                    <div class="[ btn-group ]">
                                        <label for="fancy-checkbox-tournament" class="[ btn btn-default ]">
                                            <span class="[ glyphicon glyphicon-ok ]"></span>
                                            <span> </span>
                                        </label>
                                        <label for="fancy-checkbox-tournament" class="[ btn btn-default active ]">
                                            Show Tournament
                                        </label>
                                    </div>
                                </div>


                                {{--<button type="button" class="btn btn-block archive-tournament" href="#archivetournament"--}}
                                        {{--data-toggle="modal">--}}
                                    {{--Archive Tournament--}}
                                {{--</button>--}}


                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</section>
<!-- End of Main Content -->

{{--<div class="modal fade" id="hidebarModal" tabindex="-1">--}}
    {{--<div class="modal-dialog">--}}
        {{--<div class="modal-content">--}}
            {{--<div class="modal-header">--}}
                {{--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>--}}
                {{--<h4 class="modal-title">Hide Tournament</h4>--}}
            {{--</div>--}}
            {{--<div class="modal-body">--}}
                {{--Confirm to set this Tournament to be hidden?--}}
                {{--<label></label>--}}
            {{--</div>--}}
            {{--<div class="modal-footer">--}}
                {{--<form name="bar_status_form" action="/bar/bar_status_set/" method="POST">{% csrf_token %}--}}
                    {{--<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>--}}
                    {{--<input name="barid" type="hidden" value="">--}}
                    {{--<input name="status" type="hidden" value="2">--}}
                    {{--<input type='submit' value='Confirm' class="btn btn-warning">--}}
                {{--</form>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}



<div class="modal fade" id="archivetournament" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Archive Tournament</h4>
            </div>
            <div class="modal-body">
                Are you sure to hide this tournamnet permanently from the backend? By doing so you can no longer
                edit the tournament.
                <label></label>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                <button class="btn btn-warning" onclick="window.open('/esports/archive_tournament/{{ $tournament->id }}/','_self')">Confirm</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="errorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Error</h4>
            </div>
            <div class="modal-body">
                Oops! We have encountered an error...
                <div id="error_msg"></div>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
            </div>
        </div>
    </div>
</div>


@endsection
@section('js')
    <script type="text/javascript">
//        google.load("visualization", "1", {packages: ["corechart", "table"]});
//        $(window).load(function () {
//            $('#bar_stats_form').submit();
//        });
//
//        $('#bar_stats_form').on('submit', function (event) {
//            event.preventDefault();
//
//            height = $('#bar_stats_div').height();
//            if (height) {
//                $('#bar_stats_cover').height(height);
//            } else {
//                $('#bar_stats_cover').height(200);
//            }
//            $('#bar_stats_div').hide();
//            $('#bar_stats_cover').show();
//
//            var form = $('#bar_stats_form');
//            $.ajax({
//                url: form.attr('action'),
//                type: form.attr('method'),
//                data: form.serialize(),
//
//                success: function (response) {
//                    $('#bar_stats_div').show();
//
//                    if (response['error']) {
//                        $('#error_msg').html(response['error']);
//                        $('#errorModal').modal('show');
//                    } else {
//                        //console.log(response);
//                        $('#bar_stats_div').html(response);
//                    }
//                },
//
//                error: function (xhr, errmsg, err) {
//                    $('#error_msg').html(errmsg + ' ' + xhr.status);
//                    $('#errorModal').modal('show');
//                    console.log(xhr.responseText);
//                }
//            });
//        });

        $(document).ready(function () {
            $("#fancy-checkbox-leader-board").click(function () {
                var status = 0;
                if ($(this).prop('checked')) {
                    status = 1;
                }
                else {
                    status = 0;
                }
                jQuery.post('{{url('admin/change_tournament_leader_board_ajax')}}', {
                    'tournament_id':{{ $tournament->id }},
                    'status': status,
                    "_token": "{{ csrf_token() }}",
                }, function (data) {
                    console.log(data.responseText);
                }).error(function (data) {
                    $('#error_msg').html(data.responseText);
                    $('#errorModal').modal('show');
                    console.log(data.responseText);
                });
            });

            $("#fancy-checkbox-tournament").click(function () {
                //0-show, 1-hidden
                var status = '';
                if ($(this).prop('checked')) {
                    status = '{{\App\Tournament::ENABLED}}';
                }
                else {
                    status = '{{\App\Tournament::DISABLED}}';
                }
                jQuery.post('{{url('admin/change_tournament_status_ajax')}}', {
                    'tournament_id':{{ $tournament->id }},
                    'status': status,
                    "_token": "{{ csrf_token() }}",
                }, function (data) {
                    console.log(data.responseText);
                }).error(function (data) {
                    $('#error_msg').html(data.responseText);
                    $('#errorModal').modal('show');
                    console.log(data.responseText);
                });
            });
        });

    </script>
    @endsection