@extends('admin.base')
@section('content')
<!-- Main Content -->
<section id="main-content" class="no-transition">
    <section class="wrapper">

        <div class="row">
            <div class="col-md-8">
                <section class="panel">
                    <!-- Panel Header -->
                    <header class="panel-heading">
                        Tournaments
                    </header>
                    <!-- End of Panel Header -->
                    <!-- Panel body -->
                    <div class="panel-body">
                        <div class="list-group row">
                          @foreach($tournaments as $tournament)
                              @if($tournament->status == \App\Tournament::ENABLED)
                                <a  href="{{url('admin/tournaments/'.$tournament->id)}}" class="list-group-item col-md-3 no-border" style="height: 72px;">
                                    <table>
                                        <tr>
                                            <td class="bar-avatar-small">
                                                <img style="" src="{{$tournament->Icon}}" onerror="this.onerror=null;this.src='http://filestoresg1.beetalkmobile.com:8081/file/1'" alt="..." class="img-rounded">
                                            </td>
                                            <td>
                                                <h4 class="list-group-item-heading">{{$tournament->name}}</h4>
                                            </td>
                                        </tr>
                                    </table>
                                </a>
                                @endif
                          @endforeach
                        </div>
                    </div>


                    <div class="panel-body">
                        <p> Hidden Tournaments </p>
                        <div class="list-group row">
                            @foreach($tournaments as $tournament)
                                @if($tournament->status == \App\Tournament::DISABLED)
                            <a href="{{url('admin/tournaments/'.$tournament->id)}}" class="list-group-item col-md-3 no-border" style="height: 72px;">
                                <table>
                                    <tr>
                                        <td class="bar-avatar-small">
                                            <img style="opacity: 0.5;filter: alpha(opacity=50);" src="{{$tournament->Icon}}" onerror="this.onerror=null;this.src='http://filestoresg1.beetalkmobile.com:8081/file/1'" alt="..." class="img-rounded">
                                        </td>
                                        <td>
                                            <h4 class="list-group-item-heading">{{$tournament->name}}</h4>
                                        </td>
                                    </tr>
                                </table>
                            </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <!-- End of Panel body -->
                </section>
            </div>
            <div class="col-md-4">
                <section class="panel">
                    <!-- Panel Header -->
                    <header class="panel-heading">
                        Actions
                    </header>
                    <!-- End of Panel Header -->
                    <!-- Panel body -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">

                                <button type="button" class="btn btn btn-info btn-block" onclick="window.open('{{url('admin/tournaments/create')}}','_self')">Create New Tournament</button>

                                <button type="button" class="btn btn btn-info btn-block" onclick="window.open('{{url('admin/arrange_tournaments/')}}','_self')">Arrange Tournaments</button>

                            </div>
                        </div>

                    </div>
                </section>
            </div>
        </div>

    </section>
</section>
<!-- End of Main Content -->
@endsection