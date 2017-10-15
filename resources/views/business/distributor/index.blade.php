@extends('_layouts/default')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Danh sách sản phẩm phân phối ({{$totalProduct}}/{{$quota}})</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                    {{--<a href="{{ route('Business::product@add') }}" class="btn btn-link"><i class="icon-add"></i> Thêm Sản phẩm</a>--}}
                    <a href="#" class="btn btn-link" data-toggle="modal" data-target="#import-modal"><i
                                class="icon-plus-circle"></i> Sửa sản phẩm từ file Excel</a>
                    <button href="#" class="btn btn-link" data-toggle="modal" data-target="#delete-modal"
                            @if(!Request::input('q')) disabled onclick="return false;" @endif><i
                                class="icon-plus-circle"></i> Đăng kí phân phối sản phẩm
                    </button>
                    {{--<a href="#" class="btn btn-link disabled"><i class="icon-trash"></i> Xoá</a>--}}
                    <a href="{{route('Business::downloadPP')}}" class="btn btn-link"><i class="icon-add"></i>Mẫu file
                        excel</a>
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
                <div class="row" style="margin-bottom: 30px">
                    <div class="col-md-5">
                        <form>
                            <div class="has-feedback has-feedback-left">
                                <input type="text" name="q" class="form-control" value="{{ Request::input('q') }}"
                                       placeholder="Tìm kiếm theo tên hoặc GTIN ...">
                                <div class="form-control-feedback">
                                    <i class="icon-search"></i>
                                </div>
                                <button type="submit" class="btn btn-success btn-xs" data-toggle="modal"
                                        data-target="#edit-pro">Search
                                </button>
                            </div>
                        </form>

                    </div>
                    <form id="form1">
                        <div class="col-md-3 ">
                            <select onchange="change1()" name="filter" class="form-control" id="status-filter">
                                <option value="1" @if($filter==1) selected @endif>Tất cả</option>
                                <option value="2" @if($filter==2) selected @endif>Có quyền sửa</option>
                                <option value="3" @if($filter==3) selected @endif>Không có quyền sửa</option>
                            </select>
                        </div>
                    </form>

                    <form id="form2">
                        <div class="col-md-3">
                            <select onchange="change2()" name="status" class="form-control" id="status-filter">
                                <option value="4"
                                        @if(Request::input('status') !==null and Request::input('status')==4) selected @endif>
                                    Tất cả
                                </option>
                                @foreach(\App\Models\Enterprise\ProductDistributorTemp::$statusTexts as $key => $value)
                                    <option value="{{$key}}"
                                            @if(Request::input('status') !==null and Request::input('status')==$key) selected @endif>{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>

                @if (session('success'))
                    <div class="alert bg-success alert-styled-left">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert bg-danger alert-styled-left">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        {{ session('error') }}
                    </div>
                @endif


                <div class="panel panel-flat">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all" class="js-checkbox"/></th>
                            <th>Tên</th>
                            <th>Hình ảnh</th>
                            <th>Barcode</th>
                            <th>Nhà sản xuất</th>
                            <th>Lý do từ chối</th>
                            <th>Được Sửa</th>
                            <th>Trạng Thái Sửa</th>
                            <th>Xem comment</th>
                            <th>Update time</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($products as $index => $product)

                            <tr role="row" id="product-{{ $product->id }}">
                                <td>
                                    @if(empty($product->productsDistributor()->where('business_id',Auth::user()->id)->first()))
                                        <input type="checkbox" name="selected[]" class="s" value="{{ $product->id }}">
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()) and $product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()->status==2)
                                        {{$product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()->product_name}}
                                    @else
                                        {{ $product->product_name }}
                                 @endif
                                </td>
                                <td>

                                    @if(!empty($product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()) and $product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()->status==2)
                                        @if($product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()->image)
                                            @foreach(json_decode($product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()->image) as $image)
                                                <img width="150" height="150" src="{{ get_image_url($image, 'thumb_small') }}"/>
                                            @endforeach
                                        @endif
                                    @else
                                        <img width="150" height="150" src="{{ get_image_url($product->image_default, 'thumb_small') }}"/>
                                    @endif


                                </td>
                                <td>
