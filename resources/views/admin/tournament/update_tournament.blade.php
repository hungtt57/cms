@extends('admin.base')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{url('tournament-admin/style/icheck/skins/all.css')}}" />
@endsection

@section('content')
    <!-- Main Content -->
    <section id="main-content" class="no-transition">
        <section class="wrapper">

            <div class="row">
                <div class="col-md-12">
                    <section class="panel">
                        <!-- Panel Header -->
                        <header class="panel-heading">
                            <a href="{{url('admin/tournaments')}}">Tournaments</a> / <a href="{{url('admin/tournaments/'.$tournament->id)}}/">{{$tournament->name}}</a>&nbsp;/&nbsp;Update Tournament
                        </header>
                        <!-- End of Panel Header -->
                        <!-- Panel body -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-6">

                                    <form enctype='multipart/form-data' action="{{route('tournaments.update',$tournament->id)}}" method="POST">
                                        {{ csrf_field() }}
                                        <input name="_method" type="hidden" value="PUT">
                                        <div class="form-group">
                                            <label for="id_name">Name</label>
                                            <input type="text" class="form-control f3" id="id_name" value="{{$tournament->name}}" name="name" placeholder="Enter Name of Tournament">

                                            @if ($errors->has('name'))
                                                <strong class="text-danger">{{ $errors->first('name') }}</strong>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="id_owner">Suffix</label>
                                            <input type="text" class="form-control f3" id="id_suffix" name="suffix" value="{{$tournament->suffix}}" placeholder="Enter Year/Season">

                                            @if ($errors->has('suffix'))
                                                <strong class="text-danger">{{ $errors->first('suffix') }}</strong>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="id_description">Description</label>
                                            <textarea class="form-control" id="id_description"  name="description" rows="3">{{$tournament->description}}</textarea>

                                            @if ($errors->has('description'))
                                                <strong class="text-danger">{{ $errors->first('description') }}</strong>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="id_leaderboard">Upload Leader Board</label>
                                            <div class="fileinput fileinput-new" data-provides="fileinput" style="display:block;">
                                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 180px; height: 180px;">
                                                    <img src="{{$tournament->Logo}}" onerror="">
                                                </div>

                                                <div>
                                                    <span class="btn btn-default btn-file"><span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span><input type="file" id="id_leaderboard" name="logo"></span>
                                                    <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                </div>
                                            </div>
                                            @if ($errors->has('logo'))
                                                <strong class="text-danger">{{ $errors->first('logo') }}</strong>
                                            @endif
                                        </div>


                                        <div class="form-group">
                                            <label for="id_banner">Upload Banner</label>
                                            <div class="fileinput fileinput-exists" data-provides="fileinput" style="display:block;">
                                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 180px; height: 180px;">
                                                    <img src="{{$tournament->Banner}}" onerror="">
                                                </div>

                                                <div>
                                                    <span class="btn btn-default btn-file"><span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span><input type="file" id="id_banner" name="banner"></span>
                                                    <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                </div>
                                            </div>
                                            <p class="help-block">Banner Size 375px x 100px (png format and transparent background)</p>
                                            @if ($errors->has('banner'))
                                                <strong class="text-danger">{{ $errors->first('banner') }}</strong>
                                            @endif
                                        </div>


                                        <div class="form-group">
                                            <label for="id_icon">Upload Icon</label>
                                            <div class="fileinput fileinput-exists" data-provides="fileinput" style="display:block;">
                                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 180px; height: 180px;">
                                                    <img src="{{$tournament->Icon}}" onerror="">
                                                </div>

                                                <div>
                                                    <span class="btn btn-default btn-file"><span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span><input type="file" id="id_icon" name="icon"></span>
                                                    <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                </div>
                                            </div>
                                            <p class="help-block">Icon Size 86px x 86px (png format and transparent background)</p>
                                            @if ($errors->has('icon'))
                                                <strong class="text-danger">{{ $errors->first('icon') }}</strong>
                                            @endif
                                        </div>

                                        <br>
                                        <input type='submit' value='Submit' class="btn btn-primary">
                                        <a class="btn btn-default" href="{{url('admin/tournaments')}}">Cancel</a>
                                    </form>
                                </div>
                                <!-- /.col-md-12 -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- End of Panel body -->
                    </section>
                </div>
            </div>
        </section>
    </section>
    <!-- End of Main Content -->
@endsection