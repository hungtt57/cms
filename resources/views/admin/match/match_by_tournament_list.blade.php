


@foreach($matches as $key => $match)
    <div>
        <label>{{$key }} </label>
    </div>
    @foreach($match as $m )
<div class="row tournament-schedule-list">

        <a href="{{url('admin/match_profile/'.$m->id)}}" class="">
            <div class="team-col">
                <label class="">{{ @$m->teamA->name }}</label>
                <img src="{{ @$m->teamA->Icon }}">
            </div>
            <div class="team-vs">
                <label>{{ date('H:i:s',strtotime($m->match_timestamp)) }}</label>
                <label>VS</label>
            </div>

            <div class="team-col" style="padding-left: 40px;">
                <label class="">{{ @$m->teamB->name }}</label>
                <img src="{{ @$m->teamB->Icon }}">
            </div>
        </a>
    </div>
   @endforeach
@endforeach