<!--                                    --><?php
//                                    try {
//                                        echo DNS1D::getBarcodeSVG(trim($product->gtin_code), "EAN13");
//                                    } catch (\Exception $e) {
////                                        echo $e->getMessage();
//                                    }
//                                    ?>
                                    {{ $product->gtin_code }}
                                </td>
                                <td>{{ @$product->vendor->name }} ({{ @$product->vendor->gln_code }})</td>
                                <td>
                                    @if(!empty($product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()) and $product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()->status==2)
                                        {{$product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()->reason}}
                                      @endif

                                </td>
                                <td>
                                    @if(!empty($product->productsDistributor()->where('business_id',Auth::user()->id)->first()))
                                        @if($product->productsDistributor()->where('business_id',Auth::user()->id)->first()->pivot->is_first == 1 && $product->productsDistributor()->where('business_id',Auth::user()->id)->first()->pivot->status == \App\Models\Enterprise\ProductDistributor::STATUS_ACTIVATED)
                                            Có
                                        @else
                                            Không
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first())
                                        {{\App\Models\Enterprise\ProductDistributorTemp::$statusTexts[$product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()->status]}}
                                    @endif
                                </td>
                                <td>

                                    @if(!empty($product->productsDistributor()->where('business_id',Auth::user()->id)->first()))
                                        @if($product->productsDistributor()->where('business_id',Auth::user()->id)->first()->pivot->is_first == 1 && $product->productsDistributor()->where('business_id',Auth::user()->id)->first()->pivot->status == \App\Models\Enterprise\ProductDistributor::STATUS_ACTIVATED)
                                            @if (auth()->user()->can('view-comment'))
                                                @if($product->productsDistributor()->where('business_id',Auth::user()->id)->first()->pivot->is_quota == 1)
                                                    <a href="{{route('Business::product@comments',['gtin' =>$product->gtin_code])}}">
                                                        <button type="submit"
                                                                class="btn btn-success btn-xs legitRipple">Comment<span
                                                                    class="legitRipple-ripple"></span></button>
                                                    </a>
                                                @endif
                                            @endif
                                        @endif
                                    @endif

                                </td>
                                <td>
                                    {{$product->updatedAt}}
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button id="product-{{ $product->id }}-actions" type="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                class="btn btn-link">
                                            <i class="icon-more2"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right"
                                            aria-labelledby="product-{{ $product->id }}-actions">

                                            @if(empty($product->productsDistributor()->where('business_id',Auth::user()->id)->first()))
                                                <li>
                                                    <a href="{{ route('Business::product@registerProduct', ['id' => $product->id]) }}"><i
                                                                class="icon-pencil5"></i> Đăng kí phân phối</a></li>
                                            @else
                                                <li>
                                                    <a href="{{ route('Business::product@cancelProduct', ['id' => $product->id]) }}"><i
                                                                class="icon-pencil5"></i>Hủy đăng kí</a></li>

                                                @if($product->productsDistributor()->where('business_id',Auth::user()->id)->first()->pivot->is_first == 1 && $product->productsDistributor()->where('business_id',Auth::user()->id)->first()->pivot->status == \App\Models\Enterprise\ProductDistributor::STATUS_ACTIVATED)
                                                    <li>
                                                        <a href="{{ route('Business::product@editProduct', ['id' => $product->id]) }}"><i
                                                                    class="icon-pencil5"></i> Sửa </a></li>

                                                    @if (auth()->user()->can('view-relate-product'))
                                                        @if($product->productsDistributor()->where('business_id',Auth::user()->id)->first()->pivot->is_quota == 1)
                                                            <li>
                                                                <a href="{{ route('Business::relateProductDN@listRelateProductPp', [$product->gtin_code]) }}"><i
                                                                            class="icon-pencil5"></i> Sản phẩm liên quan
                                                                </a></li>
                                                        @endif
                                                    @endif

                                                @endif
                                            @endif

                                        </ul>
                                    </div>
                                </td>
                        @endforeach
                        </tbody>
                    </table>
                    {!!$products->appends(Request::input())->render()!!}
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="delete-modal-label">Phân phối Sản phẩm</h4>
                </div>
                <div class="modal-body">
                    Quý Doanh nghiệp có chắc chắn muốn đăng kí phân phối sản phẩm ??
                </div>
                <div class="modal-footer">
                    <form method="POST" id="form-register"
                          action="{{ route('Business::product@PostRegisterProduct') }}">
                        {{ csrf_field() }}
                        <input type="hidden" class="product-distributor" name="product_id">
                        <input type="hidden" name="_method" value="POST">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
                        <button type="submit" class="btn btn-danger" id="submit-register">Xác nhận</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="import-modal" tabindex="-1" role="dialog" aria-labelledby="import-modal-label"
         data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('Business::product@importDistributor') }}"
                      enctype="multipart/form-data">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="import-modal-label">Nhập nhiều sản phẩm từ file Excel</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="reason" class="control-label text-semibold">Tệp tin</label>
                            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                               data-content="Lý do từ chối đơn đăng ký cảu Sản phẩm"></i>
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

    //    $(".js-checkbox").uniform({ radioClass: "choice" });
    $('#select-all').on('click', function () {
        $('.s').prop('checked', this.checked);
    });
    $('#submit-register').on('click', function (e) {
        e.preventDefault();
        var array = new Array();
        $(".s:checked").each(function () {
            array.push($(this).val());
        });
        $('.product-distributor').val(array);
        $('#form-register').submit();
    });
    function change1() {
        $('#form1').submit();
    }
    function change2() {
        $('#form2').submit();
    }

</script>
@endpush



