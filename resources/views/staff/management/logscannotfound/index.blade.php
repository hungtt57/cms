@extends('_layouts/staff')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Mã not found</h2>
            </div>

            <div class="heading-elements">

                <div class="heading-btn-group">

                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
    <!-- Page container -->
    <div class="page-container">
        <form role="form">

            <!-- Search Field -->
            <div class="row">
                <div class="form-group">
                    <div class="input-group">
                        <input class="form-control" type="text" name="code" placeholder="Search by code" required value="{{ Request::input('code') }}" />
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-success btn-xs" data-toggle="modal" data-target="#edit-pro">Search</button>

                  </span>
                    </div>
                </div>
            </div>

        </form>
        <!-- Page content -->
        <div class="page-content">
            <!-- Main content -->
            <div class="content-wrapper">
                @if (session('success'))
                    <div class="alert bg-success alert-styled-left">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="panel panel-flat">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Mã</th>
                                <th>Score</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($logs as $key => $log)
                                <tr role="row" >
                                    <td>{{$log->_id}}</td>
                                    <td>{{$log->score}}</td>
                                    <td><a href="{{route('Staff::Management::product2@add',['gtin_code' => $log->_id])}}"><button class="btn btn-primary">Update sản phẩm</button></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="">
                    {{$logs->appends(Request::all())->links()}}
                </div>
            </div>
            <!-- /main content -->
        </div>
        <!-- /page content -->
    </div>
    <!-- /page container -->

    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="delete-modal-label">Xoá Nhà sản xuất</h4>
                </div>
                <div class="modal-body">
                    Quý Doanh nghiệp có chắc chắn muốn xoá Nhà sản xuất <strong class="text-danger js-gln-name"></strong> khỏi hệ thống của iCheck?
                </div>
                <div class="modal-footer">
                    <form method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
                        <button type="submit" class="btn btn-danger">Xác nhận</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="approve-modal" tabindex="-1" role="dialog" aria-labelledby="approve-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="approve-modal-label">Chấp nhận đăng tải Sản phẩm</h4>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            Bạn có chắc chắn chấp nhận đăng tải Sản phẩm <strong class="text-danger js-product-name"></strong> lên hệ thống của iCheck?
                        </div>
                        <div class="form-group">
                            <label for="reason" class="control-label text-semibold">Lý do</label>
                            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do bạn chấp nhận đăng tải sản phẩm này lên hệ thống cảu iCheck"></i>
                            <textarea id="reason" name="reason" rows="5" cols="5" class="form-control" placeholder="Lý do"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PUT">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
                        <button type="submit" class="btn btn-danger">Xác nhận</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js_files_foot')
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
@endpush

@push('scripts_foot')
<script>
//    $('#delete-modal').on('show.bs.modal', function (event) {
//        var $btn = $(event.relatedTarget),
//                $modal = $(this);
//
//        $modal.find('form').attr('action', $btn.data('delete-url'));
//        $modal.find('.js-gln-name').text($btn.data('name'));
//    });
//
//    $('#approve-modal').on('show.bs.modal', function (event) {
//        var $btn = $(event.relatedTarget),
//                $modal = $(this);
//
//        $modal.find('form').attr('action', $btn.data('approve-url'));
//        $modal.find('.js-product-name').text($btn.data('name'));
//    });
//    function change(){
//        $('#form').submit();
//    }
//    $(".js-checkbox").uniform({ radioClass: "choice" });
</script>
@endpush



