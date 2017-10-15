@extends('_layouts/staff')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Tìm kiếm sản phẩm </h2>
            </div>

            <div class="heading-elements">

                {{--<button href="#" class="btn btn-link" data-toggle="modal" data-target="#delete-modal" @if(!Request::input('business_id') || Request::input('is_first') == 0) disabled onclick="return false;" @endif><i class="icon-plus-circle"></i> Chuyển quyền sửa</button>--}}



                {{--<button href="#" class="btn btn-link" data-toggle="modal" data-target="#xoa-modal" ><i class="icon-plus-circle"></i>Xóa </button>--}}

                {{--<button href="#" class="btn btn-link" data-toggle="modal" data-target="#destroy-modal" ><i class="icon-plus-circle"></i>Xóa quyền sửa</button>--}}

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

                    <div class="form-group ">

                        <input class="form-control" type="text" name="search" value="{{Request::get('search')}}" placeholder="Search tên or gtin" />
                        <span class="input-group-btn">
                              </span>

                    </div>
                    <div class="form-group">

                        {{--<select class="form-control" name="is_first" id="status-filter">--}}
                            {{--<option value="2" @if(empty(Request::get('is_first'))) selected @endif--}}
                            {{--@if(Request::get('is_first') == 2) selected @endif >Tất cả</option>--}}
                            {{--<option value="0" @if(Request::has('is_first') && Request::get('is_first') == 0) selected @endif>Không được sửa</option>--}}
                            {{--<option value="1" @if(Request::get('is_first') == 1) selected @endif>Có quyền sửa</option>--}}
                        {{--</select>--}}
                    </div>
                    <button type="submit" class="btn btn-success btn-xs">Search</button>

                </form>
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
                        {!! session('error')  !!}
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
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($products))

                            @foreach ($products as $product)
                                <tr role="row" id="product-{{ $product->id }}">
                                    <td>
                                        <input type="checkbox" name="selected[]" class="s" value="{{ $product->id }}">
                                    </td>
                                    <td>{{ $product->product_name }}</td>
                                    <td><img src="{{ get_image_url($product->image_default, 'thumb_small') }}" /></td>
                                    <td>
                                        @if (validate_EAN13Barcode($product->gtin_code))
                                            <svg class="barcode"
                                                 jsbarcode-height="50"
                                                 jsbarcode-format="EAN13"
                                                 jsbarcode-value="{{$product->gtin_code}}"
                                                 jsbarcode-textmargin="0">
                                            </svg>
                                        @endif
                                        {{$product->gtin_code}}
                                    </td>
                                    <td>

                                       <a href="{{route('Staff::Management::businessDistributor@getProductBusiness', [$product->id])}}">Xem danh sách phân phối</a>
                                    </td>

                                </tr>
                            @endforeach

                        @endif
                        </tbody>
                    </table>
                    @if(!empty($products))
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
                    <h4 class="modal-title" id="delete-modal-label">Phân phối Sản phẩm</h4>
                </div>
                <div class="modal-body">
                    Bạn muốn chuyển quyền sửa cho doanh nghiệp??
                </div>
                <div class="modal-footer">
                    <form method="POST" id="form-edit" action="{{route('Staff::Management::businessDistributor@changeEdit', [Request::get('business_id')])}}">
                        {{ csrf_field() }}

                        {{--<select name="id" class="form-control js-edit" >--}}
                            {{--@foreach ($businesses as $business)--}}
                                {{--@if($business->id != Request::get('business_id'))--}}
                                    {{--<option value="{{ $business->id }}">{{ $business->name }}</option>--}}
                                {{--@endif--}}
                            {{--@endforeach--}}
                        {{--</select>--}}


                        <input type="hidden" class="" name="business_id" value="{{Request::get('business_id')}}">
                        <input type="hidden" name="product_id" class="product_id">
                        <input type="hidden" name="_method" value="POST">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
                        <button type="submit" class="btn btn-danger" id="submit-edit">Xác nhận</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="xoa-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="delete-modal-label">Phân phối Sản phẩm</h4>
                </div>
                <div class="modal-body">
                    Bạn muốn Xóa sản phẩm mà doanh nghiệp này phân phối
                </div>
                <div class="modal-footer">
                    <form method="POST" id="form-delete" action="{{route('Staff::Management::businessDistributor@deleteDistributor', [Request::get('business_id')])}}">
                        {{ csrf_field() }}


                        <input type="hidden" class="" name="business_id" value="{{Request::get('business_id')}}">
                        <input type="hidden" name="product_id" class="product_id">
                        <input type="hidden" name="_method" value="POST">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
                        <button type="submit" class="btn btn-danger" id="submit-delete">Xác nhận</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="destroy-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="delete-modal-label">Phân phối Sản phẩm</h4>
                </div>
                <div class="modal-body">
                    Bạn muốn Xóa quyền sản phẩm của doanh nghiệp?
                </div>
                <div class="modal-footer">
                    <form method="POST" id="form-destroy" action="{{route('Staff::Management::businessDistributor@delete', [Request::get('business_id')])}}">
                        {{ csrf_field() }}


                        <input type="hidden" class="" name="business_id" value="{{Request::get('business_id')}}">
                        <input type="hidden" name="product_id" class="product_id">
                        <input type="hidden" name="_method" value="POST">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
                        <button type="submit" class="btn btn-danger" id="submit-destroy">Xác nhận</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



@endsection

@push('js_files_foot')
<script type="text/javascript" src="{{ asset('https://cdn.jsdelivr.net/jsbarcode/3.3.14/JsBarcode.all.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
@endpush

@push('scripts_foot')
<script>


    $(".js-help-icon").popover({
        html: true,
        trigger: "hover",
        delay: { "hide": 1000 }
    });

    $('#submit-edit').on('click',function(e){
        e.preventDefault();
        var array = new Array() ;
        $(".s:checked").each(function(){
            array.push($(this).val());
        });
        $('.product_id').val(array);
        $('#form-edit').submit();
    });


    $('#submit-delete').on('click',function(e){
        e.preventDefault();
        var array = new Array() ;
        $(".s:checked").each(function(){
            array.push($(this).val());
        });
        $('.product_id').val(array);
        $('#form-delete').submit();
    });


    $('#submit-destroy').on('click',function(e){
        e.preventDefault();
        var array = new Array() ;
        $(".s:checked").each(function(){
            array.push($(this).val());
        });
        $('.product_id').val(array);
        $('#form-destroy').submit();
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
        $modal.find('.js-product-name').text($btn.data('name'));
    });

    $(".js-checkbox").uniform({ radioClass: "choice" });

    $('#select-all').on('click',function(){
        $('.s').prop('checked', this.checked);
    });
    $(".js-example-basic-single").select2();
    $(".js-edit").select2();
    @if(!empty($products))
   JsBarcode(".barcode").init();
    @endif
</script>
@endpush



