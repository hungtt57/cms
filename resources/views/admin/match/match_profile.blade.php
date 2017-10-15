@extends('admin.base')

@section('css')
<link rel="stylesheet" type="text/css" href="{{url('tournament-admin/style/icheck/skins/all.css')}}"/>
<script src="{{url('tournament-admin/js/match.js')}}"></script>
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
                        <a href="{{url('admin/match_by_tournament/'.$match->tournament_id)}}">{{$match->tournament->name}}</a>&nbsp;/&nbsp;Tournament
                        Matches
                    </header>
                    <!-- End of Panel Header -->
                    <!-- Panel body -->
                    <div class="panel-body">
                        <div class="row">
                          @include('admin.match.match_result')
                        </div>
                        <!-- /.row -->
                    </div>

                    <!-- End of Panel body -->
                </section>
            </div>
            <div class="col-md-2">
                <div class="row">
                    <button type='button' value='Update Schedule' class="btn btn-info btn-block"
                            onclick="window.open('{{url('admin/update_match/'.$match->id)}}', '_self')">
                        Update Match
                    </button>
                </div>
            </div>
        </div>
    </section>
</section>
@endsection
@section('js')
<!-- End of Main Content -->
<script type="text/javascript">
    var next_round_id = '{{$num_round +1}}';

</script>

@endsection

