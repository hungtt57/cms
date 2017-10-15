@extends('frontend.layout')

@section('meta')

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-61673473-9', 'auto');
        ga('send', 'pageview');

        ga('send', 'event', 'Match Details', 'pageLoad', 'lienminh247', '0');

    </script>
@endsection

@section('content')

<body>
<h1 class="hidden-xs" style="color: #FFF">Please press F12 and Toggle mobile mode</h1>
<!-- gt-wrapper -->
<div class="container hidden-lg hidden-md hidden-sm">


    <div class="row gt-block gt-header-match-detail">
        <div class="col-xs-12 text-center">
            <h1></h1>
        </div>


        <!-- Div top -->
        <div class="col-xs-12 no-gutters div-top">
            <div class="col-xs-3">
                <img class="team-logo-bg-md" src="{{ $match->tournament->icon  }}"/>
            </div>
            <div class="col-xs-6">
                <h2>{{ $match->tournament->name  }}</h2>
            </div>
            <div class="col-xs-3">
                <div class="top10 text-right"><span> {{ $match->match_timestamp->format('H:i')  }}</span></div>
                <div class="text-right"><span>{{ $match->match_timestamp->format('d/m/Y') }}</span></div>
            </div>
        </div>
    </div>


</div>
<!-- End gt-wrapper -->

<!-- End Div top-->

