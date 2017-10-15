@extends('_layouts/default')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Sản phẩm liên quan của sản phẩm : {{$product->name}}</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                    @if($hook)
                    <form action="{{route('Business::relateProductDN@removeSx')}}" method="POST">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name ='gtin' value = "{{$product->barcode}}">
                        <button class="btn btn-link"><i class="icon-add"></i> Xóa sản phẩm liên quan </button>
                    </form>
                    @else
                    <form action="{{route('Business::relateProductDN@addSx')}}" method="POST">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name ='gtin' value = "{{$product->barcode}}">
                        <button class="btn btn-link"><i class="icon-add"></i> Thêm sản phẩm liên quan </button>
                    </form>
                    @endif

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
                <div class="row" style="margin-bottom:20px">
                    <form>
                        <div class="col-md-3">

                            {{--<div class="has-feedback has-feedback-left">--}}
                                {{--<input type="text" name="q" class="form-control" value="{{ Request::input('q') }}" placeholder="Tìm kiếm theo tên hoặc GTIN ...">--}}
                                {{--<div class="form-control-feedback">--}}
                                    {{--<i class="icon-search"></i>--}}
                                {{--</div>--}}
                            {{--</div>--}}



                        </div>
                        <div class="col-md-3">


                        </div>

                        <div class="col-md-3">

                        </div>
                        {{--<button type="submit" class="btn btn-success btn-xs" data-toggle="modal" data-target="#edit-pro">Search</button>--}}
                    </form>
                </div>

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

                <div class="panel panel-flat">
                    @if($products)
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Tên</th>
                            <th>Hình ảnh</th>
                            <th>Gtin</th>
                            <th>Price</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($products as $index => $product)
                            <tr role="row" id="product-{{ $product->id }}">

                                <td>{{ $product->product_name }}</td>
                                <td>
                                    @if($product->image_default)

                                            <img width="50" src="{{ get_image_url($product->image_default, 'thumb_small') }}" />
                                        @endif

                                </td>
                                <td>
                                    <?php
                                    try {
                                        echo DNS1D::getBarcodeSVG(trim($product->gtin_code), "EAN13");
                                    } catch (\Exception $e) {
                                        echo $e->getMessage();
                                    }
                                    ?>
                                    {{ $product->gtin_code }}
                                </td>

                                <td>
                                {{$product->price_default}}
                                </td>
                                {{--<td>--}}
                                    {{--<div class="dropdown">--}}
                                        {{--<button id="product-{{ $product->id }}-actions" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link">--}}
                                            {{--<i class="icon-more2"></i>--}}
                                        {{--</button>--}}
                                        {{--<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="product-{{ $product->id }}-actions">--}}
                                            {{--<li><a href="{{ route('Business::product@edit', [$product->id]) }}"><i class="icon-pencil5"></i> Sửa</a></li>--}}
                                        {{--<!-- <li><a href="#" data-toggle="modal" data-target="#delete-modal" data-name="{{ $product->name }}" data-delete-url="{{ route('Business::product@delete', [$product->id]) }}"><i class="icon-trash"></i> Xoá</a></li> -->--}}
                                        {{--</ul>--}}
                                    {{--</div>--}}
                                {{--</td>--}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {!! $products->appends(Request::all())->links() !!}
                        @endif
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
                    <h4 class="modal-title" id="delete-modal-label">Xoá Sản phẩm</h4>
                </div>
                <div class="modal-body">
                    Quý Doanh nghiệp có chắc chắn muốn xoá Sản phẩm <strong class="text-danger js-product-name"></strong> khỏi hệ thống của iCheck?
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

    <div class="modal fade" id="import-modal" tabindex="-1" role="dialog" aria-labelledby="import-modal-label" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('Business::product@import') }}" enctype="multipart/form-data">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="import-modal-label">Nhập nhiều sản phẩm từ file Excel</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="reason" class="control-label text-semibold">Tệp tin</label>
                            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do từ chối đơn đăng ký cảu Sản phẩm"></i>
                            <input id="reason" type="file" name="file">
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{ csrf_field() }}
                        <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
                        <button type="submit" class="btn btn-primary">Nhập</button>
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
    $('#delete-modal').on('show.bs.modal', function (event) {
        var $btn = $(event.relatedTarget),
                $modal = $(this);

        $modal.find('form').attr('action', $btn.data('delete-url'));
        $modal.find('.js-product-name').text($btn.data('name'));
    });

    $(".js-checkbox").uniform({ radioClass: "choice" });
</script>
@endpush



