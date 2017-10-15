@extends('_layouts/staff')

@section('page_title', 'Sản phẩm')
<link rel="stylesheet" href="{{ asset('css/hungtt.css') }}" type="text/css">
@section('content')
    <style>
        #products {
            overflow: auto;
        }
        .table>thead>tr>th{
            padding:12px 20px !important;
            text-align: center;
            height: 100px;
        }
        td{
            text-align: center;
            border-bottom:1px solid #bbb;
        }
        /*.form-control{*/
            /*border-color: transparent transparent rgb(0, 150, 136);*/
            /*box-shadow: rgb(0, 150, 136) 0px 1px 0px;*/
        /*}*/
        .col-md-6 label{
            word-break: break-all;
        }
        .js-categories-select{
            width: 400px !important;position: static!important;
            height:0px !important;
        }
        .form-control-feedback{
            margin-top:12px;
        }
        .blockProperties{
            min-width:200px !important;
        }
        .loader {
            margin: 0 auto;
            border: 5px solid #f3f3f3; /* Light grey */
            border-top: 5px solid #3498db; /* Blue */
            border-radius: 50%;
            width: 100px;
            height: 100px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        input{
            margin:0 auto !important;
        }
        label{
            padding: 8px !important;
        }
        .checkbox_column{
            clear: both;
            margin-left: 40px;
        }
         .price {
             width:80%;
             float:left;
         }
        .currency{
            padding: 8px 0px;
            height: 20px;
            display: block;
        }
        .td_price{
            min-width: 100px;
        }
        .border-primary{
            border-bottom-color: rgb(221, 221, 221) !important;
        ;
        }

    </style>

    <div id="main-content">
        <!-- Page header -->
        <div class="page-header">
            <div class="page-header-content">
                <div class="page-title">
                    <h2>Sản phẩm</h2>
                </div>

                <div class="heading-elements">
                    <div class="heading-btn-group">
                        <a href="{{ route('Staff::Management::product2@add') }}" class="btn btn-link"><i
                                    class="icon-plus-circle"></i> Thêm Sản phẩm</a>
                        <a href="#" class="btn btn-link" data-toggle="modal" data-target="#import-modal2"><i
                                    class="icon-plus-circle"></i> Thêm từ file Excel</a>
                        <a href="#" class="btn btn-link" data-toggle="modal" data-target="#import-modal"><i
                                    class="icon-plus-circle"></i> Cập nhật từ file Excel</a>

                        {{--<button type="button" id="btn-batch-disapprove" data-action="disapprove" disabled="disabled"--}}
                                {{--class="btn btn-grey js-batch-button" data-toggle="modal"--}}
                                {{--data-target="#batch-disapprove-modal">--}}
                            {{--<i class="icon-cancel-circle"></i> Không chấp nhận</a>--}}
                        {{--</button>--}}
                        {{--<button type="button" id="btn-batch-activate" data-action="activate" disabled="disabled"--}}
                                {{--class="btn btn-success js-batch-button" data-toggle="modal"--}}
                                {{--data-target="#batch-activate-modal">--}}
                            {{--<i class="icon-checkmark-circle"></i> Kích hoạt</a>--}}
                        {{--</button>--}}
                        {{--<button type="button" id="btn-batch-deactivate" data-action="deactivate" disabled="disabled"--}}
                                {{--class="btn btn-grey js-batch-button" data-toggle="modal"--}}
                                {{--data-target="#batch-deactivate-modal">--}}
                            {{--<i class="icon-cancel-circle"></i> Huỷ kích hoạt</a>--}}
                        {{--</button>--}}
                        <!-- <button type="button" id="btn-batch-delete" data-action="delete" disabled="disabled" class="btn btn-danger js-batch-button" data-toggle="modal" data-target="#batch-delete-modal">
                          <i class="icon-bin"></i> Xoá</a>
                        </button> -->
                        <button href="#" id="btn-report" target="_blank" class="btn btn-primary"><i class="icon-plus-circle"></i> Xuất Report</button>
                        <a href="#" id="btn-export" target="_blank" class="btn btn-primary"><i
                                    class="icon-plus-circle"></i> Xuất ra Excel</a>
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
                        <form action="" id="form-search">
                            <div class="col-md-1 mb-20">

                                <div class="has-feedback has-feedback-left">
                                    <input type="text" name="q" class="form-control" value="{{ Request::input('q') }}"
                                           placeholder="Tìm kiếm theo tên">
                                    <div class="form-control-feedback">
                                        <i class="icon-search"></i>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-1 mb-20">

                                <div class="has-feedback has-feedback-left">
                                    <input type="text" name="gtin" class="form-control"
                                           value="{{ Request::input('gtin') }}" placeholder="Tìm kiếm theo GTIN">
                                    <div class="form-control-feedback">
                                        <i class="icon-search"></i>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-1 mb-20">

                                <div class="has-feedback has-feedback-left">
                                    <input type="text" name="gln" class="form-control"
                                           value="{{ Request::input('gln') }}" placeholder="Tìm theo GLN ...">
                                    <div class="form-control-feedback">
                                        <i class="icon-barcode2"></i>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-1 mb-20">
                                <select class="form-control" name="verify_owner" id="cat-filter">
                                    <option value="">All</option>
                                    <option value="1" {{ Request::input('verify_owner') == 1 ? ' selected="selected"' : '' }}>
                                        Đã kí hợp đồng
                                    </option>
                                    {{--<option value="" {{ Request::input('vendor') == 2 ? ' selected="selected"' : '' }}>--}}
                                        {{--Sản phẩm chưa có vendor--}}
                                    {{--</option>--}}
                                </select>
                            </div>
                            <div class="col-md-1 mb-20">
                                <select class="form-control" name="map" id="cat-filter">
                                    <option value="">All</option>
                                    <option value="1" {{ Request::input('map') == 1 ? ' selected="selected"' : '' }}>
                                        Đã Map
                                    </option>
                                    <option value="" {{ Request::input('map') == 2 ? ' selected="selected"' : '' }}>
                                    Chưa Map
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-20">
                                <div class="row">
                                    <div class="col-md-2">

                                        <div class="has-feedback has-feedback-left">
                                            <input type="text" name="price" class="form-control"
                                                   value="{{ Request::input('price') }}" placeholder="Giá. VD: < 1000">
                                            <div class="form-control-feedback">
                                                <i class="icon-coin-dollar"></i>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-5">
                                        <select id="category-filter"
                                                class="select-border-color border-warning js-categories-select"
                                            name="categories">
                                            <option value="">Tất cả danh mục</option>
                                            <option value="none">Không thuộc danh mục nào</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                        data-level="{{ $category->level }}" {{ Request::input('categories') == $category->id ? ' selected="selected"' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <select class="form-control" name="image" id="image-filter">
                                            <option value="">All</option>
                                            <option value="1" {{ Request::input('image') == 1 ? ' selected="selected"' : '' }}>
                                                Sản phẩm đã có ảnh
                                            </option>
                                            <option value="2" {{ Request::input('image') == 2 ? ' selected="selected"' : '' }}>
                                                Sản phẩm chưa có ảnh
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control" name="vendor" id="cat-filter">
                                            <option value="">All</option>
                                            <option value="1" {{ Request::input('vendor') == 1 ? ' selected="selected"' : '' }}>
                                                Sản phẩm đã có vendor
                                            </option>
                                            <option value="2" {{ Request::input('vendor') == 2 ? ' selected="selected"' : '' }}>
                                                Sản phẩm chưa có vendor
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button id="search" class="btn btn-primary">Search</button>
                        </form>

                            <div class="col-md-11 mb-20 text-right" id="pagination">

                            </div>

                                <div class="col-md-1">
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                            <i class="icon-cog position-left"></i><span class="sr-only">Tùy chọn</span><span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right dropdown-options" id="column-show-hidden">
                                            <li id="columns-toggle-header" class="dropdown-header">Hiện/ Ẩn cột</li>
                                        </ul>
                                    </div>
                                </div>
                    </div>

                    @if ($errors->count())
                        @foreach ($errors->all() as $error)
                            <div class="alert bg-danger alert-styled-left">
                                <button type="button" class="close" data-dismiss="alert"><span>×</span><span
                                            class="sr-only">Close</span></button>
                                {{ $error }}
                            </div>
                        @endforeach
                    @endif

                    @if (session('success'))
                        <div class="alert bg-success alert-styled-left">
                            <button type="button" class="close" data-dismiss="alert"><span>×</span><span
                                        class="sr-only">Close</span></button>
                            {{ session('success') }}
                        </div>
                    @elseif (session('danger'))
                        <div class="alert bg-danger alert-styled-left">
                            <button type="button" class="close" data-dismiss="alert"><span>×</span><span
                                        class="sr-only">Close</span></button>
                            {{ session('danger') }}
                        </div>
                    @endif

                    <form id="batch-form" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="action"/>
                        <div id="products" class="panel panel-flat position-relative">
                            {{--<div class="loading-bar is-loading"><div class="slow"></div></div>--}}
                            <table class="table table-hover" id="table-data">
                                <thead>
                                <tr>
                                    <th >
                                        <input type="checkbox" id="toggle-select-all" class="js-checkbox"/>
                                    </th>
                                    <th data-sortable="true" data-sort-by="name">
                                        Tên
                                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Tên của Sản phẩm"></i>
                                    </th>
                                    <th>
                                        Barcode <i
                                                class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                                data-content="Logo của Sản phẩm"></i>
                                    </th>
                                    <th data-hideable="true" data-sort-by="image">
                                        Hình ảnh <i
                                                class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                                data-content="Logo của Sản phẩm"></i>
                                    </th>
                                    <th data-hideable="true" data-sortable="true" data-sort-by="price">
                                        Giá bán <i
                                                class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                                data-content="Logo của Sản phẩm"></i>
                                    </th>
                                    <th data-hideable="true"  data-sort-by="gln">
                                        GLN
                                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Logo của Sản phẩm"></i>
                                    </th>
                                    <th data-hideable="true"  data-sort-by="a1">
                                        Thông tin <i
                                                class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                                data-content="Logo của Sản phẩm"></i>
                                    </th>

                                    <th data-hideable="true" data-sortable="true" data-sort-by="scan">
                                        Lượt quét <i
                                                class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                                data-content="Ngày đăng ký của Sản phẩm trên hệ thống iCheck for Business"></i>
                                    </th>
                                    <th data-hideable="true" data-sortable="true" data-sort-by="view">
                                        Lượt xem <i
                                                class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                                data-content="Ngày đăng ký của Sản phẩm trên hệ thống iCheck for Business"></i>
                                    </th>
                                    <th data-hideable="true" data-sortable="true" data-sort-by="like">
                                        Lượt thích <i
                                                class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                                data-content="Ngày đăng ký của Sản phẩm trên hệ thống iCheck for Business"></i>
                                    </th>
                                    <th data-hideable="true" data-sortable="true" data-sort-by="vote">
                                        Lượt đánh giá <i
                                                class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                                data-content="Ngày đăng ký của Sản phẩm trên hệ thống iCheck for Business"></i>
                                    </th>
                                    <th data-hideable="true" data-sortable="true" data-sort-by="comment">
                                        Lượt bình luận <i
                                                class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                                data-content="Ngày đăng ký của Sản phẩm trên hệ thống iCheck for Business"></i>
                                    </th>
                                    <th data-hideable="true" data-sortable="true" data-sort-by="report">
                                        Số report <i
                                                class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                                data-content="Ngày đăng ký của Sản phẩm trên hệ thống iCheck for Business"></i>
                                    </th>
                                    <th style="width: 500px" data-hideable="true" data-sortable="false"  data-sort-by="category">
                                        Danh mục <i
                                                class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                                data-content="Ngày đăng ký của Sản phẩm trên hệ thống iCheck for Business"></i>
                                    </th>
                                    <th data-hideable="true" data-sortable="true" data-sort-by="updated_at">
                                       Thuộc tính  <i
                                                class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                                data-content="Thuộc tính của sản phẩm"></i>
                                    </th>
                                    <th data-hideable="true"  data-sort-by="feature">
                                        Feature <i
                                                class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                                data-content="Trạng thái tài khoản của Sản phẩm trên hệ thống iCheck for Business"></i>
                                    </th>
                                    <th data-hideable="true"  data-sort-by="feature">
                                        Map <i
                                                class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                                data-content="Trạng thái tài khoản của Sản phẩm trên hệ thống iCheck for Business"></i>
                                    </th>
                                    <th data-hideable="true" data-sortable="true" data-sort-by="created_at">
                                        Ngày tạo <i
                                                class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                                data-content="Ngày đăng ký của Sản phẩm trên hệ thống iCheck for Business"></i>
                                    </th>
                                    <th data-hideable="true" data-sortable="true" data-sort-by="updated_at">
                                        Ngày cập nhật <i
                                                class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                                data-content="Ngày đăng ký của Sản phẩm trên hệ thống iCheck for Business"></i>
                                    </th>
                                    <th ></th>
                                </tr>
                                </thead>
                                <tbody id="tbody">

                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <!-- /main content -->
            </div>
            <!-- /page content -->
        </div>
        <!-- /page container -->
    </div>

    <div class="modal fade" id="import-modal2" tabindex="-1" role="dialog" aria-labelledby="import-modal2-label"
         data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('Staff::Management::product2@import', ['new' => 1]) }}"
                      enctype="multipart/form-data">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="import-modal2-label">Nhập nhiều sản phẩm từ file Excel</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="reason" class="control-label text-semibold">Tệp tin</label>
                            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                               data-content="Lý do từ chối đơn đăng ký cảu Sản phẩm"></i>
                            <input id="reason" type="file" name="file">
                        </div>
                        @if(auth('staff')->user()->can('check-prefix-import-product'))
                            <div class="form-group">
                                <label for="reason" class="control-label text-semibold">Bỏ check prefix</label>
                                <input type="checkbox" id="" name="prefix"
                                       value="1" class="js-checkbox">
                            </div>
                        @endif
                        @if(auth('staff')->user()->can('check-vendor-import-product'))
                            <div class="form-group">
                                <label for="reason" class="control-label text-semibold">Bỏ check sai định
                                    dạng</label>
                                <input type="checkbox" id="" name="vendor"
                                       value="1" class="js-checkbox">
                            </div>
                        @endif
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

    <div class="modal fade" id="import-modal" tabindex="-1" role="dialog" aria-labelledby="import-modal-label"
         data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('Staff::Management::product2@import') }}"
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
                        @if(auth('staff')->user()->can('check-prefix-import-product'))
                            <div class="form-group">
                                <label for="reason" class="control-label text-semibold">Bỏ check prefix</label>
                                <input type="checkbox" id="" name="prefix"
                                       value="1" class="js-checkbox">
                            </div>
                        @endif
                        @if(auth('staff')->user()->can('check-vendor-import-product'))
                            <div class="form-group">
                                <label for="reason" class="control-label text-semibold">Bỏ check sai định
                                    dạng</label>
                                <input type="checkbox" id="" name="vendor"
                                       value="1" class="js-checkbox">
                            </div>
                        @endif
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

    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="delete-modal-label">Xoá Sản phẩm</h4>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xoá Sản phẩm <strong class="text-danger js-product-name"></strong> khỏi hệ
                    thống của iCheck?
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

    <div class="modal fade" id="batch-disapprove-modal" tabindex="-1" role="dialog"
         aria-labelledby="batch-disapprove-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="batch-disapprove-modal-label">Từ chối nhiều đơn đăng ký Sản phẩm</h4>
                </div>
                <form action="{{ route('Staff::Management::product@batchDisapprove') }}" class="js-batch-form"
                      method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="reason" class="control-label text-semibold">Lý do từ chối</label>
                            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                               data-content="Lý do từ chối đơn đăng ký cảu Sản phẩm"></i>
                            <textarea id="reason" name="reason" rows="5" cols="5" class="form-control"
                                      placeholder="Lý do"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
                        <button type="submit" class="btn btn-danger">Xác nhận</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="batch-activate-modal" tabindex="-1" role="dialog"
         aria-labelledby="batch-activate-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="batch-activate-modal-label">Kích hoạt nhiều Sản phẩm</h4>
                </div>
                <form action="{{ route('Staff::Management::product@batchActivate') }}" class="js-batch-form"
                      method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            Bạn có chắc chắn thực hiện hành động này?
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

    <div class="modal fade" id="batch-deactivate-modal" tabindex="-1" role="dialog"
         aria-labelledby="batch-deactivate-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="batch-deactivate-modal-label">Huỷ kích hoạt nhiều Sản phẩm</h4>
                </div>
                <form action="{{ route('Staff::Management::product@batchDeactivate') }}" class="js-batch-form"
                      method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            Bạn có chắc chắn thực hiện hành động này?
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

    <div class="modal fade" id="approve-modal" tabindex="-1" role="dialog" aria-labelledby="approve-modal-label"
         data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="approve-modal-label">Chấp nhận đơn Đăng ký của doanh nghiệp</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="id" class="control-label text-semibold">Email đăng nhập</label>
                            <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                               data-content="Email mà Sản phẩm sẽ dùng để đăng nhập vào hệ thống <strong>iCheck cho doanh nghiệp</strong>. <strong class='text-danger'>Email này phải là duy nhất</strong> trên toàn hệ thống <strong>iCheck cho doanh nghiệp</strong>."></i>
                            <input type="email" id="email" name="login_email" class="form-control" required="required"/>
                        </div>

                        <div class="form-group">
                            Sử dụng mật khẩu ngẫu nhiên
                            <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                               data-content="Hệ thống sẽ sẽ tạo ra một mật khẩu ngẫu nhiên cho Sản phẩm."></i>
                            <a id="show-password-inputs" href="#">Đặt mật khẩu</a>
                        </div>

                        <div id="password-inputs" class="hidden">
                            <div class="form-group">
                                <label for="passwrod" class="control-label text-semibold">Mật khẩu</label>
                                <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                   data-content="Mật khẩu đăng nhập vào hệ thống <strong>iCheck cho doanh nghiệp</strong> của Sản phẩm."></i>
                                <input type="password" id="password" name="password" class="form-control"/>
                                <a id="hide-password-inputs" href="#">Sử dụng mật khẩu ngẫu nhiên</a>
                            </div>

                            <div class="form-group">
                                <label for="password-confirmation" class="control-label text-semibold">Xác nhận Mật
                                    khẩu</label>
                                <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                   data-content="Nhập lại mật khẩu ở trên."></i>
                                <input type="password" id="password-confirmation" name="password_confirmation"
                                       class="form-control"/>
                            </div>

                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="password-change-required"
                                               name="password_change_required" class="js-checkbox">
                                        <span class="text-semibold">Yêu cầu Sản phẩm đổi mật khẩu trong lần đăng nhập đầu tiên</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PUT">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
                        <button type="submit" class="btn btn-primary">Kích hoạt</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="disapprove-modal" tabindex="-1" role="dialog" aria-labelledby="disapprove-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="disapprove-modal-label">Từ chối yêu cầu đăng ký của Doamh nghiệp</h4>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="reason" class="control-label text-semibold">Lý do từ chối</label>
                            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                               data-content="Lý do từ chối đơn đăng ký cảu Sản phẩm"></i>
                            <textarea id="reason" name="reason" rows="5" cols="5" class="form-control"
                                      placeholder="Lý do"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
                        <button type="submit" class="btn btn-danger">Xác nhận</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="batch-delete-modal" tabindex="-1" role="dialog"
         aria-labelledby="batch-delete-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="batch-delete-modal-label">Xoá nhiều Sản phẩm</h4>
                </div>
                <form action="{{ route('Staff::Management::product@batchDelete') }}" class="js-batch-form"
                      method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            Bạn có chắc chắn thực hiện hành động này?
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
                        <button type="submit" class="btn btn-danger">Xác nhận</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="preloader" style="display:none">
        <div class="clock">
            <div class="arrow_sec"></div>
            <div class="arrow_min"></div>
        </div>
    </div>


