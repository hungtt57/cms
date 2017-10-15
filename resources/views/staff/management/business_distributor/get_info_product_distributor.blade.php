@extends('_layouts/staff')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Danh sách các nhà phân phối của sản phẩm: </h2>
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

                        {{--<input class="form-control" type="text" name="search" value="{{Request::get('search')}}" placeholder="Search tên or gtin" />--}}
                        <span class="input-group-btn">
                              </span>

                    </div>
                    <div class="form-group">

                        <select class="form-control" name="is_first" id="status-filter">
                            <option value="2" @if(empty(Request::get('is_first'))) selected @endif
                            @if(Request::get('is_first') == 2) selected @endif >Tất cả
                            </option>
                            <option value="0"
                                    @if(Request::has('is_first') && Request::get('is_first') == 0) selected @endif>Không
                                được sửa
                            </option>
                            <option value="1" @if(Request::get('is_first') == 1) selected @endif>Có quyền sửa</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success btn-xs">Search</button>

                </form>
                <!-- End of Search Form -->
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
                        {!! session('error')  !!}
                    </div>
                @endif
                <div class="panel panel-flat">
                    <table class="table table-hover">
                        <thead>
                        <tr>

                            <th>Tên công ty</th>
                            <th>Địa chỉ</th>
                            <th>Được sửa</th>

                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($productDistributors))

                            @foreach ($productDistributors as $pD)
                                <tr role="row" id="product-{{ $pD->id }}">
                                    <td>@if($pD->business)
                                            {{$pD->business->name}}
                                        @else
                                            không tồn tại
                                        @endif</td>
                                    <td>
                                        @if($pD->business)
                                            {{$pD->business->address}}
                                        @else
                                            không tồn tại
                                        @endif</td>

                                    </td>
                                    <td>
                                        @if($pD->is_first == 1)
                                            Có
                                        @else
                                            Không
                                            <a href="{{route('Staff::Management::businessDistributor@changePermissionEdit',['id' => $pD->id])}}">Chuyền quyền sửa</a>
                                        @endif
                                    </td>


                                </tr>
                            @endforeach

                        @endif
                        </tbody>
                    </table>
                    @if(!empty($productDistributors))
                        {!! $productDistributors->appends(Request::all())->links() !!}
                    @endif
                </div>
            </div>
            <!-- /main content -->
        </div>
        <!-- /page content -->
    </div>
    <!-- /page container -->



@endsection

@push('js_files_foot')
<script type="text/javascript"
        src="{{ asset('https://cdn.jsdelivr.net/jsbarcode/3.3.14/JsBarcode.all.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
@endpush

@push('scripts_foot')
<script>


    $(".js-help-icon").popover({
        html: true,
        trigger: "hover",
        delay: {"hide": 1000}
    });

    $('#submit-edit').on('click', function (e) {
        e.preventDefault();
        var array = new Array();
        $(".s:checked").each(function () {
            array.push($(this).val());
        });
        $('.product_id').val(array);
        $('#form-edit').submit();
    });


    $('#submit-delete').on('click', function (e) {
        e.preventDefault();
        var array = new Array();
        $(".s:checked").each(function () {
            array.push($(this).val());
        });
        $('.product_id').val(array);
        $('#form-delete').submit();
    });


    $('#submit-destroy').on('click', function (e) {
        e.preventDefault();
        var array = new Array();
        $(".s:checked").each(function () {
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

    $(".js-checkbox").uniform({radioClass: "choice"});

    $('#select-all').on('click', function () {
        $('.s').prop('checked', this.checked);
    });
    $(".js-example-basic-single").select2();
    $(".js-edit").select2();
    @if(!empty($products))
   JsBarcode(".barcode").init();
    @endif
</script>
@endpush



