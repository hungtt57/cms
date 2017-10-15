@extends('_layouts/staff')

@section('content')
    <style>
        .action-button{
            height:50px;
            dis
        }
    </style>
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Jobs</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                    {{--<a href="{{route('Staff::Management::user@add')}}" class="btn btn-link"><i class="icon-add"></i> Thêm thành viên</a>--}}

                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
    <!-- Page container -->
    <div class="page-container">
        <!-- Page content -->
        <div class="page-content">
            <!-- Main content -->
            <div class="content-wrapper">
                <div class="container">
                    <div class="col-md-6 col-md-offset-3">

                        <!-- Search Form -->
                        {{--<form role="form">--}}

                          {{----}}
                            {{--<div class="row">--}}
                                {{--<div class="form-group">--}}
                                    {{--<div class="input-group">--}}
                                        {{--<input class="form-control" type="text" name="search" placeholder="Search"--}}
                                               {{--required/>--}}
                                        {{--<span class="input-group-btn">--}}
                            {{--<button type="submit" class="btn btn-success btn-xs" data-toggle="modal"--}}
                                    {{--data-target="#edit-pro">Search</button>--}}

                                 {{--</span>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                        {{--</form>--}}


                    </div>
                </div>
                @include('_partials.flashmessage')
                <div class="row" style="display: block">
                    <div class="tabbable">
                        <ul class="nav nav-tabs nav-tabs-highlight nav-justified">
                            <li class="@if(Request::input('page_failed_jobs') != null && Request::input('page_jobs') == null) @elseif(!session('tab-view-job')) active @endif "><a href="#highlighted-justified-tab1" data-toggle="tab"
                                                  class="legitRipple" aria-expanded="true">Job</a></li>
                            <li class=" @if(Request::input('page_failed_jobs') != null && Request::input('page_jobs') == null) active @endif @if(session('tab-view-job'))) active @endif" ><a href="#highlighted-justified-tab2" data-toggle="tab" class="legitRipple"
                                            aria-expanded="false">Failed Job</a></li>

                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane @if(Request::input('page_failed_jobs') != null && Request::input('page_jobs') == null) @elseif(!session('tab-view-job')) active @endif  " id="highlighted-justified-tab1">
                                <div class="panel panel-flat">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Created By</th>
                                                <th>job Name </th>
                                                <th>Created_at</th>

                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($jobs as $job)
                                                @php
                                                $json =json_decode($job->payload)->data;

                                                   if(isset($json->class)){
                                                        $data = $json->data;
                                                         $command = unserialize($data);
                                                         $command = $command[0];
                                                   }else{
                                                      $commanName = $json->commandName;
                                                         $command = unserialize($json->command);
                                                   }

                                                        @endphp
                                                <tr role="row" id="">
                                                    <td>{{$job->id}}</td>
                                                    <td>{{@$command->createBy}}</td>
                                                    <td>{{@$command->jobName}}
                                                    </td>
                                                    <td>{{$job->created_at}}</td>

                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>


                                    </div>

                                </div>
                                <div style="float:right;"><?php echo $jobs->links(); ?></div>





                            </div>

                            <div class="tab-pane @if(Request::input('page_failed_jobs') != null && Request::input('page_jobs') == null) active @endif @if(session('tab-view-job'))) active @endif" id="highlighted-justified-tab2">
                                <div class="panel panel-flat">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Created By</th>
                                                <th>job Name </th>
                                                <th>Failed_at</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($failed_jobs as $fail)
                                                @php
                                                    $json =json_decode($fail->payload)->data;

                                                      if(isset($json->class)){
                                                           $data = $json->data;
                                                            $command = unserialize($data);
                                                            $command = $command[0];
                                                      }else{
                                                         $commanName = $json->commandName;
                                                            $command = unserialize($json->command);
                                                      }

                                                @endphp
                                                <tr role="row" id="">
                                                    <td>{{$fail->id}}</td>
                                                    <td>{{@$command->createBy}}</td>
                                                    <td>{{@$command->jobName}}
                                                    <td>{{$fail->failed_at}}</td>
                                                    <td>
                                                        <a class="action-button" href="{{route('Staff::retryJob',[$fail->id])}}">
                                                            <button type="button" class="btn btn-info btn-xs"
                                                                    data-toggle="modal" data-target="#edit-pro">
                                                                Retry
                                                            </button>
                                                        </a>

                                                        <a class="action-button" href="{{route('Staff::deleteJob',[$fail->id])}}"
                                                           onclick="return xoaCat(); ">
                                                            <button style="margin-top: 10px" type="button" class="btn btn-danger btn-xs"
                                                                    data-toggle="modal" data-target="#edit-pro">
                                                                Delete
                                                            </button>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>


                                    </div>

                                </div>
                                <div style="float:right;"><?php echo $failed_jobs->links(); ?></div>
                            </div>

                        </div>
                        <!-- /main content -->
                    </div>

                    <!-- /page content -->
                </div>
                <!-- /page container -->


                @endsection

                @push('scripts_foot')
                <script>
                    function xoaCat() {
                        var conf = confirm("Bạn chắc chắn muốn xoá?");
                        return conf;
                    }

                </script>
                @endpush

                @push('scripts_ck')
                <script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
    @endpush