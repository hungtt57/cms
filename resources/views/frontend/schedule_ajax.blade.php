<!-- All match-->
<div>

    @foreach($dates as $date => $matches)
    <div class="col-xs-12 match-status">
        <div class="col-xs-12 text-center">


            @if(\Carbon\Carbon::createFromFormat('d-m-Y', $date)->gte(\Carbon\Carbon::now()))
            <button id="all_match_place"
                    class="gt-btn gt-btn-today">{{ $date }}</button>
            @else
                <button
                        class="gt-btn gt-btn-past">{{ $date }}</button>
            @endif

        </div>
</div>


    <div class="team-match-block">

            @php $previousTournamentId = null @endphp
            @foreach($matches as $match)

            @if($match->tournament->id != $previousTournamentId)
                @php $previousTournamentId = $match->tournament->id @endphp
            @if(\Carbon\Carbon::createFromFormat('d-m-Y', $date)->gte(\Carbon\Carbon::now()))
                <div class="col-xs-12 match-header match-header-today" data-tournament-id="{{ $match->tournament->id }}">
                    @else
                        <div class="col-xs-12 match-header">
                            @endif

                <div class="col-xs-4 match-header-left">
                    <img class="team-logo-bg" src="{{ $match->tournament->icon }}"/>
                </div>
                <div class="col-xs-8 match-header-right">
                    <h1> {{ $match->tournament->name }}</h1>
                </div>




            </div>
                    @endif


            <a href="{{ url('esports/match-detail', ['match_id' => $match->id]) }}">
            <div class="match-point-list">
                <div class="col-xs-12 match-point @if($loop->index % 2 > 0) odd @endif">
                <div class="col-xs-2 team-avatar-sm-left">
                    <img src="{{ $match->teamA->icon }}"/>
                </div>
                <div class="col-xs-3 name-left no-gutters">
                    <span>{{ $match->teamA->name }}</span>
                </div>
                @if($match->status == config('constants.ENDED'))
                <div class="col-xs-2 no-gutters">
                    <div><span
                                class="left-point">{{ $match->team_a_score }}</span>
                        -
                        <span class="left-point">{{ $match->team_b_score }}</span>
                    </div>
                    <div>
                        <span>Đã kết thúc</span>
                    </div>
                </div>
                @elseif($match->status == config('constants.PLAYING'))
                <div class="col-xs-2 no-gutters">
                    <div><span
                                class="left-point">{{ $match->team_a_score }}</span> -
                        <span class="left-point">{{ $match->team_b_score }}</span>
                    </div>
                    <div class="clearleft"><span class="gt-playing">Playing</span></div>
                </div>
                @elseif($match->status == config('constants.VISIBLE'))
                <div class="col-xs-2 no-gutters">

                    <div class="time-blue"><span>{{ $match->match_timestamp->format('H:i') }}</span>
                    </div>
                </div>
               @endif
                <div class="col-xs-3 name-right no-gutters">
                    <span>{{ $match->teamB->name }}</span>
                </div>
                <div class="col-xs-2 team-avatar-sm-right">
                    <span><img src="{{ $match->teamB->icon }}" ></span>
                </div>
               @if($match->is_hot == true)
                <span class="hot" style="position: absolute; top: 0; right: 0"><img src="/tournament/img/hot.png"></span>
                @endif

            </div>
        </div>
        </a>
            @endforeach

    </div>




            @endforeach
        </div>
        </div>