@extends('admin.base')


@section('css')
    <link rel="stylesheet" type="text/css" href="{{url('tournament-admin/style/icheck/skins/all.css')}}"/>
    <script type="text/javascript"
            src="{{url('tournament-admin/plugins/jquery-datetime-picker-bygiro/dist/jquery.datetimepicker.ByGiro.js')}}"></script>
    <link rel="stylesheet" type="text/css"
          href="{{url('tournament-admin/plugins/jquery-datetime-picker-bygiro/dist/jquery.datetimepicker.ByGiro.css')}}"/>
    <script type="text/javascript"
            src="{{url('tournament-admin/plugins/jquery-validation/dist/jquery.validate.js')}}"></script>
    <script type="text/javascript" src="{{url('tournament-admin/plugins/base64/base64.js')}}"></script>
    <script type="text/javascript" src="{{url('tournament-admin/js/justcal.js')}}"></script>
@endsection

@section('content')
    <!-- Main Content -->
    <section id="main-content" class="no-transition">
        <section class="wrapper">

            <div class="row">
                <div class="col-md-10">

                    <section class="panel">
                        <!-- Panel Header -->
                        <header class="panel-heading">
                            <a href="{{url('admin/match_by_tournament/'.$match->tournament_id)}}">{{$match->tournament->name}}</a>&nbsp;/&nbsp;Update
                            Tournament Match
                        </header>
                        <!-- End of Panel Header -->
                        <!-- Panel body -->
                        <div class="panel-body">
                            <div class="row">
                                <form id="rounds-form" enctype='multipart/form-data' name='bar_create_form'
                                      action='{{url('admin/update_match_submit')}}' method='post'> {{csrf_field()}}
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    @include('admin.match.match_result')
                                    <input type='button' value='Submit' class="btn btn-primary"
                                           onclick="submit_rounds()">
                                    <button type="reset" class="btn btn-default"
                                            onclick="window.open('{{url('admin/match_profile/'.$match->id)}}','_self')">
                                        Cancel
                                    </button>
                                </form>
                            </div>
                            <!-- /.row -->
                        </div>

                        <!-- End of Panel body -->
                    </section>
                </div>
                <div class="col-md-2">
                    <div class="row fix-update-match">
                        <input type='button' value='Add Round' class="btn btn-info btn-block"
                               onclick="create_round_div()">
                        <input type="button" value="Remove Round" class="btn btn-info btn-block"
                               onclick="remove_round_div()">
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection

@section('js')
    <script type="text/javascript">

        function csrfSafeMethod(method) {
            // these HTTP methods do not require CSRF protection
            return (/^(GET|HEAD|OPTIONS|TRACE)$/.test(method));
        }
        $.ajaxSetup({
            beforeSend: function (xhr, settings) {
                if (!csrfSafeMethod(settings.type) && !this.crossDomain) {
                    xhr.setRequestHeader("X-CSRFToken", $('input[name="_token"]').val());
                }
            }
        });
        var next_round_id = '{{ $num_round + 1 }}';

        function assembleMatchResult() {
            return {
                'match_data': {'match_body': createMatchBody(), 'match_header': createMatchHeader()},
                'tournament_id': '{{ $match->tournament_id }}',
                'match_timestamp': get_match_timestamp(),
                'match_id': '{{ $match->id }}',

            };
        }

        function createMatchBody() {
            var match_body = [];
            for (i = 1; i < next_round_id; i++) {
                var round_body = {};
                var round_sel = "#id-round-" + i;
                if ($(round_sel).length) {
                    var match_teams = '{!! json_encode($match_teams) !!}';
                    match_teams = JSON.parse(match_teams);

                    var team_array = match_teams;
                    round_header = createRoundHeader(round_sel, i);
                    round_body = createRoundBody(round_sel, team_array);
                    match_body.push({'round_num': i, 'round_header': round_header, 'round_body': round_body});
                }
            }
            return match_body;
        }

            function submit_rounds() {
                if (!validate_form()) {
                    console.log('invalid form');
                    return;
                }
                var all_rounds = assembleMatchResult();
                post_url = '{{url('admin/update_match_submit')}}';
                redirect_url = '{{url('admin/match_profile/'.$match->id)}}';
                jQuery.post(post_url, {
                    'match_result': JSON.stringify(all_rounds),
                    '_token' :  $('input[name="_token"]').val(),
                }, function (data) {
                    window.location.href = redirect_url;
                }).error(function(data){
                    alert(data.responseText);
                });
            }

    </script>
    <script src="{{url('tournament-admin/js/match.js')}}"></script>
@endsection