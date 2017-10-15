@extends('_layouts/staff')

@section('page_title', 'Sản phẩm')

@section('content')
  <style>
    .price {
      width:80%;
      float:left
    }
    .currency{
      padding: 8px 0px;
      height: 20px;
      display: block;
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
          <a href="{{ route('Staff::Management::product2@add') }}" class="btn btn-link"><i class="icon-plus-circle"></i> Thêm Sản phẩm</a>
          <a href="#" class="btn btn-link" data-toggle="modal" data-target="#import-modal2"><i class="icon-plus-circle"></i> Thêm từ file Excel</a>
          <a href="#" class="btn btn-link" data-toggle="modal" data-target="#import-modal"><i class="icon-plus-circle"></i> Cập nhật từ file Excel</a>

          <button type="button" id="btn-batch-disapprove" data-action="disapprove" disabled="disabled" class="btn btn-grey js-batch-button" data-toggle="modal" data-target="#batch-disapprove-modal">
            <i class="icon-cancel-circle"></i> Không chấp nhận</a>
          </button>
          <button type="button" id="btn-batch-activate" data-action="activate" disabled="disabled" class="btn btn-success js-batch-button" data-toggle="modal" data-target="#batch-activate-modal">
            <i class="icon-checkmark-circle"></i> Kích hoạt</a>
          </button>
          <button type="button" id="btn-batch-deactivate" data-action="deactivate" disabled="disabled" class="btn btn-grey js-batch-button" data-toggle="modal" data-target="#batch-deactivate-modal">
            <i class="icon-cancel-circle"></i> Huỷ kích hoạt</a>
          </button>
          <!-- <button type="button" id="btn-batch-delete" data-action="delete" disabled="disabled" class="btn btn-danger js-batch-button" data-toggle="modal" data-target="#batch-delete-modal">
            <i class="icon-bin"></i> Xoá</a>
          </button> -->
          <button href="#" id="btn-report" target="_blank" class="btn btn-primary"><i class="icon-plus-circle"></i> Xuất Report</button>
          <a href="#" id="btn-export" target="_blank" class="btn btn-primary"><i class="icon-plus-circle"></i> Xuất ra Excel</a>
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
          <div class="col-md-2 mb-20">
            <form id="query-filter">
              <div class="has-feedback has-feedback-left">
                <input type="text" name="q" class="form-control" value="{{ Request::input('q') }}" placeholder="Tìm kiếm theo tên">
                <div class="form-control-feedback">
                  <i class="icon-search"></i>
                </div>
              </div>
            </form>
          </div>
          <div class="col-md-2 mb-20">
            <form id="gtin-filter">
              <div class="has-feedback has-feedback-left">
                <input type="text" name="gtin" class="form-control" value="{{ Request::input('gtin') }}" placeholder="Tìm kiếm theo GTIN">
                <div class="form-control-feedback">
                  <i class="icon-search"></i>
                </div>
              </div>
            </form>
          </div>
          <div class="col-md-1 mb-20">
            <form id="gln-filter">
              <div class="has-feedback has-feedback-left">
                <input type="text" name="gln" class="form-control" value="{{ Request::input('gln') }}" placeholder="Tìm theo GLN ...">
                <div class="form-control-feedback">
                  <i class="icon-barcode2"></i>
                </div>
              </div>
            </form>
          </div>
          <div class="col-md-6 mb-20">
            <div class="row">
              <div class="col-md-2">
                <form id="price-filter">
                  <div class="has-feedback has-feedback-left">
                    <input type="text" name="price" class="form-control" value="{{ Request::input('price') }}" placeholder="Giá. VD: < 1000">
                    <div class="form-control-feedback">
                      <i class="icon-coin-dollar"></i>
                    </div>
                  </div>
                </form>
              </div>
              <div class="col-md-5">
                <select id="category-filter" class="select-border-color border-warning js-categories-select">
                  <option value="">Tất cả danh mục</option>
                  <option value="none">Không thuộc danh mục nào</option>
                  @foreach ($categories as $category)
                  <option value="{{ $category->id }}" data-level="{{ $category->level }}" {{ Request::input('gln') == $category->id ? ' selected="selected"' : '' }}>{{ $category->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="col-md-3">
                <select class="form-control" id="image-filter">
                  <option value="">Tất cả Sẩn phẩm</option>
                  <option value="1" {{ Request::input('image') == 1 ? ' selected="selected"' : '' }}>Sản phẩm đã có ảnh</option>
                  <option value="2" {{ Request::input('image') == 2 ? ' selected="selected"' : '' }}>Sản phẩm chưa có ảnh</option>
                </select>
              </div>
              <div class="col-md-2">
                <select class="form-control" id="cat-filter">
                  <option value="">Tất cả Sẩn phẩm</option>
                  <option value="1" {{ Request::input('vendor') == 1 ? ' selected="selected"' : '' }}>Sản phẩm đã có vendor</option>
                  <option value="2" {{ Request::input('vendor') == 2 ? ' selected="selected"' : '' }}>Sản phẩm chưa có vendor</option>
                </select>
              </div>
            </div>
          </div>
          <div class="col-md-12 mb-20 text-right">
            <span><strong id="from-record">0</strong> - <strong id="to-record">0</strong> trong tổng số <strong id="total-records">0</strong></span>
            <div class="btn-toolbar display-inline-block">
              <div class="btn-group">
                <button class="btn btn-default" id="prev-page-btn" disabled="disabled">
                  <i class="icon-arrow-left3"></i>
                </button>
                <button class="btn btn-default" id="next-page-btn" disabled="disabled">
                  <i class="icon-arrow-right3"></i>
                </button>
              </div>
              <div class="btn-group">
                <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                  <i class="icon-cog position-left"></i><span class="sr-only">Tùy chọn</span><span class="caret"></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-right dropdown-options">
                  <li id="columns-toggle-header" class="dropdown-header">Hiện/ Ẩn cột</li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        @if ($errors->count())
          @foreach ($errors->all() as $error)
            <div class="alert bg-danger alert-styled-left">
              <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
              {{ $error }}
            </div>
          @endforeach
        @endif

        @if (session('success'))
          <div class="alert bg-success alert-styled-left">
            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
            {{ session('success') }}
          </div>
        @elseif (session('danger'))
          <div class="alert bg-danger alert-styled-left">
            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
            {{ session('danger') }}
          </div>
        @endif

        <form id="batch-form" method="POST">
          {{ csrf_field() }}
          <input type="hidden" name="action" />
          <div id="products" class="panel panel-flat position-relative">
            <div class="loading-bar is-loading"><div class="slow"></div></div>
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>
                    <input type="checkbox" id="toggle-select-all" class="js-checkbox" />
                    <div class="dropdown display-inline-block">
                      <a href="#" data-toggle="dropdown">
                        <span class="caret"></span>
                      </a>
                      <ul class="dropdown-menu">
                        <li><a href="#" class="js-select-all">Chọn tất cả</a></li>
                        <li><a href="#" class="js-select-none">Bỏ chọn tất cả</a></li>
                        <li><a href="#" class="js-select-pending-activation">Sản phẩm chờ kích hoạt</a></li>
                        <li><a href="#" class="js-select-activated">Sản phẩm đã kích hoạt</a></li>
                        <li><a href="#" class="js-select-deactivated">Sản phẩm ngưng kích hoạt</a></li>
                      </ul>
                    </div>
                  </th>
                  <th data-sortable="true" data-sort-by="name">
                    Tên <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Sản phẩm"></i>
                  </th>
                  <th>
                    Barcode <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Logo của Sản phẩm"></i>
                  </th>
                  <th data-hideable="true">
                    Hình ảnh <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Logo của Sản phẩm"></i>
                  </th>
                  <th data-hideable="true" data-sortable="true" data-sort-by="price">
                    Giá bán <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Logo của Sản phẩm"></i>
                  </th>
                  <th data-hideable="true">
                    GLN <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Logo của Sản phẩm"></i>
                  </th>
                  <th data-hideable="true">
                    Thông tin <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Logo của Sản phẩm"></i>
                  </th>
                  <th data-hideable="true">
                    Nhà sản xuất <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Logo của Sản phẩm"></i>
                  </th>
                  <th data-hideable="true" data-sortable="true" data-sort-by="scan_count">
                    Lượt quét <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Ngày đăng ký của Sản phẩm trên hệ thống iCheck for Business"></i>
                  </th>
                  <th data-hideable="true" data-sortable="true" data-sort-by="view_count">
                    Lượt xem <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Ngày đăng ký của Sản phẩm trên hệ thống iCheck for Business"></i>
                  </th>
                  <th data-hideable="true" data-sortable="true" data-sort-by="like_count">
                    Lượt thích <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Ngày đăng ký của Sản phẩm trên hệ thống iCheck for Business"></i>
                  </th>
                  <th data-hideable="true" data-sortable="true" data-sort-by="vote_count">
                    Lượt đánh giá <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Ngày đăng ký của Sản phẩm trên hệ thống iCheck for Business"></i>
                  </th>
                  <th data-hideable="true" data-sortable="true" data-sort-by="comment_count">
                    Lượt bình luận <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Ngày đăng ký của Sản phẩm trên hệ thống iCheck for Business"></i>
                  </th>
                  <th data-hideable="true" data-sortable="true" data-sort-by="report_count">
                    Số report <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Ngày đăng ký của Sản phẩm trên hệ thống iCheck for Business"></i>
                  </th>
                  <th data-hideable="true" data-sortable="false">
                    Danh mục <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Ngày đăng ký của Sản phẩm trên hệ thống iCheck for Business"></i>
                  </th>
                  <th data-hideable="true">
                    Feature <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Trạng thái tài khoản của Sản phẩm trên hệ thống iCheck for Business"></i>
                  </th>
                  <th data-hideable="true" data-sortable="true" data-sort-by="created_at">
                    Ngày tạo <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Ngày đăng ký của Sản phẩm trên hệ thống iCheck for Business"></i>
                  </th>
                  <th data-hideable="true" data-sortable="true" data-sort-by="updated_at">
                    Ngày cập nhật <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Ngày đăng ký của Sản phẩm trên hệ thống iCheck for Business"></i>
                  </th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
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

<div class="modal fade" id="import-modal2" tabindex="-1" role="dialog" aria-labelledby="import-modal2-label" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST" action="{{ route('Staff::Management::product2@import', ['new' => 1]) }}" enctype="multipart/form-data">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="import-modal2-label">Nhập nhiều sản phẩm từ file Excel</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="reason" class="control-label text-semibold">Tệp tin</label>
            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do từ chối đơn đăng ký cảu Sản phẩm"></i>
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
              <label for="reason" class="control-label text-semibold">Bỏ check sai định dạng</label>
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

<div class="modal fade" id="import-modal" tabindex="-1" role="dialog" aria-labelledby="import-modal-label" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST" action="{{ route('Staff::Management::product2@import') }}" enctype="multipart/form-data">
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
          @if(auth('staff')->user()->can('check-prefix-import-product'))
            <div class="form-group">
              <label for="reason" class="control-label text-semibold">Bỏ check prefix</label>
              <input type="checkbox" id="" name="prefix"
                     value="1" class="js-checkbox">
            </div>
            @endif
          @if(auth('staff')->user()->can('check-vendor-import-product'))
          <div class="form-group">
            <label for="reason" class="control-label text-semibold">Bỏ check sai định dạng</label>
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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="delete-modal-label">Xoá Sản phẩm</h4>
      </div>
      <div class="modal-body">
        Bạn có chắc chắn muốn xoá Sản phẩm <strong class="text-danger js-product-name"></strong> khỏi hệ thống của iCheck?
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

<div class="modal fade" id="batch-disapprove-modal" tabindex="-1" role="dialog" aria-labelledby="batch-disapprove-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="batch-disapprove-modal-label">Từ chối nhiều đơn đăng ký Sản phẩm</h4>
      </div>
      <form action="{{ route('Staff::Management::product@batchDisapprove') }}" class="js-batch-form" method="POST">
        <div class="modal-body">
          <div class="form-group">
            <label for="reason" class="control-label text-semibold">Lý do từ chối</label>
            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do từ chối đơn đăng ký cảu Sản phẩm"></i>
            <textarea id="reason" name="reason" rows="5" cols="5" class="form-control" placeholder="Lý do"></textarea>
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

<div class="modal fade" id="batch-activate-modal" tabindex="-1" role="dialog" aria-labelledby="batch-activate-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="batch-activate-modal-label">Kích hoạt nhiều Sản phẩm</h4>
      </div>
      <form action="{{ route('Staff::Management::product@batchActivate') }}" class="js-batch-form" method="POST">
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

<div class="modal fade" id="batch-deactivate-modal" tabindex="-1" role="dialog" aria-labelledby="batch-deactivate-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="batch-deactivate-modal-label">Huỷ kích hoạt nhiều Sản phẩm</h4>
      </div>
      <form action="{{ route('Staff::Management::product@batchDeactivate') }}" class="js-batch-form" method="POST">
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

<div class="modal fade" id="approve-modal" tabindex="-1" role="dialog" aria-labelledby="approve-modal-label" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="approve-modal-label">Chấp nhận đơn Đăng ký của doanh nghiệp</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="id" class="control-label text-semibold">Email đăng nhập</label>
            <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Email mà Sản phẩm sẽ dùng để đăng nhập vào hệ thống <strong>iCheck cho doanh nghiệp</strong>. <strong class='text-danger'>Email này phải là duy nhất</strong> trên toàn hệ thống <strong>iCheck cho doanh nghiệp</strong>."></i>
            <input type="email" id="email" name="login_email" class="form-control" required="required" />
          </div>

          <div class="form-group">
            Sử dụng mật khẩu ngẫu nhiên
            <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Hệ thống sẽ sẽ tạo ra một mật khẩu ngẫu nhiên cho Sản phẩm."></i>
            <a id="show-password-inputs" href="#">Đặt mật khẩu</a>
          </div>

          <div id="password-inputs" class="hidden">
            <div class="form-group">
              <label for="passwrod" class="control-label text-semibold">Mật khẩu</label>
              <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Mật khẩu đăng nhập vào hệ thống <strong>iCheck cho doanh nghiệp</strong> của Sản phẩm."></i>
              <input type="password" id="password" name="password" class="form-control" />
              <a id="hide-password-inputs" href="#">Sử dụng mật khẩu ngẫu nhiên</a>
            </div>

            <div class="form-group">
              <label for="password-confirmation" class="control-label text-semibold">Xác nhận Mật khẩu</label>
              <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Nhập lại mật khẩu ở trên."></i>
              <input type="password" id="password-confirmation" name="password_confirmation" class="form-control" />
            </div>

            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" id="password-change-required" name="password_change_required" class="js-checkbox">
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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="disapprove-modal-label">Từ chối yêu cầu đăng ký của Doamh nghiệp</h4>
      </div>
      <form method="POST">
        <div class="modal-body">
          <div class="form-group">
            <label for="reason" class="control-label text-semibold">Lý do từ chối</label>
            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do từ chối đơn đăng ký cảu Sản phẩm"></i>
            <textarea id="reason" name="reason" rows="5" cols="5" class="form-control" placeholder="Lý do"></textarea>
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

<div class="modal fade" id="batch-delete-modal" tabindex="-1" role="dialog" aria-labelledby="batch-delete-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="batch-delete-modal-label">Xoá nhiều Sản phẩm</h4>
      </div>
      <form action="{{ route('Staff::Management::product@batchDelete') }}" class="js-batch-form" method="POST">
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
@endsection

@push('js_files_foot')
  <script src="https://cdn.jsdelivr.net/uri.js/1.18.1/URI.min.js"></script>
<script src="//cdn.ckeditor.com/4.5.10/full/ckeditor.js"></script>
  <script src="https://cdn.jsdelivr.net/backbone.localstorage/1.1.16/backbone.localStorage-min.js"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('https://cdn.jsdelivr.net/jsbarcode/3.3.14/JsBarcode.all.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/backbone/backbone.paginator.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
@endpush

@push('scripts_foot')
  <script type="text/template" id="column-toggle-template">
    <label class="checkbox">
      <input type="checkbox" class="js-checkbox" data-index="@{{index}}" @{{#if show}} checked="checked" @{{/if}}>
      @{{title}}
    </label>
  </script>

  <script type="text/x-handlebars-template" id="product-template">
    <td>
      <input type="checkbox" name="selected[]" class="js-checkbox" value="@{{ id }}" @{{#if checked}} checked="checked" @{{/if}} />
    </td>
    <td>
      @{{#if isBusiness}}
      <input type="text" class="form-control editable" data-gtin="@{{ barcode }}" data-url="@{{ links.inline }}" data-attr="name" value="@{{ name }}" style="width: 200px;">
      @{{else}}
        @{{ name }}
      @{{/if}}
    </td>
    <td>
      @{{ barcode }}
    </td>
    <td>
      <ul class="aimages list-inline">
        @{{#if isBusiness}}
          @{{#each images}}
            <li><a href="@{{this.url}}" class="aimage" data-image="@{{this.prefix}}" target="_blank"><img src="@{{this.url}}" width="50" /></a>
              <a href="#" class="rmfile">x</a>
          @{{/each}}
        @{{ else }}
          @{{#each images}}
          <li><a href="@{{this.url}}" class="aimage" data-image="@{{this.prefix}}" target="_blank"><img src="@{{this.url}}" width="50" /></a>
          @{{/each}}
        @{{/if}}
      </ul>
      @{{#if isBusiness}}
      <input type="file" class="fileaaa" style="display:none" data-gtin="@{{ barcode }}" data-url="@{{ links.inline }}" data-attr="img" />
      <a href="#" class="addFile">Thêm</a>
      @{{/if}}
    </td>
    <td>
      @{{#if isBusiness}}
      <input type="text" class="form-control editable price" data-gtin="@{{ barcode }}" data-url="@{{ links.inline }}" data-attr="price" value="@{{ price }}"> <span class="currency">@{{ currency }}</span>
      @{{else}}
      @{{ price }} <span class="currency">@{{ currency }}</span>
      @{{/if}}
    </td>
    <td>
      <a href="?gln=@{{ vendor.data.gln }}">@{{ vendor.data.gln }}
        {{--<br> (@{{ vendor.data.name }})--}}
      </a>
    </td>
    <td>
      @{{#if isBusiness}}
      <textarea class="form-control editable ckeditor" data-gtin="@{{ barcode }}" data-url="@{{ links.inline }}"  data-attr="description"> @{{{ attributes.a1 }}}</textarea>
      @{{else}}
      @{{{ attributes.a1 }}}
      @{{/if}}
    </td>
    <td>
      @{{ vendor.data.name }}
    </td>
    <td>
      @{{ scan_count }}
    </td>
    <td>
      @{{ view_count }}
    </td>
    <td>
      @{{ like_count }}
    </td>
    <td>
      @{{ vote_count }}
    </td>
    <td>
      @{{ comment_count }}
    </td>
    <td>
      @{{ report_count }}
    </td>
    <td>
      <ul class="people_list">

        @{{#each categories.data}}
        <option>@{{this.name}}</option>
        @{{/each}}


      </ul>
    </td>
    <td>
      {{--@{{#if isActivated}}--}}
        {{--<span class="label label-success"><i class="icon-checkmark3"></i> @{{ status }}</span>--}}
      {{--@{{/if}}--}}
      {{--@{{#if isDeactivated}}--}}
        {{--<span class="label label-default"><i class="icon-checkmark3"></i> @{{ status }}</span>--}}
      {{--@{{/if}}--}}
      {{--@{{#if isPendingActivation}}--}}
        {{--<span class="label label-warning"><i class="icon-clock2"></i> @{{ status }}</span>--}}

        {{--<a href="@{{ links.self }}">Xem yêu cầu đăng ký</a>--}}
      {{--@{{/if}}--}}


      @{{ features }}
    </td>
    <td>@{{ created_at }}</td>
    <td>@{{ updated_at }}</td>
    <td>

      <div class="dropdown">
        <button id="product-@{{ id }}-actions" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link">
          <i class="icon-more2"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="product-@{{ id }}-actions">
          @{{#if isActivated}}
            {{--<li><a href="@{{ links.self }}"><i class="icon-profile"></i> Xem thông tin</a></li>--}}
          @{{/if}}
          @{{#if isPendingActivation}}
            {{--<li><a href="#" data-toggle="modal" data-target="#approve-modal" data-name="@{{ name }}" data-action-url="@{{ links.approve }}"><i class="icon-checkmark-circle"></i> Chấp nhận</a></li>--}}
            {{--<li><a href="#" data-toggle="modal" data-target="#disapprove-modal" data-name="@{{ name }}" data-action-url="@{{ links.disapprove }}"><i class="icon-cancel-circle"></i> Từ chối</a></li>--}}
          @{{/if}}
          @{{#if isBusiness}}
          <li><a href="@{{ links.edit }}"><i class="icon-pencil5"></i> Sửa</a></li>
          @{{/if}}
          <li><a href="@{{ links.relate }}"><i class="icon-pencil5"></i> Sản phẩm liên quan</a></li>
          <!-- <li><a href="#" data-toggle="modal" data-target="#delete-modal" data-name="@{{ name }}" data-action-url="@{{ links.delete }}"><i class="icon-bin"></i> Xoá</a></li> -->
        </ul>
      </div>

    </td>
  </script>
  <script>
  var uri = new URI;

  var Column = Backbone.Model.extend({
    defaults: function() {
      return {
        show: true
      }
    },

    idAttribute: 'index',

    toggle: function() {
      this.save('show', !this.get('show'));
    }
  });

  var ColumnView = Backbone.View.extend({
    tagName: 'li',
    template: Handlebars.compile($('#column-toggle-template').html()),

    events: {
      'change input[type="checkbox"]' : 'toggle'
    },

    initialize: function() {
      this.listenTo(this.model, 'change', this.render);
    },

    render: function() {
      this.$el.html(this.template(this.model.toJSON()));
      this.$('input[type="checkbox"]').uniform({
        radioClass: "choice"
      });

      return this;
    },

    toggle: function() {
      this.model.toggle();
    }
  });

  var ColumnList = Backbone.Collection.extend({
    model: Column,
    localStorage: new Backbone.LocalStorage('table-columns-' + uri.pathname())
  });

  var Product = Backbone.Model.extend({
    defaults: function() {
      return {
        checked: false
      }
    },

    toggleChecked: function() {
      this.set('checked', !this.get('checked'));
    }
  });

  var ProductView = Backbone.View.extend({
    tagName: 'tr',
    template: Handlebars.compile($('#product-template').html()),

    events: {
      'click .js-checkbox' : 'toggleChecked'
    },

    initialize: function() {
      this.listenTo(this.model, 'change', this.render);
    },

    render: function() {
      this.$el.html(this.template(this.model.toJSON()));
      this.$el.toggleClass('active', this.model.get('checked'));
      this.$('.js-checkbox').uniform({
        radioClass: "choice"
      });

      return this;
    },

    toggleChecked: function() {
      this.model.toggleChecked();
    }
  });

  var ProductList = Backbone.PageableCollection.extend({
    url: '{{ route('Ajax::Staff::Management::product2@index') }}',

    parseState: function (response, queryParams, state, options) {
      return {
        totalRecords: response.meta.pagination.count ? response.meta.pagination.total : null
      };
    },

    parseRecords: function (response, options) {
      return response.data;
    },

    model: Product,

    checked: function() {
      return this.where({checked: true});
    },

    unchecked: function() {
      return this.where({checked: false});
    },

    activated: function() {
      return this.where({
        isActivated: true
      });
    },

    activatedAndChecked: function() {
      return this.where({
        isActivated: true,
        checked: true
      });
    },

    deactivated: function() {
      return this.where({
        isDeactivated: true
      });
    },

    deactivatedAndChecked: function() {
      return this.where({
        isDeactivated: true,
        checked: true
      });
    },

    pendingActivation: function() {
      return this.where({
        isPendingActivation: true
      });
    },

    pendingActivationAndChecked: function() {
      return this.where({
        isPendingActivation: true,
        checked: true
      });
    }
  });

  var AppView = Backbone.View.extend({
    el: $('#main-content'),

    events: {
      'submit #query-filter': 'changeQueryFilter',
      'submit #gtin-filter': 'changeGtinFilter',
      'submit #gln-filter': 'changeGlnFilter',
      'submit #price-filter': 'changePriceFilter',
      'change #status-filter': 'changeStatusFilter',
      'change #category-filter': 'changeCategoryFilter',
      'change #image-filter': 'changeImageFilter',
      'change #vendor-filter': 'changeVendorFilter',
      'click #next-page-btn': 'loadNextPage',
      'click #prev-page-btn': 'loadPrevPage',
      'change .columns-toggle-item input[type="checkbox"]': 'toggleColumnList',
      'change #toggle-select-all': 'toggleSelectAll',
      'click .js-select-all': 'selectAll',
      'click .js-select-none': 'selectNone',
      'click .js-select-pending-activation': 'selectPendingActivation',
      'click .js-select-activated': 'selectActivated',
      'click .js-select-deactivated': 'selectDeactivated',
      'click [data-sortable="true"] > a': 'setSorting'
    },

    initialize: function() {
      var app = this,
          uriQueries = uri.search(true);

      // Batch buttons
      this.$btnBatchDelete = this.$("#btn-batch-delete");
      this.$btnBatchActivate = this.$("#btn-batch-activate");
      this.$btnBatchDeactivate = this.$("#btn-batch-deactivate");
      this.$btnBatchApprove = this.$("#btn-batch-approve");
      this.$btnBatchDisapprove = this.$("#btn-batch-disapprove");
      this.$btnExport = this.$("#btn-export");

      // Filters
      this.$queryFilter = this.$('#query-filter');
      this.$gtinFilter = this.$('#gtin-filter');
      this.$glnFilter = this.$('#gln-filter');
      this.$priceFilter = this.$('#price-filter');
      this.$statusFilter = this.$('#status-filter');
      this.$categoryFilter = this.$('#category-filter');
      this.$imageFilter = this.$('#image-filter');
      this.$vendorFilter = this.$('#vendor-filter');
      this.lastQueryFilterValue = this.$queryFilter.find('input').val();
      this.lastGlnFilterValue = this.$glnFilter.find('input').val();
      this.lastPriceFilterValue = this.$priceFilter.find('input').val();

      // Pagination
      this.$fromRecord = this.$("#from-record");
      this.$toRecord = this.$("#to-record");
      this.$totalRecords = this.$("#total-records");
      this.$btnNextPage = this.$("#next-page-btn");
      this.$btnPrevPage = this.$("#prev-page-btn");

      // Table
      this.$selectAll = this.$("#toggle-select-all");
      this.$tableHead = this.$('table > thead');
      this.$tableBody = this.$('table > tbody');
      this.$loadingBar = this.$('.loading-bar');
      this.columns = new ColumnList();
      this.columns.fetch();
      this.currentPage = _.has(uriQueries, 'page') ? (parseInt(uriQueries.page) || 1) : 1;
      this.sortBy = _.has(uriQueries, 'sort_by') ? uriQueries.sort_by : null;
      this.order = _.has(uriQueries, 'order') ? uriQueries.order : null;

      this.$('th').each(function (i, elm) {
        var $elm = $(elm),
            column;

        if ($elm.data('hideable') === true) {
          // Nếu cột này chưa có trong storage, thì thêm vào
          if (!(column = app.columns.get($elm.index()))) {
            column = new Column({
              title: $elm.text(),
              index: $elm.index()
            });
            app.columns.unshift(column);
            column.save();
          }
        }

        if ($elm.data('sortable') === true) {
          var content = $elm.html(),
              $a = $('<a href="#"></a>'),
              $direction = $('<span class="sort-direction"></span>');

          $elm.html($a.html(content));

          if (app.order == 'desc') {
            $a.addClass('desc');
          }

          $a.addClass('sortable').append($direction);

          if (app.sortBy == $elm.data('sort-by')) {
            $a.addClass('active');
          }
        }
      });

      this.columns.sortBy(function (column) {
        return column.get('index');
      }).reverse().forEach(function (column) {
        var view = new ColumnView({
          id: 'toggle-column-' + column.get('index'),
          model: column
        });

        this.$("#columns-toggle-header").after(view.render().el);
      });

      this.products = new ProductList([], {
        state: {
          pageSize: 20,
          sortKey: this.sortBy,
          order: this.order == 'asc' ? -1 : 1
        }
      });

      this.columns.on('change:show', this.toggleColumnList, this);

      this.products.on('request', this.showLoadingBar, this);
      this.products.on('sync', this.hideLoadingBar, this);
      this.products.on('sync', this.renderBatchButtons, this);
      this.products.on('sync', this.renderPagination, this);
      this.products.on('sync', this.renderSelectAllCheckbox, this);
      this.products.on('sync', this.renderTableBody, this);
      this.products.on('sync', this.toggleColumnList, this);
      this.products.on('change', this.renderBatchButtons, this);
      this.products.on('change', this.renderSelectAllCheckbox, this);

      this.refreshData();
    },

    renderBatchButtons: function () {
      var checked = this.products.checked().length,
          unchecked = this.products.unchecked().length,
          activatedAndChecked = this.products.activatedAndChecked().length,
          deactivatedAndChecked = this.products.deactivatedAndChecked().length,
          pendingActivationAndChecked = this.products.pendingActivationAndChecked().length;

      // Batch buttons
      this.$btnBatchDelete.prop('disabled', !checked);
      this.$btnBatchActivate.prop('disabled', !(deactivatedAndChecked && deactivatedAndChecked == checked));
      this.$btnBatchDeactivate.prop('disabled', !(activatedAndChecked && activatedAndChecked == checked));
      this.$btnBatchApprove.prop('disabled', !(pendingActivationAndChecked && pendingActivationAndChecked == checked));
      this.$btnBatchDisapprove.prop('disabled', !(pendingActivationAndChecked && pendingActivationAndChecked == checked));
    },

    renderPagination: function () {
      var hasNextPage = this.products.hasNextPage(),
          hasPreviousPage = this.products.hasPreviousPage(),
          state = this.products.state,
          fromRecord = (state.currentPage - 1) * state.pageSize + 1,
          toRecord = fromRecord + state.pageSize - 1,
          totalRecords = state.totalRecords || 0;

      if (totalRecords <= 0) {
        fromRecord = 0;
      }

      if (toRecord > totalRecords) {
        toRecord = totalRecords;
      }

      this.$fromRecord.text(fromRecord);
      this.$toRecord.text(toRecord);
      this.$totalRecords.text(totalRecords);

      this.$btnNextPage.prop('disabled', !hasNextPage);
      this.$btnPrevPage.prop('disabled', !hasPreviousPage);

      this.$('.dropdown-menu.dropdown-options').on('click', function(e) {
        e.stopPropagation();
      });
    },

    renderSelectAllCheckbox: function () {
      var checked = this.products.checked().length,
          unchecked = this.products.unchecked().length;

      this.$selectAll.prop('checked', !unchecked && checked);
      this.$selectAll.uniform({
        radioClass: "choice"
      });
    },

    renderTableBody: function() {
      this.$tableBody.empty();

      if (this.products.length) {
        this.products.each((product) => {
          this.addOne(product);
        });
      } else {
        this.$tableBody.append('<tr><td class="h1 text-center text-muted" colspan="' + this.$tableHead.find('tr > th').length + '"><i class="icon-info"></i> Không có dữ liệu tương ứng</td></tr>');
      }
    },

    getFilterQuery: function() {
      var query = {};

      if (this.$queryFilter.find('input').val()) {
        query['q'] = this.$queryFilter.find('input').val();
      }

      if (this.$gtinFilter.find('input').val()) {
        query['gtin'] = this.$gtinFilter.find('input').val();
      }

      if (this.$glnFilter.find('input').val()) {
        query['gln'] = this.$glnFilter.find('input').val();
      }

      if (this.$priceFilter.find('input').val()) {
        query['price'] = this.$priceFilter.find('input').val();
      }

      if (this.$statusFilter.val()) {
        query['status'] = this.$statusFilter.val();
      }

      if (this.$categoryFilter.val()) {
        query['category'] = this.$categoryFilter.val();
      }

      if (this.$imageFilter.val()) {
        query['image'] = this.$imageFilter.val();
      }

      if (this.$vendorFilter.val()) {
        query['vendor'] = this.$vendorFilter.val();
      }

      return query;
    },

    refreshData: function (page) {
      this.$btnExport.attr('href', this.products.url + '?' + uri.query() + '&export=1');

      this.products.getPage(page || this.currentPage, {
        data: this.getFilterQuery()
      });
    },

    loadNextPage: function (e) {
      e.preventDefault();

      var state = this.products.state;

      uri.setQuery({
        'page': state.currentPage + 1
      });
      history.replaceState(null, null, uri.toString());

      this.products.getNextPage({
        data: this.getFilterQuery()
      });
    },

    loadPrevPage: function (e) {
      e.preventDefault();

      var state = this.products.state;

      uri.setQuery({
        'page': state.currentPage - 1
      });
      history.replaceState(null, null, uri.toString());

      this.products.getPreviousPage({
        data: this.getFilterQuery()
      });
    },

    showLoadingBar: function () {
      this.$loadingBar
        .removeClass('is-completed')
        .addClass('is-loading');
    },

    hideLoadingBar: function () {
      this.$loadingBar
        .removeClass('is-loading')
        .addClass('is-completed');
    },

    addOne: function (product) {
      var view = new ProductView({
        id: 'product-' + product.get('id'),
        model: product
      });

      this.$tableBody.append(view.render().el);
    },

    toggleSelectAll: function () {
      var checked = this.$selectAll.prop('checked');

      this.products.each(function (product) {
        product.set('checked', checked);
      });
    },

    selectAll: function (e) {
      e.preventDefault();

      this.products.each(function (product) {
        product.set('checked', true);
      });
    },

    selectNone: function (e) {
      e.preventDefault();

      this.products.each(function (product) {
        product.set('checked', false);
      });
    },

    selectPendingActivation: function (e) {
      e.preventDefault();

      this.products.pendingActivation().forEach(function (product) {
        product.set('checked', true);
      });
    },

    selectActivated: function (e) {
      e.preventDefault();

      this.products.activated().forEach(function (product) {
        product.set('checked', true);
      });
    },

    selectDeactivated: function (e) {
      e.preventDefault();

      this.products.deactivated().forEach(function (product) {
        product.set('checked', true);
      });
    },

    toggleColumnList: function () {
      var that = this;

      this.columns.each(function (column) {
        var id = column.get('index') + 1;

        that.$tableHead.find('th:nth-child(' + id + ')').toggle(column.get('show'));
        that.$tableBody.find('td:nth-child(' + id + ')').toggle(column.get('show'));
      });
    },

    changeQueryFilter: function (e) {
      e.preventDefault();

      var currentQuery = $(e.target).find('input').val();

      if (currentQuery != this.lastQueryFilterValue) {
        this.lastQueryFilterValue = currentQuery;
        uri.setQuery({
          'q': currentQuery,
          'page': 1
        });
        history.replaceState(null, null, uri.toString());
        this.refreshData();
      }
    },

    changeGtinFilter: function (e) {
      e.preventDefault();

      var currentGln = $(e.target).find('input').val();

      if (currentGln != this.lastGlnFilterValue) {
        this.lastGlnFilterValue = currentGln;
        uri.setQuery({
          'gtin': currentGln,
          'page': 1
        });
        history.replaceState(null, null, uri.toString());
        this.refreshData();
      }
    },

    changeGlnFilter: function (e) {
      e.preventDefault();

      var currentGln = $(e.target).find('input').val();

      if (currentGln != this.lastGlnFilterValue) {
        this.lastGlnFilterValue = currentGln;
        uri.setQuery({
          'gln': currentGln,
          'page': 1
        });
        history.replaceState(null, null, uri.toString());
        this.refreshData();
      }
    },

    changePriceFilter: function (e) {
      e.preventDefault();

      var currentPrice = $(e.target).find('input').val();

      if (currentPrice != this.lastPriceFilterValue) {
        this.lastPriceFilterValue = currentPrice;
        uri.setQuery({
          'price': currentPrice,
          'page': 1
        });
        history.replaceState(null, null, uri.toString());
        this.refreshData();
      }
    },

    changeStatusFilter: function (e) {
      // Update url
      uri.setQuery({
        'status': $(e.target).val(),
        'page': 1
      });
      history.replaceState(null, null, uri.toString());

      this.refreshData();
    },

    changeCategoryFilter: function (e) {
      // Update url
      uri.setQuery({
        'category': $(e.target).val(),
        'page': 1
      });
      history.replaceState(null, null, uri.toString());

      this.refreshData();
    },

    changeImageFilter: function (e) {
      // Update url
      uri.setQuery({
        'image': $(e.target).val(),
        'page': 1
      });
      history.replaceState(null, null, uri.toString());

      this.refreshData();
    },

    changeVendorFilter: function (e) {
      // Update url
      uri.setQuery({
        'vendor': $(e.target).val(),
        'page': 1
      });
      history.replaceState(null, null, uri.toString());

      this.refreshData();
    },

    setSorting: function (e) {
      e.preventDefault();

      var $a = $(e.target);

      // Remove all sortable has active class and add active class to current
      $a.parent().siblings().children('a').removeClass('active');
      $a.addClass('active').toggleClass('desc');

      // Update url
      uri.setQuery({
        'sort_by': $a.parent().data('sort-by'),
        'order': $a.hasClass('desc') ? 'desc' : 'asc'
      });
      history.replaceState(null, null, uri.toString());

      // Set sorting and refresh data
      this.products.setSorting($a.parent().data('sort-by'), $a.hasClass('desc') ? 1 : -1);
      this.refreshData();
    }
  });

  var App = new AppView;

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
            item =  (prefix ? prefix + '| ' : '') + item.text;

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
    var old= $this.val();

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
        if(newVal==''){
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
        type:'POST',
        url: '{{ route('Ajax::Staff::upload@image') }}',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        data:formData,
        cache:false,
        contentType: false,
        processData: false,
        success:function(data){


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
        error: function(data){
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

            if(images.length == 0){
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

