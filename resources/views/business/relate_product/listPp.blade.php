@extends('_layouts/default')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Sản phẩm liên quan của sản phẩm : {{$product->name}}({{$product->gtin_code}})</h2>
                @php $gtin_code =$product->gtin_code;  @endphp
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                    <button class="btn btn-link" id="btn-update"><i class="icon-plus-circle"></i> Cập nhật sản phẩm liên quan </button>
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

                            <div class="has-feedback has-feedback-left">
                            <input type="text" name="q" class="form-control" value="{{ Request::input('q') }}" placeholder="Tìm kiếm theo tên hoặc GTIN ...">
                            <div class="form-control-feedback">
                            <i class="icon-search"></i>
                            </div>
                            </div>



                        </div>





                    <div class="col-md-3">
                        <select name="filter" class="form-control" id="status-filter">
                            <option value="1" @if(Request::input('filter')==1) selected @endif >Tất cả </option>
                            <option value="2" @if(Request::input('filter')==2) selected @endif>Có liên quan</option>
                            <option value="3" @if(Request::input('filter')==3) selected @endif>Không liên quan</option>
                        </select>

                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-success btn-xs" data-toggle="modal" data-target="#edit-pro">Search</button>
                    </div>
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
                                <th><input type="checkbox" id="select-all" /></th>
                                <th>Tên</th>
                                <th>Hình ảnh</th>
                                <th>Gtin</th>
                                <th>Price</th>
                                <th></th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($products as $index => $product)
                                <tr role="row" id="product-{{ $product->id }}">
                                    <td>

                                        <input type="checkbox" name="selected[]"
                                        @if(in_array($product->product->gtin_code,$product_related)) checked @endif
                                               class="s" value="{{ $product->product->gtin_code }}">
                                    </td>
                                    <td>{{ $product->product->product_name }}</td>
                                    <td>
                                        @if($product->product->image_default)

                                            <img width="50" src="{{ get_image_url($product->product->image_default, 'thumb_small') }}" />
                                        @endif

                                    </td>
                                    <td>
                                        <?php
                                        try {
                                            echo DNS1D::getBarcodeSVG(trim($product->product->gtin_code), "EAN13");
                                        } catch (\Exception $e) {
                                            echo $e->getMessage();
                                        }
                                        ?>
                                        {{ $product->product->gtin_code }}
                                    </td>

                                    <td>
                                        {{$product->product->price_default}}
                                    </td>
                                    <td>
                                        @if(in_array($product->product->gtin_code,$product_related))
                                            <form action="{{route('Business::relateProductDN@removePp')}}" method="POST">

                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name ='gtin_code' value = "{{$gtin_code}}">
                                                <input type="hidden" name ='gtin_code2' value = "{{$product->product->gtin_code}}">
                                                <button class="btn-submit btn btn-danger btn-xs legitRipple"><i class="icon-add"></i> Xóa sản phẩm liên quan </button>
                                            </form>
                                        @else
                                            <form action="{{route('Business::relateProductDN@addPp')}}" method="POST">

                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name ='gtin_code' value = "{{$gtin_code}}">
                                                <input type="hidden" name ='gtin_code2' value = "{{$product->product->gtin_code}}">
                                                <button class="btn-submit btn btn-success btn-xs legitRipple"><i class="icon-add"></i> Thêm sản phẩm liên quan </button>
                                            </form>
                                        @endif

                                    </td>
                                    <td>
                                    <div class="dropdown">
                                    {{--<button id="product-{{ $product->id }}-actions" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link">--}}
                                    {{--<i class="icon-more2"></i>--}}
                                    {{--</button>--}}
                                    {{--<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="product-{{ $product->id }}-actions">--}}
                                             {{--<li><a href="{{ route('Business::relateProductDN@removePP', [$product->id]) }}"><i class="icon-pencil5"></i>Hủy liên quan</a></li>--}}
                                    {{--</ul>--}}
                                    </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>


                    @endif
                </div>
                <div class="col-md-12" style="text-align:right">
                    @if($products)
                    {!! $products->appends(Request::all())->links() !!}

                    @endif
                </div>
            </div>
            <!-- /main content -->
        </div>
        <!-- /page content -->
    </div>
    <!-- /page container -->

    <form id="form-updatePp" action="{{route('Business::relateProductDN@updatePp')}}" method="POST">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name ='gtin_code' value = "{{$gtin_code}}">
        <input type="hidden" id="gtin_update" name ='gtin_update' value ="">
    </form>



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
    $('#select-all').on('click', function () {
        $('.s').prop('checked', this.checked);
    });

    $('#delete-modal').on('show.bs.modal', function (event) {
        var $btn = $(event.relatedTarget),
                $modal = $(this);

        $modal.find('form').attr('action', $btn.data('delete-url'));
        $modal.find('.js-product-name').text($btn.data('name'));
    });
    $('.btn-submit').click(function(e){
        if(confirm('Bạn chắc chắn muốn tiếp tục')){

        }else{
            e.preventDefault();
        }
    });
    $('#btn-update').click(function(){
        if(confirm('Bạn chắc chắn muốn tiếp tục')){
            var array = new Array() ;
            $(".s:checked").each(function(){
                array.push($(this).val());
            });
            $('#gtin_update').val(array);
            $('#form-updatePp').submit();
        }
    });
    $(".js-checkbox").uniform({ radioClass: "choice" });
</script>
@endpush



