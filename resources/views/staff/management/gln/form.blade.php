@extends('_layouts/staff')

@section('content')
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>
          <a href="{{ route('Staff::Management::gln@index') }}" class="btn btn-link">
            <i class="icon-arrow-left8"></i>
          </a>
          {{ isset($gln) ? 'Sửa GLN ' . $gln->gln : 'Thêm Mã địa điểm toàn cầu' }}
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
        <div class="row">
          <div class="col-md-offset-4 col-md-4">
            @if (session('success'))
              <div class="alert bg-success alert-styled-left">
                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                {{ session('success') }}
              </div>
            @endif
            <div class="panel panel-flat">
              <div class="panel-body">
                <form method="POST" enctype="multipart/form-data" action="{{ isset($gln) ? route('Staff::Management::gln@update', [$gln->id]) : route('Staff::Management::gln@store') }}">
                  {{ csrf_field() }}
                  @if (isset($gln))
                  <input type="hidden" name="_method" value="PUT">
                  @endif

                  <div class="form-group {{ $errors->has('business_id') ? 'has-error has-feedback' : '' }}">
                    <label for="business" class="control-label text-semibold">Doanh nghiệp</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Quốc gia"></i>
                    <select id="business" name="business_id" class="js-select">
                      @foreach ($businesses as $business)
                      <option value="{{ $business->id }}" {{ ((old('business_id') and old('business_id') == $business->id) or (isset($gln) and $gln->business_id == $business->id)) ? ' selected="selected"' : '' }}>{{ $business->name }}</option>
                      @endforeach
                    </select>
                    @if ($errors->has('business_id'))
                      <div class="help-block">{{ $errors->first('business_id') }}</div>
                    @endif
                  </div>

                  <div class="form-group {{ $errors->has('gln') ? 'has-error has-feedback' : '' }}">
                    <label for="gln" class="control-label text-semibold">Mã GLN</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của nhà sản xuất"></i>
                    <input type="text" id="gln" name="gln" class="form-control" value="{{ old('gln') ?: @$gln->gln }}" />
                    @if ($errors->has('gln'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('gln') }}</div>
                    @endif
                  </div>

                  <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
                    <label for="name" class="control-label text-semibold">Tên</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của nhà sản xuất"></i>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') ?: @$gln->name }}" />
                    @if ($errors->has('name'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('name') }}</div>
                    @endif
                  </div>

                  <div class="form-group {{ $errors->has('country_id') ? 'has-error has-feedback' : '' }}">
                    <label for="country" class="control-label text-semibold">Quốc gia</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Quốc gia"></i>
                    <select id="country" name="country_id" class="js-select">
                      @foreach ($countries as $country)
                      <option value="{{ $country->id }}" {{ ((old('country_id') and old('country_id') == $country->id) or (isset($gln) and $gln->country_id == $country->id)) ? ' selected="selected"' : '' }}>{{ $country->name }}</option>
                      @endforeach
                    </select>
                    @if ($errors->has('country_id'))
                      <div class="help-block">{{ $errors->first('country_id') }}</div>
                    @endif
                  </div>

                  <div class="form-group {{ $errors->has('address') ? 'has-error has-feedback' : '' }}">
                    <label for="address" class="control-label text-semibold">Địa chỉ</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Địa chỉ của Doanh nghiệp"></i>
                    <input type="text" id="address" name="address" class="form-control" value="{{ old('address') ?: @$gln->address }}" />
                    @if ($errors->has('address'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('address') }}</div>
                    @endif
                  </div>

                  <div class="form-group {{ $errors->has('email') ? 'has-error has-feedback' : '' }}">
                    <label for="email" class="control-label text-semibold">Email</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Email"></i>
                    <input type="text" id="email" name="email" class="form-control" value="{{ old('email') ?: @$gln->email }}" />
                    @if ($errors->has('email'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('email') }}</div>
                    @endif
                  </div>

                  <div class="form-group {{ $errors->has('phone_number') ? 'has-error has-feedback' : '' }}">
                    <label for="phone-number" class="control-label text-semibold">Số điện thoại</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Số điện thoại"></i>
                    <input type="text" id="phone-number" name="phone_number" class="form-control" value="{{ old('phone_number') ?: @$gln->phone_number }}" />
                    @if ($errors->has('phone_number'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('phone_number') }}</div>
                    @endif
                  </div>

                  <div class="form-group {{ $errors->has('contact_info') ? 'has-error has-feedback' : '' }}">
                    <label for="contact-info" class="control-label text-semibold">Thông tin liên hệ</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Thông tin để liên hệ với Doanh nghiệp. VD: Email, SĐT"></i>
                    <textarea id="contact-info" name="contact_info" rows="5" cols="5" class="form-control" placeholder="- Email:">{{ old('contact_info') ?: @$gln->contact_info }}</textarea>
                    @if ($errors->has('contact_info'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('contact_info') }}</div>
                    @endif
                  </div>

                  <div class="form-group {{ $errors->has('prefix') ? 'has-error has-feedback' : '' }}">
                    <label for="gln" class="control-label text-semibold">Mã Prefix</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Prefix của nhà sản xuất"></i>
                    <input type="text" id="prefix" name="prefix" class="form-control" value="{{ old('prefix') ?: @$gln->prefix }}" />
                    @if ($errors->has('prefix'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('prefix') }}</div>
                    @endif
                  </div>


                  <div class="text-right">
                    <button type="submit" class="btn btn-primary">{{ isset($gln) ? 'Cập nhật' : 'Thêm mới' }}</button>
                    @if (isset($gln))
                    <a href="#" data-toggle="modal" data-target="#approve-modal" data-gln="{{ $gln->gln }}" data-approve-url="{{ route('Staff::Management::gln@approve', [$gln->id]) }}"><i class="icon-checkmark-circle2"></i> Chấp nhận</a>
                    @endif
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /main content -->
    </div>
    <!-- /page content -->
  </div>
  <!-- /page container -->

<div class="modal fade" id="approve-modal" tabindex="-1" role="dialog" aria-labelledby="approve-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="approve-modal-label">Chấp nhận GLN</h4>
      </div>
      <form method="POST">
        <div class="modal-body">
          <div class="form-group">
            Bạn có chắc chắn chấp nhận GLN <strong class="text-danger js-product-name"></strong> lên hệ thống của iCheck?
          </div>
          <div class="form-group">
            <label for="reason" class="control-label text-semibold">Lý do</label>
            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do bạn chấp nhận đăng tải sản phẩm này lên hệ thống cảu iCheck"></i>
            <textarea id="reason" name="reason" rows="5" cols="5" class="form-control" placeholder="Lý do"></textarea>
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
@endsection

@push('js_files_foot')
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
@endpush

@push('scripts_foot')
  <script>
  $(document).ready(function () {
    // Basic
    $(".js-select").select2();

    $(".js-help-icon").popover({
      html: true,
      trigger: "hover",
      delay: { "hide": 1000 }
    });

  $('#approve-modal').on('show.bs.modal', function (event) {
    var $btn = $(event.relatedTarget),
        $modal = $(this);

    $modal.find('form').attr('action', $btn.data('approve-url'));
    $modal.find('.js-product-name').text($btn.data('name'));
  });
  });
  </script>
@endpush
