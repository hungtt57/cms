

<div id="match_result" class="{{$match_type}}">
    <div id="id-round-example" class="round-display-none">
        <p id="round-result">
            Game
            <span id="game_num"></span>
            Won
            <input id="team_a_won" name="round-won" type="radio" class="team-score" value="team_a">
            <label for="team_a_won" class="team-a-name"></label>
            <input id="team_b_won" name="round-won" type="radio" class="team-score" value="team_b">
            <label for="team_b_won" class="team-b-name"></label>
        </p>

        <p id="blue-team-color">
            <label>Blue Team:</label>
            <input id="team_a_blue_team" name="team-a-sel" type="radio" value="blue"
                   class="team-color-sel">
            <label class="team-a-name"></label>
            <input id="team_a_red_team" name="team-a-sel" type="radio" value="red"
                   class="team-color-sel">
            <label class="team-b-name"></label>
        </p>

        <p id="red-team-color">
            <label>Red Team:</label>
            <input id="team_b_blue_team" name="team-b-sel" type="radio" value="blue"
                   class="team-color-sel">
            <label class="team-a-name"></label>
            <input id="team_b_red_team" name="team-b-sel" type="radio" value="red"
                   class="team-color-sel">
            <label class="team-b-name"></label>
        </p>

        <p id="round-status" class="game-status">
            <label for="game_visible">game visible</label>
            <input id="game_visible" name="game-status" class="game-status-sel" type="radio" value="visible">
            <label for="game_in_progress">game in progress</label>
            <input id="game_in_progress" name="game-status" class="game-status-sel" type="radio"
                   value="in-progress">
            <label for="game_finished">game finished</label>
            <input id="game_finished" name="game-status" class="game-status-sel" type="radio" value="finished">
        </p>

        <div id="round-duration" class="duration_and_link">
            <p>
                <label for="round_duration">Duration:</label>
                <input id="round_duration" type="text"/>
            </p>

            <p class="video">
                <label for="video_link">Video Link:</label>
                <input id="video_link" type="text"/>
            </p>
        </div>

       @foreach($match_teams as $team_cls_name)
        <div id="{{ $team_cls_name }}-kda" class="team-kda-show">
            <label class="{{ $team_cls_name }}"> </label>
            <label>:</label>
            <label id="k-example" name="{{ $team_cls_name }}">0</label>
            <label>/</label>
            <label id="d-example" name="{{ $team_cls_name }}">0</label>
            <label>/</label>
            <label id="a-example" name="{{ $team_cls_name }}">0</label>
        </div>
        <div id="{{ $team_cls_name }}-ban-champions">
            <label id="ban">Ban: </label>

            <div class="col-control-bg">
                <input id="ban_1" type="text" class="form-control champion-search">
                <img id="ban1-icon" src="" class="icon-img">
                <input id="ban_1_icon" type="hidden">
            </div>
            <div class="col-control-bg">
                <input id="ban_2" type="text" class="form-control champion-search">
                <img id="ban1-icon" src="" class="icon-img">
                <input id="ban_2_icon" type="hidden">
            </div>
            <div class="col-control-bg">
                <input id="ban_3" type="text" class="form-control champion-search">
                <img id="ban1-icon" src="" class="icon-img">
                <input id="ban_3_icon" type="hidden">
            </div>
        </div>
        <div class="">
            <label class="col-control-bg">Players</label>
            <label class="col-control-bg">Champion</label>
            <label class="col-control-md">K</label>
            <label class="col-control-md">D</label>
            <label class="col-control-md">A</label>
            <label class="col-control-md">Gold</label>
            <label class="col-control-md">Minions</label>
            <label class="col-control-bg-item">Items</label>
            <label class="col-control-bg-spell">Spell</label>
            <label class="col-control-bg">Mastery</label>
        </div>
       @foreach($team_sequence as $i)
        <div id="{{ $team_cls_name }}_{{ $i }}" class="">
            <select id="{{ $team_cls_name }}-members" name="" class="form-control col-control-bg">

            </select>

            <div class="col-control-bg">
                <input id="champion" name="" class="form-control col-control-bg champion-search">
                <img id="champion-icon" src="" class="icon-img">
                <input id="champion_icon" type="hidden">
            </div>
            <input id="lolK" name="{{ $team_cls_name }}" class="form-control col-control-md refresh-kda-sum ">
            <input id="lolD" name="{{ $team_cls_name }}" class="form-control col-control-md refresh-kda-sum">
            <input id="lolA" name="{{ $team_cls_name }}" class="form-control col-control-md refresh-kda-sum">
            <input id="gold" name="{{ $team_cls_name }}" class="form-control col-control-md">
            <input id="minions" name="{{ $team_cls_name }}" class="form-control col-control-md">

            <div class="col-control-bg">
                <input id="item1" class="form-control col-control-bg item-search">
                <img id="item1-icon" src="" class="icon-img">
                <input id="item_1_icon" type="hidden">
            </div>
            <div class="col-control-bg">
                <input id="item2" class="form-control col-control-bg item-search">
                <img id="item2-icon" src="" class="icon-img">
                <input id="item_2_icon" type="hidden">

            </div>
            <div class="col-control-bg">
                <input id="item3" class="form-control col-control-bg item-search">
                <img id="item3-icon" src="" class="icon-img">
                <input id="item_3_icon" type="hidden">

            </div>
            <div class="col-control-bg">
                <input id="item4" class="form-control col-control-bg item-search">
                <img id="item4-icon" src="" class="icon-img">
                <input id="item_4_icon" type="hidden">

            </div>
            <div class="col-control-bg">
                <input id="item5" class="form-control col-control-bg item-search">
                <img id="item5-icon" src="" class="icon-img">
                <input id="item_5_icon" type="hidden">

            </div>
            <div class="col-control-bg">
                <input id="item6" class="form-control col-control-bg item-search">
                <img id="item6-icon" src="" class="icon-img">
                <input id="item_6_icon" type="hidden">

            </div>
            <div class="col-control-bg">
                <input id="spell1" class="form-control col-control-bg spell-search">
                <img id="spell1-icon" src="" class="icon-img">
                <input id="spell_1_icon" type="hidden">

            </div>
            <div class="col-control-bg">
                <input id="spell2" class="form-control col-control-bg spell-search">
                <img id="spell2-icon" src="" class="icon-img">
                <input id="spell_2_icon" type="hidden">

            </div>
            <div class="col-control-bg">
                <input id="mastery" class="form-control col-control-bg mastery-search">
                <img id="mastery-icon" src="" class="icon-img">
                <input id="mastery_icon" type="hidden">
            </div>
        </div>
       @endforeach
        @endforeach
    </div>

    <div id="match-data">
        <div id="match-header" class="match-header">
            ID: {{@$match->id}}
            <div class="match-team-select">
                <p class="match-team-a-select">
                    Team A:
                    <select id="team_a" class="form-control" name="teama">
                      @foreach($tournament_teams as $team)
                       @if(isset($match) && $match->teamA->id == $team->id)
                        <option id="id_{{ $team->id }}"
                                value="{{ $team->id }}" selected>{{ $team->name }}</option>
                        @else
                                <option id="id_{{ $team->id }}"
                                        value="{{ $team->id }}">{{ $team->name }}</option>
                        @endif
                       @endforeach
                    </select>
                </p>

                <p class="match-time-select">
                    Match DateTime
                    <input type="text" id="match_datetime" @if(isset($match)) value="{{ date('Y-m-d H:i:s',strtotime($match->match_timestamp)) }}" @endif class="form-control"
                           onclick="justCal(this, {lang:'en',persistent:true, format:'Y-m-d H:i:s',theme:'frog'})">
                </p>

                <p class="match-team-b-select">
                    Team B:
                    <select id="team_b" class="form-control" name="teamb">
                        @foreach($tournament_teams as $team)
                            @if( isset($match) && $match->teamB->id == $team->id)
                                <option id="id_{{ $team->id }}"
                                        value="{{ $team->id }}" selected>{{ $team->name }}</option>
                            @else
                                <option id="id_{{ $team->id }}"
                                        value="{{ $team->id }}">{{ $team->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </p>
            </div>
            <p id="match-status-grp" class="match-status-grp">
                Match Status:
                <label class="btn btn-default">
                    @if(!isset($match) || $match->status == config('constants.HIDDEN'))
                    <input id="match-hidden" name="match-status" type="radio" value="HIDDEN" checked>
                   @else
                    <input id="match-hidden" name="match-status" type="radio" value="HIDDEN">
                   @endif
                    Hidden
                </label>
                <label class="btn btn-default">
                    @if(isset($match) && $match->status == config('constants.VISIBLE'))
                    <input id="match-visible" name="match-status" type="radio" value="VISIBLE" checked>
                    @else
                    <input id="match-visible" name="match-status" type="radio" value="VISIBLE">
                    @endif
                    Visible
                </label>
                <label class="btn btn-default">
                    @if(isset($match) && $match->status == config('constants.PLAYING'))
                    <input id="match-playing" name="match-status" type="radio" value="PLAYING" checked>
                    @else
                    <input id="match-playing" name="match-status" type="radio" value="PLAYING">
                    @endif
                    Playing
                </label>
                <label class="btn btn-default">
                    @if(isset($match) && $match->status == config('constants.ENDED'))
                    <input id="match-ended" name="match-status" type="radio" value="ENDED" checked>
                    @else
                    <input id="match-ended" name="match-status" type="radio" value="ENDED">
                    @endif
                    Ended
                </label>
            </p>
            <p>Is hot : <input type="checkbox" name="is_hot" id="is_hot" @if(isset($match) && $match->is_hot == 1) checked @endif></p>
            <p>Can bet : <input type="checkbox" name="can_bet" id="can_bet" @if(isset($match) && $match->can_bet == 1) checked @endif ></p>

            <div id="match_score" class="match-score">
                <p>
                    Current Score:
                    <span class="team-a-name"></span>
                    <span id="team_a_score" class="team-score-effect">{{ @$match->team_a_score }}</span>
                    vs
                    <span class="team-b-name"></span>
                    <span id="team_b_score" class="team-score-effect">{{ @$match->team_b_score }}</span>
                </p>
            </div>
        </div>

        <div id="rounds">
            <div id="round-header"></div>
            <div id="round-detail">
                @if(isset($match))
                @foreach($match->rounds as $round)


                <div id="id-round-{{ $round->round_num }}" class="round-display-show">
                    <p id="round-result">
                        Game
                        <span id="game_num">{{ $round->round_num }}</span>

                        Won
                        @if($round->team_won == 'team_a')
                        <input id="team_a_won" name="round-won{{ $round->round_num }}" class="team-score"
                               type="radio" value="team_a" checked>
                        @else
                        <input id="team_a_won" name="round-won{{ $round->round_num  }}" class="team-score"
                               type="radio" value="team_a">
                        @endif
                        <label for="team_a_won" class="team-a-name"></label>

                        @if($round->team_won == 'team_b')
                        <input id="team_b_won" name="round-won{{ $round->round_num }}" class="team-score"
                               type="radio" value="team_b" checked>
                        @else
                        <input id="team_b_won" name="round-won{{ $round->round_num }}" class="team-score"
                               type="radio" value="team_b">
                        @endif
                        <label for="team_b_won" class="team-b-name"></label>

                    </p>

                    <p id="blue-team-color">
                        <label>Blue Team:</label>
                        @if($round->team_a_color == 'blue')
                        <input id="team_a_blue_team" name="team-a-sel{{ $round->round_num }}" type="radio"
                               value="blue"
                               class="team-color-sel" checked>
                      @else
                        <input id="team_a_blue_team" name="team-a-sel{{ $round->round_num }}" type="radio"
                               value="blue"
                               class="team-color-sel">
                       @endif
                        <label class="team-a-name"></label>
                        @if($round->team_a_color == 'red')
                        <input id="team_a_red_team" name="team-a-sel{{ $round->round_num }}" type="radio"
                               value="red"
                               class="team-color-sel" checked>
                       @else
                        <input id="team_a_red_team" name="team-a-sel{{ $round->round_num }}" type="radio"
                               value="red"
                               class="team-color-sel">
                        @endif
                        <label class="team-b-name"></label>

                    </p>

                    <p id="red-team-color">

                        <label>Red Team:</label>
                        @if($round->team_b_color == 'blue')
                        <input id="team_b_blue_team" name="team-b-sel{{ $round->round_num }}" type="radio"
                               value="blue"
                               class="team-color-sel" checked>
                        @else
                        <input id="team_b_blue_team" name="team-b-sel{{ $round->round_num }}" type="radio"
                               value="blue"
                               class="team-color-sel">
                        @endif
                        <label class="team-a-name"></label>
                            @if($round->team_b_color == 'red')
                        <input id="team_b_red_team" name="team-b-sel{{ $round->round_num }}" type="radio"
                               value="red"
                               class="team-color-sel" checked>
                            @else
                        <input id="team_b_red_team" name="team-b-sel{{ $round->round_num }}" type="radio"
                               value="red"
                               class="team-color-sel">
                        @endif
                        <label class="team-b-name"></label>
                    </p>

                    <p id="round-status" class="game-status">
                        <label>game visible</label>
                       @if($round->status == config('constants.visible'))
                        <input id="game_visible" name="game-status{{ $round->round_num }}" class="game-status-sel"
                               type="radio"
                               value="visible" checked>
                         @else
                        <input id="game_visible" name="game-status{{ $round->round_num }}" class="game-status-sel"
                               type="radio"
                               value="visible">
                     @endif
                        <label>game in progress</label>
                        @if($round->status == config('constants.in-progress'))
                        <input id="game_in_progress" name="game-status{{ $round->round_num }}"
                               class="game-status-sel" type="radio"
                               value="in-progress" checked>
                      @else
                        <input id="game_in_progress" name="game-status{{ $round->round_num }}"
                               class="game-status-sel" type="radio"
                               value="in-progress">
                      @endif
                        <label>game finished</label>
                        @if($round->status == config('constants.finished'))
                        <input id="game_finished" name="game-status{{ $round->round_num }}" class="game-status-sel"
                               type="radio"
                               value="finished" checked>
                      @else
                        <input id="game_finished" name="game-status{{ $round->round_num }}" class="game-status-sel"
                               type="radio"
                               value="finished">
                       @endif
                    </p>

                    <div id="round-duration" class="duration_and_link">
                        <p>
                            <label for="round_duration">Duration:</label>
                            <input id="round_duration" type="text" value="{{ $round->duration }}"/>
                        </p>

                        <p class="video">
                            <label for="video_link">Video Link:</label>
                            <input id="video_link" type="text" value="{{ $round->video_link }}"/>
                        </p>
                    </div>

                    @foreach(json_decode($round->extra_data) as $key => $team)

                    <p id="{{$key }}-kda" class="team-kda-show">
                        <span class="{{ $key }}"> </span>
                        :
                        <span id="k-example" name="{{ $key }}">{{ $team->total_k }}</span>
                        /
                        <span id="d-example" name="{{ $key }}">{{  $team->total_d }}</span>
                        /
                        <span id="a-example" name="{{ $key }}">{{  $team->total_a }}</span>
                    </p>
                    <div id="{{ $key }}-ban-champions">
                        <label id="ban">Ban: </label>

                        <div class="col-control-bg">
                            <input id="ban_1" type="text" class="form-control champion-search"
                                   value="{{ $team->ban_1 }}">
                            <img id="ban1-icon" src="{{ \App\Garena\Functions::getChampionIcon($team->ban_1_icon)}}" class="icon-img"
                                 onerror="this.onerror=null; ">
                            <input id="ban_1_icon" type="hidden" value="{{ $team->ban_1_icon}}">
                        </div>
                        <div class="col-control-bg">
                            <input id="ban_2" type="text" class="form-control champion-search"
                                   value="{{ $team->ban_2 }}">
                            <img id="ban2-icon" src="{{ \App\Garena\Functions::getChampionIcon($team->ban_2_icon)}}" class="icon-img"
                                 onerror="this.onerror=null;">
                            <input id="ban_2_icon" type="hidden" value="{{ $team->ban_2_icon}}">
                        </div>
                        <div class="col-control-bg">
                            <input id="ban_3" type="text" class="form-control champion-search"
                                   value="{{ $team->ban_3}}">
                            <img id="ban3-icon" src="{{ \App\Garena\Functions::getChampionIcon($team->ban_3_icon)}}" class="icon-img"
                                 onerror="this.onerror=null;">
                            <input id="ban_3_icon" type="hidden" value="{{ $team->ban_3_icon}}">
                        </div>
                    </div>
                    <div class="">
                        <label class="col-control-bg">Players</label>
                        <label class="col-control-bg">Champion</label>
                        <label class="col-control-md">K</label>
                        <label class="col-control-md">D</label>
                        <label class="col-control-md">A</label>
                        <label class="col-control-md">Gold</label>
                        <label class="col-control-md">Minions</label>
                        <label class="col-control-bg-item">Items</label>
                        <label class="col-control-bg-spell">Spell</label>
                        <label class="col-control-bg">Mastery</label>
                    </div>
                  @foreach($team_sequence as $i => $t)

                    <div id="{{$key}}_{{ $t }}" class="">
                        @php $h =$t;
                        $name = $key.'-members';

                        @endphp
                        <select id="{{ $key }}-members" name=""
                                class="form-control col-control-bg">
                            <option value="{{  $team->$h->$name}}">{{$team->$h->$name}}</option>
                        </select>

                        <div class="col-control-bg">
                            <input id="champion" name=""
                                   class="form-control col-control-bg champion-search"
                                   value="{{ $team->$t->champion }}">
                            <img id="champion-icon" src="{{ \App\Garena\Functions::getChampionIcon($team->$t->champion) }}"
                                 class="icon-img"
                                 onerror="this.onerror=null;this.src='{{url("tournament-admin/images/questionmark.png")}} '">
                            <input id="champion_icon" type="hidden"
                                   value="{{ $team->$t->champion_icon }}">
                        </div>
                        <input id="lolK" name="{{$key }}" value="{{$team->$t->lolK }}"
                               class="form-control col-control-md refresh-kda-sum ">
                        <input id="lolD" name="{{ $key }}" value="{{ $team->$t->lolD }}"
                               class="form-control col-control-md refresh-kda-sum">
                        <input id="lolA" name="{{ $key }}" value="{{ $team->$t->lolA }}"
                               class="form-control col-control-md refresh-kda-sum">
                        <input id="gold" name="{{ $key }}" value="{{ $team->$t->gold }}"
                               class="form-control col-control-md">
                        <input id="minions" name="{{ $key }}"
                               value="{{ $team->$t->minions }}"
                               class="form-control col-control-md">

                        <div class="col-control-bg">
                            <input id="item1" class="form-control col-control-bg item-search"
                                   value="{{ $team->$t->item1 }}">
                            <img id="item1-icon" src="{{\App\Garena\Functions::getItemIcon($team->$t->item_1_icon) }}"
                                 class="icon-img"
                                 onerror="this.onerror=null;this.src='{{url("tournament-admin/images/questionmark.png")}} '">
                            <input id="item_1_icon" type="hidden"
                                   value="{{$team->$t->item_1_icon}}">
                        </div>

                        <div class="col-control-bg">
                            <input id="item2" class="form-control col-control-bg item-search"
                                   value="{{ $team->$t->item2 }}">
                            <img id="item2-icon" src="{{ \App\Garena\Functions::getItemIcon($team->$t->item_2_icon) }}"
                                 class="icon-img"
                                 onerror="this.onerror=null;this.src='{{url("tournament-admin/images/questionmark.png")}} '">
                            <input id="item_2_icon" type="hidden"
                                   value="{{$team->$t->item_2_icon}}">
                        </div>
                        <div class="col-control-bg">
                            <input id="item3" class="form-control col-control-bg item-search"
                                   value="{{ $team->$t->item3 }}">
                            <img id="item3-icon" src="{{ \App\Garena\Functions::getItemIcon($team->$t->item_3_icon) }}"
                                 class="icon-img"
                                 onerror="this.onerror=null;this.src='{{url("tournament-admin/images/questionmark.png")}} '">
                            <input id="item_3_icon" type="hidden"
                                   value="{{$team->$t->item_3_icon}}">
                        </div>


                        <div class="col-control-bg">
                            <input id="item4" class="form-control col-control-bg item-search"
                                   value="{{ $team->$t->item4 }}">
                            <img id="item4-icon" src="{{ \App\Garena\Functions::getItemIcon($team->$t->item_4_icon) }}"
                                 class="icon-img"
                                 onerror="this.onerror=null;this.src='{{url("tournament-admin/images/questionmark.png")}} '">
                            <input id="item_4_icon" type="hidden"
                                   value="{{$team->$t->item_4_icon}}">
                        </div>
                        <div class="col-control-bg">
                            <input id="item5" class="form-control col-control-bg item-search"
                                   value="{{ $team->$t->item5 }}">
                            <img id="item5-icon" src="{{ \App\Garena\Functions::getItemIcon($team->$t->item_5_icon) }}"
                                 class="icon-img"
                                 onerror="this.onerror=null;this.src='{{url("tournament-admin/images/questionmark.png")}} '">
                            <input id="item_5_icon" type="hidden"
                                   value="{{$team->$t->item_5_icon}}">
                        </div>
                        <div class="col-control-bg">
                            <input id="item6" class="form-control col-control-bg item-search"
                                   value="{{ $team->$t->item6 }}">
                            <img id="item6-icon" src="{{ \App\Garena\Functions::getItemIcon($team->$t->item_6_icon) }}"
                                 class="icon-img"
                                 onerror="this.onerror=null;this.src='{{url("tournament-admin/images/questionmark.png")}} '">
                            <input id="item_6_icon" type="hidden"
                                   value="{{$team->$t->item_6_icon}}">
                        </div>
                        <div class="col-control-bg">
                            <input id="spell1" class="form-control col-control-bg spell-search"
                                   value="{{ $team->$t->spell1 }}">
                            <img id="spell1-icon" src="{{\App\Garena\Functions::getSpellIcon($team->$t->spell_1_icon)}}"
                                 class="icon-img"
                                 onerror="this.onerror=null;this.src='{{url("tournament-admin/images/questionmark.png")}}'">
                            <input id="spell_1_icon" type="hidden"
                                   value="{{ $team->$t->spell_1_icon}}">

                        </div>
                        <div class="col-control-bg">
                            <input id="spell2" class="form-control col-control-bg spell-search"
                                   value="{{  $team->$t->spell2}}">
                            <img id="spell2-icon" src="{{\App\Garena\Functions::getSpellIcon($team->$t->spell_2_icon)}}"
                                 class="icon-img"
                                 onerror="this.onerror=null;this.src='{{url("tournament-admin/images/questionmark.png")}}'">
                            <input id="spell_2_icon" type="hidden"
                                   value="{{ $team->$t->spell_2_icon}}">

                        </div>
                        <div class="col-control-bg">
                            <input id="mastery" class="form-control col-control-bg mastery-search"
                                   value="{{ $team->$t->mastery }}">
                            <img id="mastery-icon" src="{{ \App\Garena\Functions::getMasteryIcon($team->$t->mastery_icon)}}"
                                 class="icon-img"
                                 onerror="this.onerror=null;this.src='{{url("tournament-admin/images/questionmark.png")}}'">
                            <input id="mastery_icon" type="hidden"
                                   value="{{ $team->$t->mastery_icon }}">
                        </div>
                    </div>

                  @endforeach
                   @endforeach
                </div>

              @endforeach
              @endif
            </div>
        </div>
    </div>
</div>



