@extends('_layouts/staff')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">
            <h2>Đăng kí phân phối sản phẩm</h2>
        </div>

        <div class="heading-elements">
            <div class="heading-btn-group">
                <button type="button" class="btn btn-primary" id="select-all">Chọn tất cả</button>
                <button type="button" class="btn btn-default" id="unselect-all">Bỏ Chọn tất cả</button>
                <button type="submit" class="btn btn-danger" id="approve-all">Chấp nhận</button>
                <button type="submit" class="btn btn-danger" id="disapprove-all">Không chấp nhận</button>
                {{--<a href="#" class="btn btn-link " id="destroy"><i class="icon-trash"></i> Xoá</a>--}}
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
            {{--<form role="form">--}}

                {{--<!-- Search Field -->--}}
                {{--<div class="row">--}}
                    {{--<div class="form-group">--}}
                        {{--<div class="input-group">--}}
                            {{--<input class="form-control" type="text" name="gln" placeholder="Search by GLN" required value="{{ Request::input('gln') }}" />--}}
                            {{--<span class="input-group-btn">--}}
                            {{--<button type="submit" class="btn btn-success btn-xs" data-toggle="modal" data-target="#edit-pro">Search</button>--}}

                  {{--</span>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}

            {{--</form>--}}
            <!-- End of Search Form -->

            @if (session('success'))
            <div class="alert bg-success alert-styled-left">
                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                {{ session('success') }}
            </div>
            @endif
            @if (session('error'))
                <div class="alert bg-danger alert-styled-left">
                    <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                    {{ session('error') }}
                </div>
            @endif
            <form id="main-form" method="POST">
                {{ csrf_field() }}
                <div class="panel panel-flat">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Tên công ty</th>
                            <th>Tên sản phẩm</th>
                            <th>Barcode</th>
                            <th>Ảnh</th>
                            <th>Giá</th>
                            {{--<th>Danh mục</th>--}}
                            {{--<th>Thông tin</th>--}}
                            {{--<th>Cảnh báo</th>--}}
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($businesses as $index => $business)
                        <tr role="row" id="product-{{ $business->id }}">
                            <td><input type="checkbox" name="selected[]" class="js-checkbox s" value="{{ $business->id }}" /></td>
                            <td>{{ $business->business->name }}</td>
                            <td>{{$business->product->product_name}}</td>
                            <td>
                                <?php
                                try {
                                    echo DNS1D::getBarcodeSVG(trim($business->product->gtin_code ), "EAN13");
                                } catch (\Exception $e) {
                                    echo $e->getMessage();
                                }
                                ?>
                                {{ $business->product->gtin_code }}
                            </td>
                            <td>

                                @if($business->product->image_default)
                                    <img  src="{{ $business->product->image('thumb_small') }}" />
                                @endif
                            </td>
                            <td>
                                {{ $business->product->price_default }}
                            </td>
                            <td>
                                {{$business->statustext}}
                            </td>
                            <td>
                                {{$business->created_at}}
                            </td>

                            <td>
                                <div class="dropdown">
                                    <button id="product-{{ $business->id }}-actions" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link">
                                        <i class="icon-more2"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="product-{{ $business->id }}-actions">
                                        <li><a href="#" data-toggle="modal" data-target="#approve-modal" data-name="{{ $business->id }}" data-id="{{ $business->id }}" data-approve-url="{{ route('Staff::Management::businessDistributor@approveBusiness', ['id' => $business->id]) }}"><i class="icon-checkmark-circle2"></i> Chấp nhận</a></li>
                                        <li><a href="#" data-toggle="modal" data-target="#disapprove-modal" data-name="{{ $business->id }}" data-disapprove-url="{{ route('Staff::Management::businessDistributor@disapproveBusiness', ['id' => $business->id]) }}"><i class="icon-blocked"></i> Không chấp nhận</a></li>

                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {!! $businesses->links() !!}
                </div>
            </form>
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
                <h4 class="modal-title" id="delete-modal-label">Xoá Sản phẩm</h4>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xoá Sản phẩm <strong class="text-danger js-product-name"></strong> khỏi hệ thống của iCheck?
            </div>
            <div class="modal-footer">
                <form method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="POST">
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
                        Bạn có chắc chắn chấp nhận cho doanh nghiệp phân phối sản phẩm?
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

<div class="modal fade" id="disapprove-modal" tabindex="-1" role="dialog" aria-labelledby="disapprove-modal-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="disapprove-modal-label">Không chấp nhận cho doanh nghiệp phân phối sản phẩm ?</h4>
            </div>
            <form method="POST">
                <div class="modal-body">
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
<script type="text/javascript" src="{{ asset('https://cdn.jsdelivr.net/jsbarcode/3.3.14/JsBarcode.all.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
@endpush

@push('scripts_foot')
<script>


    $('#approve-all').on('click', function () {
        if(confirm('Bạn có chắc chắn thực hiện hành động này?')){
            $('#main-form').attr('action', '{{ route('Staff::Management::businessDistributor@approveList') }}').submit();
        }

    });

    $('#disapprove-all').on('click', function () {
        if(confirm('Bạn có chắc chắn thực hiện hành động này?')){
        $('#main-form').attr('action', '{{ route('Staff::Management::businessDistributor@disapproveList') }}').submit();
        }
    });

    $(".js-help-icon").popover({
        html: true,
        trigger: "hover",
        delay: { "hide": 1000 }
    });

    $('#select-all').on('click', function () {
        $('.s').prop('checked', true);
    });

    $('#unselect-all').on('click', function () {
        $('.s').prop('checked', false);
    });

    $('#delete-modal').on('show.bs.modal', function (event) {
        var $btn = $(event.relatedTarget),
            $modal = $(this);

        $modal.find('form').attr('action', $btn.data('delete-url'));
        $modal.find('.js-product-name').text($btn.data('name'));
    });

    $('#approve-modal').on('show.bs.modal', function (event) {
        var $btn = $(event.relatedTarget),
            $modal = $(this);

        $modal.find('form').attr('action', $btn.data('approve-url'));
        $modal.find('.js-product-name').text($btn.data('name'));
    });

    $('#disapprove-modal').on('show.bs.modal', function (event) {
        var $btn = $(event.relatedTarget),
            $modal = $(this);

        $modal.find('form').attr('action', $btn.data('disapprove-url'));

    });
    $('#destroy').on('click',function(event){
        if(confirm('Bạn có chắc chắn muốn xóa !!')){
            var checked = '';
            $('.s').each(function(){
                if(this.checked){
                    checked = $(this).val() + ',' + checked;
                }

            });
            console.log(checked);
            $.ajax({
                url : "{{route('Ajax::Staff::Management::product@destroy')}}",
                data : {ids : checked},
                type : 'get',
                dataType: "text",
                success:function(data){
                    if(data == 'oke'){
                        location.reload();
                    }
                }
            });
        }
    });
    //$(".js-checkbox").uniform({ radioClass: "choice" });
    if ($(".barcode").length > 0){
        JsBarcode(".barcode").init();
    }

</script>
@endpush



