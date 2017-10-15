@extends('frontend.layout')

@section('meta')

    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-61673473-14', 'auto');
        ga('send', 'pageview');

    </script>
@endsection

@section('content')
    <body>

    <h1 class="hidden-xs" style="color: #FFF">Please press F12 and Toggle mobile mode</h1>

    <div class="container">
        <div class="row gt-block gt-header hidden-lg hidden-md hidden-sm">

            <div class="col-xs-12 text-center">
                <h1>Bảng xếp hạng</h1>
            </div>

        </div>


        <div class="row panel-group gt-body hidden-lg hidden-md hidden-sm" id="accordion" role="tablist"
             aria-multiselectable="true">
            <div class="col-xs-12 panel panel-default">

                @foreach($tournaments as $tournament)
                    <div class="panel-heading gt-tour tournament-back"
                         style="background-image: url({{ $tournament->banner }})"
                         role="tab"
                         id="heading{{ $tournament->id }}" data-toggle="collapse"
                         data-parent="#accordion"
                         href="#collapse{{ $tournament->id }}" aria-expanded="@if($loop->first){{'true'}}@else{{'false'}}@endif"
                         aria-controls="collapse{{ $tournament->id }}">

                        <div class="row">
                            <div class="col-xs-8 text-left gt-tour-left">
                                <h1>{{ $tournament->name }}</h1>
                                <button class="gt-btn btn-down-div btn-down" role="button">
                                    {{ $tournament->teams->count() }} đội
                                </button>
                            </div>
                        </div>

                    </div>

                @if($tournament->is_leader_board_enabled == 1)

                    <div id="collapse{{ $tournament->id }}" class="panel-collapse collapse @if($loop->first){{'in'}}@endif " role="tabpanel"
                         aria-labelledby="heading{{ $tournament->id }}">
                        <img class="leader-board" src="{{ $tournament->logo }}"/>
                    </div>

                    @endif
                @endforeach
            </div>


        </div>

    </div>

    </body>
@endsection