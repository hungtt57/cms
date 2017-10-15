@extends('_layouts/staff')

@section('page_title', 'Tìm kiếm sản phẩm mới')

@section('content')

  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>
          Nhập danh sách Gtin cần tìm
        </h2>
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
        <div class="panel panel-flat">
          <div class="panel-body">
            <form id = "cpa-form" method="GET" action="{{ route('Staff::operation@barcode') }}">
              <div class="form-group {{ $errors->has('gtin') ? 'has-error has-feedback' : '' }}">
                <label for="contact-info" class="control-label text-semibold">Nhập Barcode cần tìm</label>
                <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Barcode cần tìm"></i>
                <textarea id="barcode-input" name="gtin" rows="5" cols="5" class="form-control" placeholder="">{{ old('gtin') }}</textarea>
                @if ($errors->has('gtin'))
                  <div class="form-control-feedback">
                    <i class="icon-notification2"></i>
                  </div>
                  <div class="help-block">{{ $errors->first('gtin') }}</div>
                @endif
              </div>
                <p class="error-find-data" style="color: red;font-size: 15px;display: none">Không tìm thấy dữ liệu phù hợp</p>
              <div class="text-right">
                <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- /main content -->
    </div>
    <!-- /page content -->
  </div>
  <!-- /page container -->
@endsection

