@extends('_layouts/staff')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Thêm sản phẩm phân phối cho doanh nghiệp</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                    <button href="#" class="btn btn-link" data-toggle="modal" data-target="#add-modal" ><i class="icon-plus-circle"></i> Thêm nhanh s/p phân phối </button>
                    <button href="#" class="btn btn-link" data-toggle="modal" data-target="#delete-modal" @if(!Request::input('q')) disabled onclick="return false;" @endif><i class="icon-plus-circle"></i> Đăng kí phân phối sản phẩm </button>
                    {{--<a href="#" class="btn btn-link disabled"><i class="icon-trash"></i> Xoá</a>--}}
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
                <div class="row">
                    <div class="col-md-6">
                        <form>
                            <div class="has-feedback has-feedback-left">
                                <input type="text" name="q" class="form-control" value="{{ Request::input('q') }}" placeholder="Tìm kiếm theo tên hoặc GTIN ...">
                                <div class="form-control-feedback">
                                    <i class="icon-search"></i>
                                </div>
                            </div>
                        </form>
                    </div>
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
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all" class="js-checkbox" /></th>
                            <th>Tên</th>
                            <th>Hình ảnh</th>
                            <th>Barcode</th>
                            <th>Nhà sản xuất</th>
                            <th>Lượt quét</th>
                            <th>Lượt thích</th>
                            <th>Lượt đánh giá</th>
                            <th>Lượt bình luận</th>


                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($products))
                        @foreach ($products as $index => $product)

                            <tr role="row" id="product-{{ $product->id }}">

                                <td>

                                        <input type="checkbox" name="selected[]" class="s" value="{{ $product->id }}">

                                </td>
                                <td>{{ $product->product_name }}</td>
                                <td><img src="{{ get_image_url($product->image_default, 'thumb_small') }}" /></td>
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
                                <td>{{ @$product->vendor->name }} ({{ @$product->vendor->gln_code }})</td>
                                <td>{{ @$product->scan_count ?: 0 }}</td>
                                <td>{{ @$product->like_count ?: 0 }}</td>
                                <td>
                                    ({{ @$product->vote_good_count ?: 0 }})
                                </td>
                                <td><i class="icon-bubble8"></i> {{ $product->comment_count}}</td>

                                <td>{{ $product->createdAt }}</td>

                        @endforeach
                            @endif
                        </tbody>
                    </table>
                    @if(!empty($products))
                    {!!$products->appends(Request::input())->render()!!}
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
                    <h4 class="modal-title" id="delete-modal-label">Phân phối Sản phẩm</h4>
                </div>
                <div class="modal-body">
                    Quý Doanh nghiệp có chắc chắn muốn đăng kí phân phối sản phẩm ??
                </div>
                <div class="modal-footer">
                    <form method="POST" id="form-register" action="{{ route('Staff::Management::businessDistributor@storeProductDistributor') }}">
                        {{ csrf_field() }}
                        <select name="id" class="form-control js-edit" >
                            @foreach ($businesses as $business)
                                @if($business->id != Request::get('business_id'))
                                    <option value="{{ $business->id }}">{{ $business->name }}</option>
                                @endif
                            @endforeach
                        </select>


                        <input type="hidden" class="product-distributor" name="product_id">
                        <input type="hidden" name="_method" value="POST">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
                        <button type="submit" class="btn btn-danger" id="submit-register">Xác nhận</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="delete-modal-label">Phân phối Sản phẩm</h4>
                </div>
                <div class="modal-body">
                    Quý Doanh nghiệp có chắc chắn muốn đăng kí phân phối sản phẩm ??
                </div>
                <div class="modal-footer">
                    <form method="POST" id="form-register" action="{{ route('Staff::Management::businessDistributor@addList') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                        <select name="id" class="form-control js-edit" >
                            @foreach ($businesses as $business)
                                @if($business->id != Request::get('business_id'))
                                    <option value="{{ $business->id }}">{{ $business->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        </div>

                        <div class="form-group">
                            <label for="gtin" style="float:left">GTIN CODE</label>
                            <textarea class="form-control" name="gtin" id="gtin"></textarea>
                        </div>


                        <input type="hidden" name="_method" value="POST">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
                        <button type="submit" class="btn btn-danger" id="submit-register">Xác nhận</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('js_files_foot')
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
@endpush

@push('scripts_foot')
<script>
    $('#delete-modal').on('show.bs.modal', function (event) {
        var $btn = $(event.relatedTarget),
                $modal = $(this);

        $modal.find('form').attr('action', $btn.data('delete-url'));
        $modal.find('.js-product-name').text($btn.data('name'));
    });

    //    $(".js-checkbox").uniform({ radioClass: "choice" });
    $('#select-all').on('click',function(){
        $('.s').prop('checked', this.checked);
    });
    $(".js-edit").select2();
    $('#submit-register').on('click',function(e){
        e.preventDefault();
        var array = new Array() ;
        $(".s:checked").each(function(){
            array.push($(this).val());
        });
        $('.product-distributor').val(array);
        $('#form-register').submit();
    });
</script>
@endpush



