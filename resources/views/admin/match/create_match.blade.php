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

            @if(count($tournament_teams) > 0)
            <div class="row">
                <div class="col-md-10">

                    <section class="panel">
                        <!-- Panel Header -->
                        <header class="panel-heading">
                            <a href="{{url('admin/match_by_tournament/'.$tournament->id)}}">{{ $tournament->id }}</a>&nbsp;/&nbsp;Create
                            a New Tournament Schedule
                        </header>
                        <!-- End of Panel Header -->
                        <!-- Panel body -->
                        <div class="panel-body">
                            <div class="row">
                                <form id="rounds-form" enctype='multipart/form-data' name='bar_create_form'
                                      action="{{url('admin/create_match_submit')}}" method='post'>
                                    {{--<input type="hidden" value="{{ tournament_id }}">--}}
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                    @include('admin.match.match_result')

                                    <input type='button' value='Submit' class="btn btn-primary"
                                           onclick="submit_rounds()">
                                    <button type="reset" class="btn btn-default"
                                            onclick="window.open('{{url('admin/match_by_tournament/'.$tournament->id)}}','_self')">Cancel
                                    </button>
                                </form>
                            </div>
                            <!-- /.row -->
                        </div>

                        <!-- End of Panel body -->
                    </section>
                </div>
                <div class="col-md-2">
                    <div class="panel-body">
                        <div class="row">
                            <input type='button' value='Add Round' class="btn btn-info btn-block"
                                   onclick="create_round_div()">
                            <input type="button" value="Remove Round" class="btn btn-info btn-block"
                                   onclick="remove_round_div()">
                        </div>
                    </div>
                </div>
            </div>
                @else
                <div class="row">
                    <h1>Giải đấu chưa có team!!! Vui lòng chọn team cho giải đấu</h1>
                    <h2>Vui lòng click  <a href="{{url('admin/tournament_team/'.$tournament->id)}}">vào đây</a></h2>
                </div>
            @endif
        </section>
    </section>
    <!-- End of Main Content -->


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


        var next_round_id = 1;


        function submit_rounds() {
            if (!validate_form()) {
                console.log('invalid form');
                return;
            }
           if(get_match_timestamp()==''){
               alert('Vui lòng chọn thời gian trận đấu !!')
               return;
           }
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
            var all_rounds = {
            'match_data': {'match_body': match_body, 'match_header': createMatchHeader()},
            'tournament_id':'{{ $tournament->id }}',
            'match_timestamp': get_match_timestamp()
            };
            post_url = '{{url('admin/create_match_submit')}}';
            redirect_url = '{{url('admin/match_by_tournament/'.$tournament->id)}}';
            jQuery.post(post_url, {
            'match_result': JSON.stringify(all_rounds),
                '_token' :  $('input[name="_token"]').val(),
            }, function (data) {
            window.location.href = redirect_url;
            }).error(function (data) {
            alert(data.responseText);
            });
        }
    </script>
    <script src="{{url('tournament-admin/js/match.js')}}"></script>
@endsection