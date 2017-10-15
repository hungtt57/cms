@extends('_layouts/default')

@section('content')

    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>
                   Danh sách thông báo
                </h2>
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
                <div class="row">
                    <div class="col-md-offset-3 col-md-6">
                        <div class="panel panel-flat">
                            <ul class="media-list media-list-linked media-list-bordered">
                                @foreach ($notifications as $notification)
                                    <li class="media">
                                        <a href="{{ route('Business::notification@read', [$notification->id])}}" class="media-link{{ $notification->unread ? ' border-left-xlg border-left-green' : '' }}">
                                            <!--<div class="media-left">
                                              <span class="btn border-primary text-primary btn-flat btn-rounded btn-icon btn-sm"><i class="icon-git-pull-request"></i></span>
                                            </div>-->

                                            <div class="media-body">
                                                <p>{!! $notification->content !!}</p>
                                                <div class="media-annotation">{{ $notification->created_at }}</div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /main content -->
        </div>
        <!-- /page content -->
    </div>
    <!-- /page container -->
@endsection
