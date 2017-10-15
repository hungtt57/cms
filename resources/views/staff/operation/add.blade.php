@extends('_layouts/staff')

@section('page_title', 'Thêm sản phẩm vào danh sách Yêu cầu sửa')

@section('content')

  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>
          Thêm sản phẩm vào danh sách Yêu cầu sửa
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
            <form method="POST" action="{{ route('Staff::operation@add') }}">
              {{ csrf_field() }}
              <div class="form-group {{ $errors->has('gtin') ? 'has-error has-feedback' : '' }}">
                <label for="contact-info" class="control-label text-semibold">Thêm theo GTIN</label>
                <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Nhập GTIN cần đồng bộ dữ liệu"></i>
                <textarea id="contact-info" name="gtin" rows="5" cols="5" class="form-control" placeholder="">{{ old('gtin') }}</textarea>
                @if ($errors->has('gtin'))
                  <div class="form-control-feedback">
                    <i class="icon-notification2"></i>
                  </div>
                  <div class="help-block">{{ $errors->first('gtin') }}</div>
                @endif
              </div>
              
              <div class="text-right">
                <button type="submit" class="btn btn-primary">Thêm mới</button>
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

@push('scripts_foot')
  <script>
  $(document).ready(function () {

  $(document).on('submit', 'form', function () {
    $('button[type="submit"]').prop('disabled', true);
  });

  });
  </script>
@endpush