@endsection

@push('js_files_foot')

<script src="//cdn.ckeditor.com/4.5.10/full/ckeditor.js"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
@endpush

@push('scripts_foot')

<script>
    var ajaxUrl = '{{ route('Ajax::Staff::Management::product2@index') }}';
    var url = '{{ route('Ajax::Staff::Management::product2@index') }}';

    $(document).ready(function(){
        var param = location.search;

        url = url+param;
        callAjax(url);
        if(!localStorage.columnProduct){
            var size = $('#table-data > thead > tr > th').length -1;
            var array = [];
            $('#table-data > thead > tr > th').each(function( index,value ) {
                if(index > 0 && index < size){
                    array.push(index.toString());
                }
            });
            localStorage.columnProduct = JSON.stringify(array);
        }
        renderColum();
        $('.js-checkbox').uniform({
            radioClass: "choice"
        });

    });
    function callAjax(url) {
        $('#tbody').html('<tr><td class="" colspan="' +$('#table-data').find('tr > th').length + '"><div class="loader"></div></td></tr>');
        $.ajax({
            type: "get",
            url: url,
            headers: {
                'X-CSRF-Token': "{{ csrf_token() }}"
            },
            success: function (data) {
                if (data.data.length > 0) {
                    var pagination = data.meta;


                    var products = data.data;
                    var string = '';
                    $.each(products, function (index, product) {
                        string += template(product);
                    });
                    $('#pagination').html(renderPagination(pagination));
                    $('#tbody').html(string);
                    var array = [];
                    if(localStorage.columnProduct){
                        array = JSON.parse(localStorage.columnProduct);
                    }

                    var size = $('#table-data > thead > tr > th').length -1;
                    $('#tbody > tr').each(function( index,value ) {
                        var id = $(this).attr('id');
                        $(this).find('td').each(function(i,v){
                            if(i > 0 && i < size){
                                if (array.indexOf(i.toString()) != -1) {
                                } else {
                                    $('#'+id).find('td').eq(i).hide()
                                }
                            }
                        });

                    });
                    $(".js-attr").select2();
                    $(".js-categories-select").select2({
                        templateResult: function (item) {
                            if (!item.id) {
                                return item.text;
                            }

                            var originalOption = item.element,
                                    prefix = "----------".repeat(parseInt($(item.element).data('level'))),
                                    item = (prefix ? prefix + '| ' : '') + item.text;

                            return item;
                        },
                        templateSelection: function (item) {
                            return item.text;
                        },
                        escapeMarkup: function (m) {
                            return m;
                        },
                        closeOnSelect: false,
                        dropdownCssClass: 'border-primary',
                        containerCssClass: 'border-primary text-primary-700'
                    });
                }else{
                    $('#tbody').html('<tr><td class="h1 text-center text-muted" colspan="' +$('#table-data').find('tr > th').length + '"><i class="icon-info"></i> Không có dữ liệu tương ứng</td></tr>');
//
                }

            },
            error: function () {
                alert('Lỗi, hãy thử lại sau');
            }
        });
    }
    function renderColum(){
        var string = '';
        var array = [];
        if(localStorage.columnProduct){
            array = JSON.parse(localStorage.columnProduct);
        }

        var size = $('#table-data > thead > tr > th').length -1;
        var checked = '';
        $('#table-data > thead > tr > th').each(function( index,value ) {
            if(index > 0 && index < size){
                checked = '';
                if(array.indexOf(index.toString()) != -1)
                {
                    checked = 'checked';
                }else{
                    $('#table-data thead tr').find('th').eq(index).hide();
                }

                string += '<li><label class="checkbox"> <input type="checkbox"  class="js-checkbox" data-index="'+index+'" '+checked+'><span class="checkbox_column">'+$(this).text()+'</span></label></li>';
            }
        });

        $('#column-show-hidden').append(string);
    }
    function template(product){
       var s = '';
        s += '<tr id='+product.id+'>';

        s+='<td><input type="checkbox" name="selected[]" class="js-checkbox" value="'+product.id+'"/> </td>';

        s += '<td>' ; s+= product.renderName; s += '</td>';
        s += '<td>' ; s+= product.barcode; s += '</td>';
        s += '<td>' ; s+= product.renderImage; s += '</td>';
        s += '<td class="td_price">' ; s+= product.renderPrice; s += '</td>';
        s += '<td>' ; s+= product.renderGln; s += '</td>';
        s += '<td>' ; s+= product.renderA1; s += '</td>';
        s += '<td>' ; s+= product.scan_count; s += '</td>';
        s += '<td>' ; s+= product.view_count; s += '</td>';
        s += '<td>' ; s+= product.like_count; s += '</td>';
        s += '<td>' ; s+= product.vote_count; s += '</td>';
        s += '<td>' ; s+= product.comment_count; s += '</td>';
        s += '<td>' ; s+= product.report_count; s += '</td>';

        s += '<td >' ;
        s+= ' <select class="select-border-color border-warning js-categories-select js-category-product" multiple="multiple" data-product="'+product.id+'" data-url="'+product.links.inline+'" data-attr="categories">';
        for (var i = 0; i < product.listCategories.length; i++) {
            s+= '<option value="'+product.listCategories[i].id+'" ';
            if(jQuery.inArray(product.listCategories[i].id, product.selectedCategories) !== -1){
                  s+= 'selected="selected"';
            }
            s+='>'+product.listCategories[i].name+'</option>';
        }

        s+= '</select>';
        s += '</td>';
        s += '<td>' ;
        s+= '<div class="blockProperties" id="properties'+product.id+'" data-id='+product.id+'>';
        s+= product.renderProperties ;



        s += '</div></td>';
        s += '<td id="features'+product.id+'">' ; s+= product.features; s += '</td>';
        s += '<td >' ; s+= product.mapped; s += '</td>';
        s += '<td>' ; s+= product.created_at; s += '</td>';
        s += '<td>' ; s+= product.updated_at; s += '</td>';
        s += '<td>' ; s+= product.renderDropDown; s += '</td>';

        s += '</tr>';
      return s;
    }
    function renderPagination(pagination){
        var data = pagination.pagination;
        var string = '';

        if(data.count > 0){

            var number_from = (data.current_page-1)*data.per_page+1;
            var number_to = data.count + number_from -1;
            string = '<span><strong id="from-record">'+number_from+'</strong> - <strong id="to-record">'+number_to+'</strong> trong tổng số <strong id="total-records">'+data.total+'</strong></span>';
            string+='<div class="btn-toolbar display-inline-block"> <div class="btn-group">';
            if(typeof data.links.previous !== 'undefined'){
                string+='<button class="btn btn-default legitRipple" id="prev-page-btn" data-url="'+data.links.previous+'"> <i class="icon-arrow-left3"></i> </button>';
            }else{
                string+='<button class="btn btn-default legitRipple" id="prev-page-btn" disabled=""> <i class="icon-arrow-left3"></i> </button>';
            }
            if(typeof data.links.next !== 'undefined'){
                string+='<button class="btn btn-default legitRipple" id="next-page-btn" data-url="'+data.links.next+'"><i class="icon-arrow-right3"></i> </button> </div> </div>';
            }else{
                string+='<button class="btn btn-default legitRipple" id="next-page-btn" disabled=""> <i class="icon-arrow-right3"></i> </button> </div> </div>';
            }

        }
        return string;

    }
    $('#btn-export').click(function(){
        var dataform = $('#form-search').serialize();
        window.open(ajaxUrl+'?'+dataform+'&export=1');
    });
    //click previous pagination
    $(document).on('click','#next-page-btn',function(e){
        var nextUrl = $(this).attr('data-url');
        if(nextUrl){
            callAjax(nextUrl);
        }
    });
    $(document).on('click','.js-checkbox',function(e){
        var index = $(this).attr('data-index');

        if ($(this).is(':checked')) {
            if(localStorage.columnProduct){
                var array = JSON.parse(localStorage.columnProduct);
                array.push(index);


                 array = array.unique2();
                localStorage.columnProduct = JSON.stringify(array);

            }else{
                var array = [];
                array.push(index);
                localStorage.columnProduct = JSON.stringify(array);
            }
            $('#table-data thead tr').find('th').eq(index).show();
            $('#tbody > tr').each(function( key,value ) {
                var id = $(this).attr('id');
                $(this).find('td').each(function(i,v){
                    $('#'+id).find('td').eq(index).show()
                });

            });
        }else{
            if(localStorage.columnProduct){
                var array = JSON.parse(localStorage.columnProduct);
                array.remove(index);
                array = array.unique2();
                localStorage.columnProduct = JSON.stringify(array);
            }
            $('#table-data thead tr').find('th').eq(index).hide();
            $('#tbody > tr').each(function( key,value ) {
                var id = $(this).attr('id');
                $(this).find('td').each(function(i,v){
                    $('#'+id).find('td').eq(index).hide()
                });

            });
        }

    });
    Array.prototype.unique2 = function unique()
    {
        var n = {},r=[];
        for(var i = 0; i < this.length; i++)
        {
            if (!n[this[i]])
            {
                n[this[i]] = true;
                r.push(this[i]);
            }
        }
        return r;
    }
    Array.prototype.remove = function() {
        var what, a = arguments, L = a.length, ax;
        while (L && this.length) {
            what = a[--L];
            while ((ax = this.indexOf(what)) !== -1) {
                this.splice(ax, 1);
            }
        }
        return this;
    };
    Array.prototype.contains = function(obj) {
        var i = this.length;
        while (i--) {
            if (this[i] === obj) {
                return true;
            }
        }
        return false;
    }
    $(document).on('click','#prev-page-btn',function(e){
        var prevUrl = $(this).attr('data-url');
        if(prevUrl){
            callAjax(prevUrl);
        }
    });
    $(document).on('click','#search',function(e){
        e.preventDefault();
        var dataform = $('#form-search').serialize();
        callAjax(ajaxUrl+'?'+dataform);
    });


    $(document).on('change','.properties-product',function(e){
        var value = $(this).val();
        var attr_id = $(this).parent().parent().attr('data-id');
        var product_id = $(this).parent().parent().parent().attr('data-id');
//        updateAttrInline
        var urlInline = '{{route('Ajax::Staff::Management::product2@updateAttrInline')}}';
             $("#preloader").fadeIn();
        $.ajax({
            type: "POST",
            url: urlInline,
            headers: {
                'X-CSRF-Token': "{{ csrf_token() }}"
            },
            data: {
                value: value,
                attr_id:attr_id,
                product_id:product_id
            },
            success: function (data) {
                  var features = data.features;
                $('#features'+product_id).html(features);

                if(data.delete){
                    $('#'+product_id+attr_id).remove();
                }
                $("#preloader").fadeOut();
            },
            error: function () {
                $("#preloader").fadeOut();
                alert('Lỗi, hãy thử lại sau');
            }
        });
    });
    $('.js-batch-form').on('submit', function (e) {
        var $this = $(this),
                ids = [];

        $this.find('button[type="submit"]').prop('disabled', true);

        App.products.checked().forEach(function (product) {
            ids.push(product.get('id'));
        });

        var $input = $('<input>').attr({'type': 'hidden', 'name': 'ids'}).val(ids);
        $this.append($input);
    });

    $('#approve-modal, #disapprove-modal, #delete-modal').on('show.bs.modal', function (event) {
        var $btn = $(event.relatedTarget),
                $modal = $(this);

        $modal.find('form').attr('action', $btn.data('action-url'));
        $modal.find('.js-product-name').text($btn.data('name'));
    });

    // Toggle password inputs
    $(document).on('click', 'a#show-password-inputs', function (e) {
        e.preventDefault();

        $('#password-inputs').removeClass('hidden').prev().addClass('hidden');
    });

    $(document).on('click', 'a#hide-password-inputs', function (e) {
        e.preventDefault();

        $('#password-inputs').addClass('hidden').prev().removeClass('hidden');
    });

    // Initialize with options
    $(".js-categories-select").select2({
        templateResult: function (item) {
            if (!item.id) {
                return item.text;
            }

            var originalOption = item.element,
                    prefix = "----------".repeat(parseInt($(item.element).data('level'))),
                    item = (prefix ? prefix + '| ' : '') + item.text;

            return item;
        },
        templateSelection: function (item) {
            return item.text;
        },
        escapeMarkup: function (m) {
            return m;
        },
        dropdownCssClass: 'border-primary',
        containerCssClass: 'border-primary text-primary-700'
    });

    var oldData = {};
    $(document).on('focus', '.editable', function () {
        var $this = $(this);
        var gtin = $this.data('gtin');
        var attr = $this.data('attr');
        var old = $this.val();

        if (!oldData[gtin]) {
            oldData[gtin] = {};
        }

        oldData[gtin][attr] = old;
    });

    $(document).on('blur', '.editable', function () {
        var $this = $(this);
        var gtin = $this.data('gtin');
        var attr = $this.data('attr');
        var url = $this.data('url');
        var newVal = $this.val();

        if (newVal !== oldData[gtin][attr]) {
            var data = {};

            if (attr === "description") {
                data = {
                    "attrs": {
                        "1": newVal
                    }
                };
            } else if (attr === "name") {
                if (newVal == '') {
                    newVal = 'dell-all-1994';
                }
                data = {
                    "product_name": newVal
                };
            } else if (attr === "price") {
                data = {
                    "price_default": newVal
                };
            }

            $.ajax({
                type: "PUT",
                url: url,
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                data: data,
                success: function () {
                },
                error: function () {
                    alert('Lỗi, hãy thử lại sau');
                }
            });
        }
    });

    $(document).on('click', '.addFile', function (e) {
        e.preventDefault();
        $(this).prev().trigger('click');
    });

    $(document).on('change', '.fileaaa', function (e) {
        var $this = $(this);
        var gtin = $this.data('gtin');
        var attr = $this.data('attr');
        var url = $this.data('url');

        var newVal = $this.val();

        var formData = new FormData(this);
        formData.append("file", e.target.files[0]);

        $.ajax({
            type: 'POST',
            url: '{{ route('Ajax::Staff::upload@image') }}',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {

                var images = [];

                $this.prev('.aimages').find('.aimage').each(function () {
                    images.push($(this).data('image'));
                });

                images.push(data.prefix);

                $.ajax({
                    type: "PUT",
                    url: url,
                    headers: {
                        'X-CSRF-Token': "{{ csrf_token() }}"
                    },
                    data: {
                        "images": images,
                    },
                    success: function () {
                        $this.prev('.aimages').append('<li><a href="' + data.url + '" class="aimage" data-image="' + data.prefix + '" target="_blank"><img src="' + data.url + '" width="50" /></a><a href="#" class="rmfile">x</a>');
                    },
                    error: function () {
                        alert('Lỗi, hãy thử lại sau');
                    }
                });
            },
            error: function (data) {
                alert('Loi roi aaaaa!')
            }
        });
    });

    $(document).on('click', '.rmfile', function (e) {
        e.preventDefault();
        var $this = $(this);
        var $this2 = $(this).parents('td').find('.fileaaa');
        var gtin = $this2.data('gtin');
        var attr = $this2.data('attr');
        var url = $this2.data('url');

        $this.parents('li').remove();

        var images = [];

        $this2.prev('.aimages').find('.aimage').each(function () {
            images.push($(this).data('image'));
        });

        if (images.length == 0) {
            images = 'del-all';
        }

        $.ajax({
            type: "PUT",
            url: url,
            headers: {
                'X-CSRF-Token': "{{ csrf_token() }}"
            },
            data: {
                "images": images,

            },
            success: function () {
            },
            error: function () {
                alert('Lỗi, hãy thử lại sau');
            }
        });
    });


    $(".js-categories-select").select2({
        templateResult: function (item) {
            if (!item.id) {
                return item.text;
            }

            var originalOption = item.element,
                    prefix = "----------".repeat(parseInt($(item.element).data('level'))),
                    item = (prefix ? prefix + '| ' : '') + item.text;

            return item;
        },
        templateSelection: function (item) {
            return item.text;
        },
        escapeMarkup: function (m) {
            return m;
        },
        closeOnSelect: false,
        dropdownCssClass: 'border-primary',
        containerCssClass: 'border-primary text-primary-700'
    });

    $(".js-categories-select").on('select2:select', function () {
        $(this).select2("search", "");
    });
    //change category => get attr
    $(document).on('select2:select','.js-category-product',function(e){
        var id_select = e.params.data.id;
        var product_id = $(this).attr('data-product');

        var urladdAttrInline = '{{route('Ajax::Staff::Management::product2@addAttrInline')}}';
        $.ajax({
            type: "POST",
            url: urladdAttrInline,
            headers: {
                'X-CSRF-Token': "{{ csrf_token() }}"
            },
            data: {
                id : id_select,
                product_id:product_id
            },
            dataType:'json',
            success: function (data) {
                $.each(data, function(index, value) {
                    if($('#'+product_id+index).length > 0){
                        var count = parseInt($('#'+product_id+index).attr('data-count'));
                        $('#'+product_id+index).attr('data-count',count+1);
                    }else{
                        $('#properties'+product_id).append(value);
                        $('.js-attr').select2({
                            dropdownCssClass: 'border-primary',
                            containerCssClass: 'border-primary text-primary-700'
                        });
                    }

                });

            },
            error: function () {
                alert('Lỗi, hãy thử lại sau');
            }
        });
    });
    {{--$(document).on('select2:unselect','.js-category-product',function(e){--}}
        {{--var category_id = e.params.data.id;--}}
        {{--if(category_id){--}}
            {{--var urlgetAttrIdCategory = '{{route('Ajax::Staff::Management::product2@getAttrIdCategory')}}';--}}
            {{--$.ajax({--}}
                {{--type: "POST",--}}
                {{--url: urlgetAttrIdCategory,--}}
                {{--headers: {--}}
                    {{--'X-CSRF-Token': "{{ csrf_token() }}"--}}
                {{--},--}}
                {{--data: {--}}
                    {{--category_id:category_id--}}
                {{--},--}}
                {{--dataType:'json',--}}
                {{--success: function (data) {--}}
                    {{--data = data.split(',');--}}
                    {{--data.forEach(function(id){--}}
                        {{--if($('#'+id).length > 0){--}}
                            {{--var count = parseInt($('#'+id).attr('data-count'));--}}
                            {{--if(count < 2){--}}
                                {{--$('#'+id).remove();--}}
                            {{--}else{--}}
                                {{--$('#'+index).attr('data-count',count-1);--}}
                            {{--}--}}

                        {{--}--}}

                    {{--});--}}
                {{--},--}}
                {{--error: function () {--}}
                {{--}--}}
            {{--});--}}

        {{--}--}}
    {{--});--}}
    $(document).on('change', '.js-category-product', function (e) {
        $("#preloader").fadeIn();
        var categories = $(this).val();
        var url = $(this).data('url');
        if(categories == null){
            categories = 'del-all';
        }
        $.ajax({
            type: "PUT",
            url: url,
            headers: {
                'X-CSRF-Token': "{{ csrf_token() }}"
            },
            data: {
                categories: categories
            },
            success: function () {
                $("#preloader").fadeOut();
            },
            error: function () {
                $("#preloader").fadeOut();
                alert('Lỗi, hãy thử lại sau');
            }
        });
    });

    $('#btn-report').click(function(){
//  reportHCM

        $.ajax({
            type: "GET",
            url: "{{ route('Staff::Management::product2@reportHCM') }}",
            headers: {
                'X-CSRF-Token': "{{ csrf_token() }}"
            },
            success: function () {
                alert("File sẽ được gửi về email của hươngCM!Vui lòng đợi");
            },
            error: function () {
                alert('Lỗi, hãy thử lại sau');
            }
        });
    });
</script>
@endpush

