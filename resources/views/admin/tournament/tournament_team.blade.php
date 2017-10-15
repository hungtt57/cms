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
                            <form enctype='multipart/form-data' action="{{url('admin/tournament_team/'.$tournament->id)}}" method="POST">
                                {{ csrf_field() }}
                                <input name="_method" type="hidden" value="POST">
                                <div class="col-md-12">
                                    <div class="well">
                                        {{ $tournament->name }}
                                        <img class="img-responsive" src="{{$tournament->Icon}}"
                                             onerror=""/>
                                    </div>
                                    <div class="tournament-team-table">
                                        <div class="tournament-team-cell">
                                            <select id="id_other_teams" name="other_teams"
                                                    class="" MULTIPLE>
                                              @foreach($other_teams as $oteam)
                                                <option value="{{ $oteam->id }}">{{ $oteam->name }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="tournament-team-middle">
                                            <input type="Button" class="btn btn-default" value="Add >>" style="width:100px"
                                                   onClick="SelectMoveRows(document.getElementById('id_other_teams'),document.getElementById('id_my_teams'))">
                                            <br>
                                            <br>
                                            <input type="Button" class="btn btn-default" value="<< Remove" style="width:100px"
                                                   onClick="SelectMoveRows(document.getElementById('id_my_teams'),document.getElementById('id_other_teams'))">
                                        </div>
                                        <div class="tournament-team-cell">
                                            <select id="id_my_teams" name="my_teams[]" class=""
                                                    MULTIPLE>
                                               @foreach($my_teams as $mteam)
                                                <option value="{{ $mteam->id }}">{{ $mteam->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" id="id_tournament_id" value="{{ $tournament->id }}"
                                           name="tournament_id">
                                    <input type='submit' value='Submit' class="btn btn-primary"
                                           onclick="selectAll()">
                                    <button type="reset" class="btn btn-default"
                                            onclick="window.open('{{url('admin/tournaments/'.$tournament->id)}}','_self')">Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- End of Panel body -->
                </section>
            </div>
            {{--<div class="col-md-4">--}}
                {{--<section class="panel">--}}
                    {{--<!-- Panel Header -->--}}
                    {{--<header class="panel-heading">--}}
                        {{--Actions--}}
                    {{--</header>--}}
                    {{--<!-- End of Panel Header -->--}}
                    {{--<!-- Panel body -->--}}
                    {{--<div class="panel-body">--}}
                        {{--<div class="row">--}}

                            {{--<div class="col-md-12">--}}

                                {{--<button type="button" class="btn btn btn-info btn-block"--}}
                                        {{--onclick="window.open('/esports/update_tournament//','_self')">--}}
                                    {{--Update Tournament--}}
                                {{--</button>--}}

                                {{--<button type="button" class="btn btn-default btn-block" href="#hidebarModal"--}}
                                        {{--data-toggle="modal">Hide Tournament--}}
                                {{--</button>--}}

                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</section>--}}
            {{--</div>--}}
        </div>
    </section>
</section>
<!-- End of Main Content -->
{{--{% if hide_bar_perm %}--}}
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
                    {{--<input name="barid" type="hidden" value="{{ barid }}">--}}
                    {{--<input name="status" type="hidden" value="2">--}}
                    {{--<input type='submit' value='Confirm' class="btn btn-warning">--}}
                {{--</form>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}
{{--{% endif %}--}}

{{--<div class="modal fade" id="errorModal" tabindex="-1">--}}
    {{--<div class="modal-dialog">--}}
        {{--<div class="modal-content">--}}
            {{--<div class="modal-header">--}}
                {{--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>--}}
                {{--<h4 class="modal-title">Error</h4>--}}
            {{--</div>--}}
            {{--<div class="modal-body">--}}
                {{--Oops! We have encountered an error...--}}
                {{--<div id="error_msg"></div>--}}
            {{--</div>--}}
            {{--<div class="modal-footer">--}}
                {{--<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}

<script type="text/javascript">
    function SelectMoveRows(SS1, SS2) {
        var SelID = '';
        var SelText = '';
        // Move rows from SS1 to SS2 from bottom to top
        for (i = SS1.options.length - 1; i >= 0; i--) {
            if (SS1.options[i].selected == true) {
                SelID = SS1.options[i].value;
                SelText = SS1.options[i].text;
                var newRow = new Option(SelText, SelID);
                SS2.options[SS2.length] = newRow;
                SS1.options[i] = null;
            }
        }
        SelectSort(SS2);
    }
    function SelectSort(SelList) {
        var ID = '';
        var Text = '';
        for (x = 0; x < SelList.length - 1; x++) {
            for (y = x + 1; y < SelList.length; y++) {
                if (SelList[x].text > SelList[y].text) {
                    // Swap rows
                    ID = SelList[x].value;
                    Text = SelList[x].text;
                    SelList[x].value = SelList[y].value;
                    SelList[x].text = SelList[y].text;
                    SelList[y].value = ID;
                    SelList[y].text = Text;
                }
            }
        }
    }

    function selectAll() {
        selectBox = document.getElementById("id_my_teams");

        for (var i = 0; i < selectBox.options.length; i++) {
            selectBox.options[i].selected = true;
        }
    }
//    google.load("visualization", "1", {packages: ["corechart", "table"]});
//    $(window).load(function () {
//        $('#bar_stats_form').submit();
//    });
//
//    $('#bar_stats_form').on('submit', function (event) {
//        event.preventDefault();
//
//        height = $('#bar_stats_div').height();
//        if (height) {
//            $('#bar_stats_cover').height(height);
//        } else {
//            $('#bar_stats_cover').height(200);
//        }
//        $('#bar_stats_div').hide();
//        $('#bar_stats_cover').show();
//
//        var form = $('#bar_stats_form');
//        $.ajax({
//            url: form.attr('action'),
//            type: form.attr('method'),
//            data: form.serialize(),
//
//            success: function (response) {
//                $('#bar_stats_div').show();
//
//                if (response['error']) {
//                    $('#error_msg').html(response['error']);
//                    $('#errorModal').modal('show');
//                } else {
//                    //console.log(response);
//                    $('#bar_stats_div').html(response);
//                }
//            },
//
//            error: function (xhr, errmsg, err) {
//                $('#error_msg').html(errmsg + ' ' + xhr.status);
//                $('#errorModal').modal('show');
//                console.log(xhr.responseText);
//            }
//        });
//    });
</script>
@endsection