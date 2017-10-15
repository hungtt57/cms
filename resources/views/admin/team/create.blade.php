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
                        <a href="{{url('admin/teams')}}">Teams</a>&nbsp;/&nbsp;Create a New Team
                    </header>
                    <!-- End of Panel Header -->
                    <!-- Panel body -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12 col-lg-6">

                                    <form enctype='multipart/form-data' name='bar_create_form' action="{{route('teams.store')}}" method='post'>
                                        {{ csrf_field() }}
                                    <div class="form-group">
                                        <label for="id_name">Name</label>
                                        <input type="text" class="form-control f3" id="id_name" name="name"
                                               placeholder="Enter Name of Team">
                                        @if ($errors->has('name'))
                                            <strong class="text-danger">{{ $errors->first('name') }}</strong>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="id_description">Description</label>
                                        <textarea class="form-control" id="id_description" name="description"
                                                  rows="3"></textarea>
                                        @if ($errors->has('description'))
                                            <strong class="text-danger">{{ $errors->first('description') }}</strong>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="id_country">Select Country</label>
                                        <select class="form-control" id="id_country" name="country"></select>

                                    </div>

                                    <div class="form-group">
                                        <label for="id_icon">Upload Icon</label>

                                        <div class="fileinput fileinput-new" data-provides="fileinput"
                                             style="display:block;">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput"
                                                 style="width: 180px; height: 180px;"></div>

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
                                        <label for="id_member">Name</label>
                                        <input type="text" class="form-control f3" id="id_member0"
                                               name="member_name[]" placeholder="Add Member of Team"><a href="#"
                                                                                                      onclick="removeMember(0)">Remove</a>
                                        <input type="text" class="form-control f3" id="id_member1"
                                               name="member_name[]" placeholder="Add Member of Team"><a href="#"
                                                                                                      onclick="removeMember(1)">Remove</a>
                                        <input type="text" class="form-control f3" id="id_member2"
                                               name="member_name[]" placeholder="Add Member of Team"><a href="#"
                                                                                                      onclick="removeMember(2)">Remove</a>
                                        <input type="text" class="form-control f3" id="id_member3"
                                               name="member_name[]" placeholder="Add Member of Team"><a href="#"
                                                                                                      onclick="removeMember(3)">Remove</a>
                                        <input type="text" class="form-control f3" id="id_member4"
                                               name="member_name[]" placeholder="Add Member of Team"><a href="#"
                                                                                                      onclick="removeMember(4)">Remove</a>
                                    </div>
                                    <input id="add-new" type="button" value='add-new'
                                           class="btn btn-default member-button ">

                                    <br>
                                    <input type='submit' value='Submit' class="btn btn-primary">
                                        <a class="btn btn-default" href="{{url('admin/teams')}}">Cancel</a>
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
@section('js')
<script>
    var member_next_id = 5;
    populateCountries("id_country", "-1");
</script>
@endsection
