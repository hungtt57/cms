@extends('admin.base')

@section('css')
<link rel="stylesheet" type="text/css" href="{{url('tournament-admin/style/icheck/skins/all.css')}}"/>
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
                        <a href="{{url('admin/teams')}}">Teams</a>&nbsp;/&nbsp;<a
                                href="{{url('admin/teams'.$team->id)}}">{{ $team->name }}</a>&nbsp;/&nbsp;Update
                        Team
                    </header>
                    <!-- End of Panel Header -->
                    <!-- Panel body -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12 col-lg-6">

                                    <form enctype='multipart/form-data' action="{{route('teams.update',$team->id)}}" method="POST">
                                        {{ csrf_field() }}
                                        <input name="_method" type="hidden" value="PUT">
                                    <div class="form-group">
                                        <label for="id_name">Name</label>
                                        <input type="text" class="form-control f3" id="id_name" name="name"
                                               placeholder="Enter Name of Tournament" value="{{ $team->name }}">
                                        @if ($errors->has('name'))
                                            <strong class="text-danger">{{ $errors->first('name') }}</strong>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="id_description">Description</label>
                                        <textarea class="form-control" id="id_description" name="description"
                                                  rows="3">{{$team->description }}</textarea>

                                        @if ($errors->has('description'))
                                            <strong class="text-danger">{{ $errors->first('description') }}</strong>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="id_country">Select Country</label>
                                        <select class="form-control" id="id_country" name="country"></select>
                                        @if ($errors->has('country'))
                                            <strong class="text-danger">{{ $errors->first('country') }}</strong>
                                        @endif
                                    </div>


                                    <div class="form-group">
                                        <label for="id_icon">Upload Icon</label>

                                        <div class="fileinput fileinput-exists" data-provides="fileinput"
                                             style="display:block;">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput"
                                                 style="width: 180px; height: 180px;">
                                                <img src="{{$team->Icon }}" onerror="">
                                            </div>


                                            <div>
                                                    <span class="btn btn-default btn-file"><span class="fileinput-new">Select image</span><span
                                                                class="fileinput-exists">Change</span><input type="file"
                                                                                                             id="id_icon"
                                                                                                             name="icon"></span>
                                                <a href="#" class="btn btn-default fileinput-exists"
                                                   data-dismiss="fileinput">Remove</a>
                                            </div>

                                        </div>
                                        <p class="help-block">Icon Size 86px x 86px (png format and transparent
                                            background)</p>
                                        @if ($errors->has('icon'))
                                            <strong class="text-danger">{{ $errors->first('icon') }}</strong>
                                        @endif
                                    </div>
                                    <div class="form-group" id="members-div">
                                        <label for="id_member">Members</label>
                                        @php $members = json_decode($team->member,true);@endphp
                                        @foreach($members as $key => $member)

                                        <input type="text" class="form-control f3"
                                               id="id_member{{$key}}"
                                               name="member_name[]" placeholder="Add Member of Team"
                                               value="{{ $member }}">
                                        <a href="#" onclick="removeMember({{$key}})">Remove</a>
                                       @endforeach
                                    </div>
                                    <input id="add-new" type="button" value='add-new'
                                           class="btn btn-default member-button ">

                                    <br>
                                    <input type='submit' value='Apply Changes' class="btn btn-primary">
                                    <button type="reset" class="btn btn-default"
                                            onclick="window.open('{{url('admin/teams/'.$team->id)}}','_self')">
                                        Cancel
                                    </button>
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
<script>
    var member_next_id = {{ count(json_decode($team->member)) }};
    populateCountries("id_country", "{{ $team->country }}")
</script>
@endsection
