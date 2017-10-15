<!DOCTYPE html>
<html lang="en">
    @extends('frontend.layout')

    @section('meta')

        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-61673473-9', 'auto');
            ga('send', 'pageview');

            ga('send', 'event', 'Team & Tournaments', 'pageLoad', 'lienminh247', '0');

        </script>
    @endsection

    @section('content')


<body>

<h1 class="hidden-xs" style="color: #FFF">Please press F12 and Toggle mobile mode</h1>

<div class="container">
    <div class="row gt-block gt-header hidden-lg hidden-md hidden-sm">
        <div class="col-xs-12 text-center">
            <h1>Các đội và giải đáu</h1>
        </div>

        <!-- Button trigger modal -->


        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Chọn đội tuyển yêu thích của bạn</h4>
                    </div>
                    <div class="modal-body">
                        Theo dõi một đội tuyển để nhận thông báo về trận đấu, tin tức và kết quả<br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn gt-btn gt-btn-yes" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row panel-group gt-body hidden-lg hidden-md hidden-sm" id="accordion" role="tablist"
         aria-multiselectable="true">
       @foreach($tournaments as $tournament)
        <div class="panel panel-default ">

            <div class="panel-heading gt-tour tournament-back"
                 style="background-image: url({{ $tournament->banner  }})"
                 role="tab" id="heading{{ $tournament->id }} " data-toggle="collapse" data-parent="#accordion"
                 href="#collapse{{ $tournament->id }}" aria-expanded="true"
                 aria-controls="collapse{{ $tournament->id }}">

                    <div class="row">
                        <div class="col-xs-8 text-left gt-tour-left">
                            <h1>{{ $tournament->name }}</h1>
                            <button class="gt-btn btn-down-div btn-down" role="button">
                                {{ $tournament->teams->count() }} đội
                            </button>
                        </div>

                        @if(\App\Garena\Functions::checkTournamentFollowed($tournament->id))

                        <div class="col-xs-4 text-right gt-tour-right">
                            <a class="follow-choice">
                                <input type="hidden" id="tournament-type" value="{{ $tournament->id }}">
                                <button class="gt-btn btn-checked">
                                    <span>&nbsp;</span>
                                </button>
                            </a>
                        </div>
                            @else
                            <div class="col-xs-4 text-right gt-tour-right">
                                <a class="follow-choice">
                                    <input type="hidden" id="tournament-type" value="{{ $tournament->id }}">
                                    <button class="gt-btn btn-follow">
                                        <span>Theo dõi</span>
                                    </button>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div id="collapse{{ $tournament->id }}" class="panel-collapse collapse in " role="tabpanel"
                     aria-labelledby="heading{{ $tournament->id }}">
                        <div class="panel-body gt-panel-body">
                            <ul class="team-list">
                                @foreach($tournament->teams as $team)
                                <li class="team-list-item">
                                    <div class="row">
                                        <div class="col-xs-2">
                                            <img class="team-avatar" src="{{ $team->icon }}"/>
                                        </div>
                                        <div class="col-xs-6 text-left">
                                            <h4><b>{{ $team->name }}</b></h4>

                                            <p>{{ $team->country }}</p>
                                        </div>
                                        @if(\App\Garena\Functions::checkTeamFollowed($team->id))
                                        <div class="col-xs-4 text-right no-gutters">
                                            <a class="follow-choice">
                                                <input type="hidden" id="team-type" value="{{$team->id }}">
                                                <button class="gt-btn btn-checked">
                                                    <span>&nbsp;</span>
                                                </button>
                                            </a>
                                        </div>
                                            @else
                                            <div class="col-xs-4 text-right no-gutters">
                                                <a class="follow-choice">
                                                    <input type="hidden" id="team-type" value="{{$team->id }}">
                                                    <button class="gt-btn btn-follow">
                                                        <span>Theo dõi</span>
                                                    </button>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </li>
                              @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
               @endforeach
            </div>
        </div>

        @push('scripts')
        <script>

            $(document).ready(function(){
//                {% if popup_follow %}
//                $('#myModal').modal();
//                {% endif %}
            });

            var follow_click = (function () {
                var to_followed;

                return function (e) {

                    if ($(this).find("button").hasClass('btn-checked')) {
                        to_followed = false;
                        console.log("from followed to not followed");
                    }
                    else {
                        to_followed = true;
                        console.log("from not followed to followed");
                    }
                    console.log($(this).find("button").attr("class"));
                    if (to_followed) {
                        if ($(this).find("input").attr("id") == "tournament-type") {
                            choose_follow("follow_tournament", $(this).find("input").val(), false);
                            $(this).find("button").removeClass("btn-follow");
                            $(this).find("button").addClass("btn-checked");
                        }
                        else if ($(this).find("input").attr("id") == "team-type") {
                            choose_follow("follow_team", $(this).find("input").val(), false);
                            $(this).find("button").removeClass("btn-follow");
                            $(this).find("button").addClass("btn-checked");
                        }
                        else {
                            console.log("can not find valid type");
                        }
                        $(this).find("button").empty();
                        $(this).find("button").append('<span>&nbsp;</span>')
                    }
                    else {
                        $(this).find("img").attr("src", "/tournament/img/btn_follow.png");
                        if ($(this).find("input").attr("id") == "tournament-type") {
                            choose_follow("unfollow_tournament", $(this).find("input").val(), false);
                            $(this).find("button").removeClass("btn-checked");
                            $(this).find("button").addClass("btn-follow");
                        }
                        else if ($(this).find("input").attr("id") == "team-type") {
                            choose_follow("unfollow_team", $(this).find("input").val(), false);
                            $(this).find("button").removeClass("btn-checked");
                            $(this).find("button").addClass("btn-follow");
                        }
                        else {
                            console.log("can not find valid type");
                        }
                        $(this).find("button").empty();
                        $(this).find("button").append('<span>Theo dõi</span> ')
                    }
                }
            })();

            function choose_follow(type, id) {
                post_url = "/esports/" + type ;

                $.ajax({
                    type: 'post',
                    url: post_url,
                    data: {
                        'id': id
                    },
                    success: function(response)
                    {
                        console.log('post follow  success', id);
                    }
                });
            }

            $(document).ready(function () {
                $(".follow-choice").each(function () {
                    $(this).click(follow_click);
                });
            })

        </script>
        @endpush

</body>
@endsection