<div class="container wrapper">
    <div class="row match-point">
        <div class="col-xs-5 text-center ">
            <div class="h84"><img src="{{ $match->teamA->icon }}"/></div>
            <p>{{ $match->teamA->name }}</p>
        </div>
        <div class="col-xs-2 no-gutters">
            <div class="ts">
                <span>{{ $match->team_a_score }}</span>
                <span> - </span><span>{{ $match->team_b_score }}</span>
            </div>
        </div>
        <div class="col-xs-5 text-center ">
            <div class="h84"><img src="{{ $match->teamB->icon }}"/></div>
            <p>{{ $match->teamB->name }}</p>
        </div>
    </div>

    <div class="row" style="margin-top: 20px; margin-bottom: 25px;">
       @if($match->is_hot == true)
        {{--<a href="http://gpl2016.garena.vn/du-doan/{{match_detail.id}}/1"><img src="{% static "img/du-doan.jpg" %}" style="height: 30px"/></a>--}}
      @endif
    </div>

    @if(!empty($rounds))
        @foreach($rounds as $round)
            @php $roundData = json_decode($round->extra_data, true) @endphp

    <div class="row ribbon rb-default ">
        <div class="col-xs-3 no-gutters">
            <span class="match-num">Trận đấu {{ $round->round_num }}</span>
        </div>
        <div class="col-xs-6 no-gutters text-center">
            <span class="match-duration">Thời gian {{ $round->duration }}</span>
        </div>
        <div class="col-xs-3 text-left no-gutters ">
             @if(!empty($round->video_link))
            <a href="{{ $round->video_link }}">
                <span class="view-rp">Xem lại</span>
                <img class="playimg" src="/tournament/img/play.png">
            </a>
           @endif
        </div>
    </div>
    <div class="row match-detail-overview">
        <div class="col-xs-4 no-gutters">
            <div class="cadidate won">

                @php $blueTeam = \App\Garena\Functions::getTeamByColor($round, 'blue') @endphp

                <div class="logo-sm">
                    <img class=size50 src="{{ $blueTeam['data']->icon }}"/>
                </div>
                <div class="chiso">

                    @if($blueTeam['side'] == 'teamA')

                    <span>{{ $roundData['team-a-name']['total_k'] }}/{{ $roundData['team-a-name']['total_d'] }}/{{ $roundData['team-a-name']['total_a'] }}</span>

                        @else
                        <span>{{ $roundData['team-b-name']['total_k'] }}/{{ $roundData['team-a-name']['total_d'] }}/{{ $roundData['team-a-name']['total_a'] }}</span>
                    @endif
                </div>

            </div>
        </div>

        @if(\App\Garena\Functions::getTeamWon($round, 'blue') == 'team_b_won')
        <div class="col-xs-4 no-gutters tyso"><span>Thắng - Thua</span></div>
       @elseif (\App\Garena\Functions::getTeamWon($round, 'red') == 'team_a_won')
        <div class="col-xs-4 no-gutters tyso"><span>Thua - Thắng</span></div>
       @else
        <div class="col-xs-4 no-gutters tyso"></div>
        @endif

        @php $redTeam = \App\Garena\Functions::getTeamByColor($round, 'red') @endphp
        <div class="col-xs-4 no-gutters">
            <div class="cadidate lost">
                <div class="logo-sm">
                    <img class=size50 src="{{ $redTeam['data']->icon }}"/>
                </div>
                <div class="chiso">
                    @if($redTeam['side'] == 'teamA')

                        <span>{{ $roundData['team-a-name']['total_k'] }}/{{ $roundData['team-a-name']['total_d'] }}/{{ $roundData['team-a-name']['total_a'] }}</span>

                    @else
                        <span>{{ $roundData['team-b-name']['total_k'] }}/{{ $roundData['team-a-name']['total_d'] }}/{{ $roundData['team-a-name']['total_a'] }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row div-hero">
        <div class="col-xs-5 hero-won no-gutters text-right top10">
            <div class="hero5">

                @if($blueTeam['side'] == 'teamA')

                <span><img src="{{ \App\Garena\Functions::getChampionIcon($roundData['team-a-name']['1']['champion_icon']) }}"/> </span>
                <span><img src="{{ \App\Garena\Functions::getChampionIcon($roundData['team-a-name']['2']['champion_icon']) }}"/></span>
                <span><img src="{{ \App\Garena\Functions::getChampionIcon($roundData['team-a-name']['3']['champion_icon']) }}"/></span>
                <span><img src="{{ \App\Garena\Functions::getChampionIcon($roundData['team-a-name']['4']['champion_icon']) }}"/></span>
                <span><img src="{{ \App\Garena\Functions::getChampionIcon($roundData['team-a-name']['5']['champion_icon']) }}"/></span>

                    @else

                    <span><img src="{{ \App\Garena\Functions::getChampionIcon($roundData['team-b-name']['1']['champion_icon']) }}"/> </span>
                    <span><img src="{{ \App\Garena\Functions::getChampionIcon($roundData['team-b-name']['2']['champion_icon']) }}"/></span>
                    <span><img src="{{ \App\Garena\Functions::getChampionIcon($roundData['team-b-name']['3']['champion_icon']) }}"/></span>
                    <span><img src="{{ \App\Garena\Functions::getChampionIcon($roundData['team-b-name']['4']['champion_icon']) }}"/></span>
                    <span><img src="{{ \App\Garena\Functions::getChampionIcon($roundData['team-b-name']['5']['champion_icon']) }}"/></span>

                @endif
            </div>
            <div class="hero3">
                @if($blueTeam['side'] == 'teamA')
                <span><img src="{{ \App\Garena\Functions::getHeroIcon($roundData['team-a-name']['ban_1_icon']) }}"/> </span>
                    <span><img src="{{ \App\Garena\Functions::getHeroIcon($roundData['team-a-name']['ban_2_icon']) }}"/> </span>
                    <span><img src="{{ \App\Garena\Functions::getHeroIcon($roundData['team-a-name']['ban_3_icon']) }}"/> </span>
                @else
                    <span><img src="{{ \App\Garena\Functions::getHeroIcon($roundData['team-b-name']['ban_1_icon']) }}"/> </span>
                    <span><img src="{{ \App\Garena\Functions::getHeroIcon($roundData['team-b-name']['ban_2_icon']) }}"/> </span>
                    <span><img src="{{ \App\Garena\Functions::getHeroIcon($roundData['team-b-name']['ban_3_icon']) }}"/> </span>
                    @endif

            </div>
        </div>

        <div class="col-xs-5 hero-lost no-gutters col-xs-offset-2 text-left top10">
            <div class="hero5">
            @if($redTeam['side'] == 'teamB')

                    <span><img src="{{ \App\Garena\Functions::getChampionIcon($roundData['team-b-name'][1]['champion_icon']) }}"/> </span>
                    <span><img src="{{ \App\Garena\Functions::getChampionIcon($roundData['team-b-name']['2']['champion_icon']) }}"/></span>
                    <span><img src="{{ \App\Garena\Functions::getChampionIcon($roundData['team-b-name']['3']['champion_icon']) }}"/></span>
                    <span><img src="{{ \App\Garena\Functions::getChampionIcon($roundData['team-b-name']['4']['champion_icon']) }}"/></span>
                    <span><img src="{{ \App\Garena\Functions::getChampionIcon($roundData['team-b-name']['5']['champion_icon']) }}"/></span>
            @else

                    <span><img src="{{ \App\Garena\Functions::getChampionIcon($roundData['team-a-name']['1']['champion_icon']) }}"/> </span>
                    <span><img src="{{ \App\Garena\Functions::getChampionIcon($roundData['team-a-name']['2']['champion_icon']) }}"/></span>
                    <span><img src="{{ \App\Garena\Functions::getChampionIcon($roundData['team-a-name']['3']['champion_icon']) }}"/></span>
                    <span><img src="{{ \App\Garena\Functions::getChampionIcon($roundData['team-a-name']['4']['champion_icon']) }}"/></span>
                    <span><img src="{{ \App\Garena\Functions::getChampionIcon($roundData['team-a-name']['5']['champion_icon']) }}"/></span>

            @endif
                </div>
            <div class="hero3">
                @if($redTeam['side'] == 'teamA')
                    <span><img src="{{ \App\Garena\Functions::getHeroIcon($roundData['team-a-name']['ban_1_icon']) }}"/> </span>
                    <span><img src="{{ \App\Garena\Functions::getHeroIcon($roundData['team-a-name']['ban_2_icon']) }}"/> </span>
                    <span><img src="{{ \App\Garena\Functions::getHeroIcon($roundData['team-a-name']['ban_3_icon']) }}"/> </span>
                @else
                    <span><img src="{{ \App\Garena\Functions::getHeroIcon($roundData['team-b-name']['ban_1_icon']) }}"/> </span>
                    <span><img src="{{ \App\Garena\Functions::getHeroIcon($roundData['team-b-name']['ban_2_icon']) }}"/> </span>
                    <span><img src="{{ \App\Garena\Functions::getHeroIcon($roundData['team-b-name']['ban_3_icon']) }}"/> </span>
                @endif

            </div>
        </div>
    </div>

    <div class="row detail-match">

        <a class="gt-btn btn-more" role="button" data-toggle="collapse"
           href="#detail-match{{ $round->round_num }}"
           aria-expanded="false"
           aria-controls="collapseExample">
            Thông tin thêm <span class="caret"></span>
        </a>

        <div class="collapse" id="detail-match{{ $round->round_num }}">
            <div class="col-xs-12 team team-lost-header">
                <h1>{{ $redTeam['data']->name }}</h1>
            </div>

            @php
            $playerData = \App\Garena\Functions::getPlayerData($round, $redTeam['side']);
                    @endphp
           @if(!empty($playerData))
                @for ($i = 1; $i <=5; $i++)
            <div class="hero-list">
                <div class="hero-list-item col-xs-12">
                    <div class="hero-img">
                        <img src="{{ \App\Garena\Functions::getChampionIcon($playerData[$i]['champion_icon']) }}">
                    </div>
                    <div class="no-gutters hero-name">
                        <div>
                            {{--<span>{{ player_data | get_player_name:red_team.member_type }}</span>--}}
                        </div>
                        <br>

                        <div>
                            <span>{{ $playerData[$i]['lolK'] }}/{{$playerData[$i]['lolD'] }}/{{ $playerData[$i]['lolA'] }}</span>
                        </div>
                    </div>
                    <div class="no-gutters hero-pbt">
                                                    <span><img
                                                                src="{{ \App\Garena\Functions::getSpellIcon($playerData[$i]['spell_1_icon']) }}"> </span>
                        <span><img
                                    src="{{ \App\Garena\Functions::getSpellIcon($playerData[$i]['spell_2_icon']) }}"> </span>
                        <span><img
                                    src="{{ \App\Garena\Functions::getMasteryIcon($playerData[$i]['mastery_icon']) }}"> </span>
                    </div>
                    <div class="hero-items no-gutters">
                                                <span class="stat">
                                                        <span><img
                                                                    src="/tournament/img/gold.png"/>{{ $playerData[$i]['gold'] }}  </span>
                                                        <span><img
                                                                    src="/tournament/img/creep.png"/>{{ $playerData[$i]['minions'] }} </span>
                                                </span><br>
                        <span class="item">
                                                    <span><img
                                                                src="{{ \App\Garena\Functions::getItemIcon($playerData[$i]['item_1_icon']) }}"/> </span>
                                                    <span><img
                                                                src="{{ \App\Garena\Functions::getItemIcon($playerData[$i]['item_2_icon']) }}"/> </span>
                                                    <span><img
                                                                src="{{ \App\Garena\Functions::getItemIcon($playerData[$i]['item_3_icon']) }}"/> </span>
                                                    <span><img
                                                                src="{{ \App\Garena\Functions::getItemIcon($playerData[$i]['item_4_icon']) }}"/> </span>
                                                    <span><img
                                                                src="{{ \App\Garena\Functions::getItemIcon($playerData[$i]['item_5_icon']) }}"/> </span>
                                                    <span><img
                                                                src="{{ \App\Garena\Functions::getItemIcon($playerData[$i]['item_6_icon']) }}"/> </span>
                                                </span>
                    </div>
                </div>
            </div>
          @endfor
          @endif
            @php
                $playerData = \App\Garena\Functions::getPlayerData($round, $blueTeam['side']);
            @endphp
            <div class="col-xs-12 team team-win-header">
                <h1>{{ $blueTeam['data']->name }}</h1>
            </div>
            @if(!empty($playerData))
                @for ($i = 1; $i <=5; $i++)
                    <div class="hero-list">
                        <div class="hero-list-item col-xs-12">
                            <div class="hero-img">
                                <img src="{{ \App\Garena\Functions::getChampionIcon($playerData[$i]['champion_icon']) }}">
                            </div>
                            <div class="no-gutters hero-name">
                                <div>
                                    {{--<span>{{ player_data | get_player_name:red_team.member_type }}</span>--}}
                                </div>
                                <br>

                                <div>
                                    <span>{{ $playerData[$i]['lolK'] }}/{{$playerData[$i]['lolD'] }}/{{ $playerData[$i]['lolA'] }}</span>
                                </div>
                            </div>
                            <div class="no-gutters hero-pbt">
                                                    <span><img
                                                                src="{{ \App\Garena\Functions::getSpellIcon($playerData[$i]['spell_1_icon']) }}"> </span>
                                <span><img
                                            src="{{ \App\Garena\Functions::getSpellIcon($playerData[$i]['spell_2_icon']) }}"> </span>
                                <span><img
                                            src="{{ \App\Garena\Functions::getMasteryIcon($playerData[$i]['mastery_icon']) }}"> </span>
                            </div>
                            <div class="hero-items no-gutters">
                                                <span class="stat">
                                                        <span><img
                                                                    src="/tournament/img/gold.png"/>{{ $playerData[$i]['gold'] }}  </span>
                                                        <span><img
                                                                    src="/tournament/img/creep.png"/>{{ $playerData[$i]['minions'] }} </span>
                                                </span><br>
                                <span class="item">
                                                    <span><img
                                                                src="{{ \App\Garena\Functions::getItemIcon($playerData[$i]['item_1_icon']) }}"/> </span>
                                                    <span><img
                                                                src="{{ \App\Garena\Functions::getItemIcon($playerData[$i]['item_2_icon']) }}"/> </span>
                                                    <span><img
                                                                src="{{ \App\Garena\Functions::getItemIcon($playerData[$i]['item_3_icon']) }}"/> </span>
                                                    <span><img
                                                                src="{{ \App\Garena\Functions::getItemIcon($playerData[$i]['item_4_icon']) }}"/> </span>
                                                    <span><img
                                                                src="{{ \App\Garena\Functions::getItemIcon($playerData[$i]['item_5_icon']) }}"/> </span>
                                                    <span><img
                                                                src="{{ \App\Garena\Functions::getItemIcon($playerData[$i]['item_6_icon']) }}"/> </span>
                                                </span>
                            </div>
                        </div>
                    </div>
                @endfor
            @endif

        </div>

    </div>
        @endforeach
        @endif
</div>


</body>
    @endsection
