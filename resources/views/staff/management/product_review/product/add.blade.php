@extends('_layouts/staff')

@section('page_title', 'Thêm sản phẩm vào danh sách Yêu cầu đánh giá')

@section('content')

  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>
          Thêm sản phẩm vào danh sách Yêu cầu đánh giá
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
            <form method="POST" action="{{ route('Staff::Management::productReview@product@store') }}">
              {{ csrf_field() }}
              <div class="form-group {{ $errors->has('gtin') ? 'has-error has-feedback' : '' }}">
                <label for="contact-info" class="control-label text-semibold">Thêm theo GTIN</label>
                <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Thông tin để liên hệ với Doanh nghiệp. VD: Email, SĐT"></i>
                <textarea id="contact-info" name="gtin" rows="5" cols="5" class="form-control" placeholder="">{{ old('gtin') }}</textarea>
                @if ($errors->has('gtin'))
                  <div class="form-control-feedback">
                    <i class="icon-notification2"></i>
                  </div>
                  <div class="help-block">{{ $errors->first('gtin') }}</div>
                @endif
              </div>
              <div class="form-group {{ $errors->has('gln') ? 'has-error has-feedback' : '' }}">
                <label for="contact-info" class="control-label text-semibold">Thêm theo GLN</label>
                <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Thông tin để liên hệ với Doanh nghiệp. VD: Email, SĐT"></i>
                <textarea id="contact-info" name="gln" rows="5" cols="5" class="form-control" placeholder="">{{ old('gln') }}</textarea>
                @if ($errors->has('gln'))
                  <div class="form-control-feedback">
                    <i class="icon-notification2"></i>
                  </div>
                  <div class="help-block">{{ $errors->first('gln') }}</div>
                @endif
              </div>
              <div class="form-group {{ $errors->has('max_review') ? 'has-error has-feedback' : '' }}">
                <label for="max-review" class="control-label text-semibold">Số đánh giá tối đa</label>
                <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của sản phẩm"></i>
                <input type="text" id="max-review" name="max_review" class="form-control" value="{{ @$product->max_review }}" />
                @if ($errors->has('name'))
                  <div class="form-control-feedback">
                    <i class="icon-notification2"></i>
                  </div>
                  <div class="help-block">{{ $errors->first('max_review') }}</div>
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


