@extends('_layouts/staff')

@section('content')
    <style>
        .properties-block label {
            word-wrap: break-word;
        }

        .col-md-6 label {
            padding-top: 8px;
            padding-bottom: 8px;

        }

    </style>
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Thông báo của doanh nghiệp</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                    {{--<a href="{{route('Business::notificationUser@add')}}">--}}
                        {{--<button type="button" class="btn btn-primary" id="select-all">Thông báo mới</button>--}}
                    {{--</a>--}}

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

                <!-- Search Form -->
                <form role="form">

                    <!-- Search Field -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input class="form-control" type="text" name="content" placeholder="Search by content" value="{{ Request::input('content') }}"/>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <select class="form-control" name="status" id="status-filter">
                                    <option value="">Tất cả</option>
                                    @foreach(App\Models\Enterprise\DNNotificationUser::$statusTexts as $key => $value)
                                        <option value="{{$key}}"
                                                @if(Request::has('status') && Request::get('status') == $key) selected @endif>{{$value}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <span class="input-group-btn">
                        <button type="submit" class="btn btn-success btn-xs" data-toggle="modal"
                                data-target="#edit-pro">Search</button>

                        </span>
                    </div>

                </form>
                <!-- End of Search Form -->

                @include('_partials.flashmessage')
                <div class="row">


                </div>
                <form id="main-form" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="reason" id="reasonall-form">
                    <div class="panel panel-flat">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th>Tin nhắn</th>
                                <th>Trạng thái</th>
                                <th>Thời gian tạo</th>
                                <th>Loại gửi</th>
                                <th>Doanh nghiệp</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach ($notifications as $index => $notification)
                                <tr role="row" id="product-{{ $notification->id }}">
                                    <td>{{$notification->content}}</td>
                                    <td>{{\App\Models\Enterprise\DNNotificationUser::$statusTexts[$notification->status]}}</td>
                                    <td>{{date_format($notification->created_at,'H:i:s d/m/Y')}}</td>
                                    <td>
                                        @if($notification->type_send == 1)
                                            Gửi luôn
                                            @endif
                                            @if($notification->type_send == 2)
                                                @php $time = Carbon\Carbon::createFromTimestamp(strtotime($notification->time_send)); @endphp
                                                Đặt lịch : {{date_format($time,'H:i:s d/m/Y')}}
                                            @endif
                                    </td>
                                    <td>{{@$notification->business->name}}</td>
                                    <td>
                                        @if($notification->status == \App\Models\Enterprise\DNNotificationUser::STATUS_PENDING)
                                        <a href="{{route('Staff::Management::notificationUser@approve',['id' => $notification->id])}}" onclick="return approve()" class="btn btn-info btn-xs">Duyệt</a>
                                        <a href="#" data-toggle="modal" data-target="#batch-disapprove-modal"
                                           data-disapprove-url="{{route('Staff::Management::notificationUser@disapprove',['id' => $notification->id])}}"
                                           class="btn btn-warning btn-xs">Không duyệt</a>
                                            @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="row" style="text-align: right">
                            {!! $notifications->appends(Request::all())->links() !!}
                            <div style="clear: both"></div>
                        </div>
                    </div>
                </form>
                <div id="answer-question">

                </div>
            </div>
            <!-- /main content -->
        </div>
        <!-- /page content -->
    </div>
    <!-- /page container -->

    <div class="modal fade" id="batch-disapprove-modal" tabindex="-1" role="dialog" aria-labelledby="disapprove-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="disapprove-modal-label">Không chấp nhận thông báo của doanh nghiệp</h4>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="note" class="control-label text-semibold">Lý do</label>
                            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do bạn chấp nhận đăng tải sản phẩm này lên hệ thống cảu iCheck"></i>
                            <textarea id="note" name="note" rows="5" cols="5" class="form-control" placeholder="Lý do"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="POST">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
                        <button type="submit" class="btn btn-danger">Xác nhận</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@push('js_files_foot')
<script type="text/javascript"
        src="{{ asset('https://cdn.jsdelivr.net/jsbarcode/3.3.14/JsBarcode.all.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
@endpush

@push('scripts_foot')
<script>
    function xoa(){
        if(confirm('Bạn có chắc chắn muốn xóa')){
            return true;
        }
        return false;

    }
    function approve(){
        if(confirm('Bạn có chắc chắn muốn duyệt thông báo này !!')){
            return true;
        }
        return false;

    }
    function disapprove(){
        if(confirm('Bạn có chắc chắn muốn không duyệt thông báo này !!')){
            return true;
        }
        return false;

    }
    $(document).ready(function () {
        $('#batch-disapprove-modal').on('show.bs.modal', function (event) {
            var $btn = $(event.relatedTarget),
                    $modal = $(this);
            $modal.find('form').attr('action', $btn.data('disapprove-url'));
        });
    });

</script>
@endpush



