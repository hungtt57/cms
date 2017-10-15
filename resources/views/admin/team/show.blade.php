@extends('admin.base')

@section('css')
<link rel="stylesheet" type="text/css" href="{{url('tournament-admin/style/tcal.css')}}" />
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
                        <a href="{{url('admin/teams')}}">Teams</a>&nbsp;/&nbsp;{{$team->name}}
                    </header>
                    <!-- End of Panel Header -->
                    <!-- Panel body -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="well">
                                    <h4>Name</h4>
                                    {{$team->name}}
                                </div>
                                <div class="well">
                                    <h4>Description</h4>
                                    {{$team->description}}
                                </div>
                                <div class="well">
                                    <h4>Country</h4>
                                    {{ $team->country }}
                                </div>
                                <div class="well">
                                    <h4>Avatar</h4>
                                    <img class="img-responsive" src="{{$team->Icon}}" onerror=""/>
                                </div>
                                <div class="form-group" id="members-div" >
                                    <label for="id_member">Members</label>
                                    @php $members = json_decode($team->member,true);@endphp
                                   @foreach($members as $member)
                                    <input type="text" class="form-control f3" id="id_member" name="member_name" placeholder="Add Member of Team" value="{{ $member }}" disabled>
                                   @endforeach
                                </div>
                            </div>
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

                                <button type="button" class="btn btn btn-info btn-block" onclick="window.open('{{url('admin/teams/'.$team->id.'/edit')}}','_self')">Update Team</button>

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