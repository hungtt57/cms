@extends('frontend.layout')

    @section('meta')

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-61673473-14', 'auto');
        ga('send', 'pageview');

    </script>
        @endsection

@section('content')
<body>
<h1 class="hidden-xs" style="color: #FFF">Please press F12 and Toggle mobile mode</h1>

<div class="">
    <!-- Schedule header -->
    <div class="gt-block gt-header-schedule hidden-lg hidden-md hidden-sm container">
        <div class="row">
            <div class="col-xs-12 text-center">
                <h1>Lịch thi đấu</h1>
            </div>

            <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
                 aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Ủng hộ một đội tuyển</h4>
                        </div>
                        <div class="modal-body">
                            Theo dõi một đội tuyển để nhận thông báo về trận đấu, tin tức và kết quả<br>
                            Đi đến danh sách đội ?
                        </div>
                        <div class="modal-footer">
                            <form action="/esports/tournament_teams">
                                <button class="btn gt-btn gt-btn-no" data-dismiss="modal">Không</button>
                                <button type="submit" class="btn gt-btn gt-btn-yes">Có</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Schedule switch -->
        <div class="row div-switch">
            <div class="col-xs-6 col-xs-offset-3 text-center " role="tablist">
                <ul class="nav nav-tabs div-sw div-sw-vn" >


                        <li role="presentation" class="gt-btn all-match sc-active active ">
                            <a id="tab-all" href="#allmatches" aria-controls="allmatches" role="tab" data-toggle="tab">
                                Tất cả
                            </a>
                            </a>
                        </li>
                        <li role="presentation" class="gt-btn followed">
                            <a id="tab-followed" href="#followed" aria-controls="followed"
                               role="tab"
                               data-toggle="tab">
                              Theo dõi
                            </a>
                        </li>

                    </ul>

            </div>
        </div>
        <!-- End Schedule switch -->
    </div>
    <!-- End Schedule header -->

    <div class="container">
        <div class="row hidden-lg hidden-md hidden-sm">
            <div class="tab-content sc-content">
                <!-- All match-->
                <div role="tabpanel" class="tab-pane active" id="allmatches">
                </div>
                <!-- End all match-->
                <!-- Followed match-->
                <div role="tabpanel" class="tab-pane" id="followed">
                </div>
                <!-- End Followed match-->

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        @if(isset($popupFollow))
        $('#myModal').modal();
        @endif
    });

    var loaded = false;
    @if (isset($hasFollowed))

    var view_followed = true;

    @else

    var view_followed = false;

    @endif
    var all_head_timestamp = 0;
    var followed_head_timestamp = 0;
    var all_tail_timestamp = 0;
    var followed_tail_timestamp = 0;
    var followed_match_init = false;
    var all_match_init = false;
    var init_all_matches = function () {
        if (!all_match_init) {
            jQuery.getJSON('{{ url('esports/init_schedule_ajax') }}', {}, function (data) {
                $("#allmatches").append(data['response_data']);
                console.log('all_head_timestamp = %s, all_tail_timestamp = %s', data['head_timestamp'], data['tail_timestamp']);
                all_head_timestamp = data['head_timestamp'];
                all_tail_timestamp = data['tail_timestamp'];
                loaded = false;
                all_match_init = true;
                $('html, body').animate({
                    scrollTop: $("#all_match_place").offset().top - 200
                }, 200);
            });
        }
        else {
            console.log('all_match_inited');
        }
    };

    var init_followed_matches = function () {
        if (!followed_match_init) {
            jQuery.getJSON('{{ url('esports/init_follow_ajax') }}', {}, function (data) {
                $("#followed").append(data['response_data']);
                console.log('followed_head_timestamp = %s, followed_tail_timestamp = %s', data['head_timestamp'], data['tail_timestamp']);
                followed_head_timestamp = data['head_timestamp'];
                followed_tail_timestamp = data['tail_timestamp'];
                loaded = false;
                followed_match_init = true;
                $('html, body').animate({
                    scrollTop: $("#followed_match_place").offset().top - 200
                }, 200);
            });
        }
        else {
            console.log('followed_match_inited')
        }
    };
    $(document).ready(function () {
        $("#tab-all").click(function () {
            view_followed = false;
            init_all_matches();
        });
        $("#tab-followed").click(function () {
            view_followed = true;
            init_followed_matches();
        });

        loaded = true;
        if (view_followed) {
            //  init_followed_matches();
            init_all_matches();
        }
        else {
            init_all_matches();
        }
    });

    var all_match = function () {
        if ($(window).scrollTop() < 1) {
            if (all_head_timestamp != 0 && !loaded) {
                loaded = true;
                jQuery.getJSON('{{ url('esports/init_schedule_ajax') }}', {head_timestamp: all_head_timestamp}, function (data) {
                    $("#allmatches").prepend(data['response_data']);
                    console.log('all_head_timestamp = %s', data['head_timestamp']);
                    all_head_timestamp = data['head_timestamp'];
                    loaded = false;
                });
            }
        }

        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1) {
            if (all_tail_timestamp != 0 && !loaded) {
                loaded = true;
                jQuery.getJSON('{{ url('esports/init_schedule_ajax') }}', {tail_timestamp: all_tail_timestamp}, function (data) {
                    $("#allmatches").append(data['response_data']);
                    console.log('all_tail_timestamp = %s', data['tail_timestamp']);
                    all_tail_timestamp = data['tail_timestamp'];
                    loaded = false;
                });
            }
        }
    };


    var followed_match = function () {
        if ($(window).scrollTop() < 1) {
            if (followed_head_timestamp != 0 && !loaded) {
                loaded = true;
                jQuery.getJSON('{{ url('esports/init_follow_ajax') }}', {
                    head_timestamp: followed_head_timestamp
                }, function (data) {
                    $("#followed").prepend(data['response_data']);
                    console.log('followed_head_timestamp = %s', data['head_timestamp']);
                    followed_head_timestamp = data['head_timestamp'];
                    loaded = false;
                });
            }
        }

        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1) {
            if (followed_tail_timestamp != 0 && !loaded) {
                loaded = true;
                jQuery.getJSON('{{ url('esports/init_follow_ajax') }}', {tail_timestamp: followed_tail_timestamp}, function (data) {
                    $("#followed").append(data['response_data']);
                    console.log('followed_tail_timestamp = %s', data['tail_timestamp']);
                    followed_tail_timestamp = data['tail_timestamp'];
                    loaded = false;
                });
            }
        }
    };

    $(window).scroll(function () {
        if (view_followed) {
            loaded = false;
            //  all_head_timestamp = 0;
            all_match()
        }
        else {
            loaded = false;
            //  all_head_timestamp = 0;
            all_match()
        }
    });
</script>

@endpush
</body>
    @endsection
