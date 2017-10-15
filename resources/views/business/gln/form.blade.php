@extends('_layouts/default')

@section('content')
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>
          <a href="{{ route('Business::gln@index') }}" class="btn btn-link">
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
                <form method="POST" enctype="multipart/form-data" action="{{ isset($gln) ? route('Business::gln@update', [$gln->id]) : route('Business::gln@store') }}">
                  {{ csrf_field() }}
                  @if (isset($gln))
                  <input type="hidden" name="_method" value="PUT">

                  @endif
                  @if (!isset($gln))
                  <div class="form-group {{ $errors->has('gln') ? 'has-error has-feedback' : '' }}">
                    <label for="gln" class="control-label text-semibold">Mã GLN</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Mã GLN"></i>
                    <input type="text" id="gln" name="gln" class="form-control" value="{{ old('gln') ?: @$gln->gln }}" />
                    @if ($errors->has('gln'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('gln') }}</div>
                    @endif
                  </div>
                    <div class="form-group {{ $errors->has('prefix') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Prefix</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Prefix của nhà sản xuất"></i>
                      <input type="text" id="prefix" name="prefix" class="form-control" value="{{ old('prefix') ?: @$gln->prefix }}" />
                      @if ($errors->has('prefix'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('prefix') }}</div>
                      @endif
                    </div>
                  @endif

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
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Địa chỉ tương ứng với mã GLN"></i>
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

                  <div class="form-group {{ $errors->has('fax') ? 'has-error has-feedback' : '' }}">
                    <label for="fax" class="control-label text-semibold">Fax</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Fax"></i>
                    <input type="text" id="fax" name="fax" class="form-control" value="{{ old('fax') ?: @$gln->fax }}" />
                    @if ($errors->has('fax'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('fax') }}</div>
                    @endif
                  </div>

                  <div class="form-group {{ $errors->has('website') ? 'has-error has-feedback' : '' }}">
                    <label for="website" class="control-label text-semibold">Website</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Website của Doanh nghiệp"></i>
                    <input type="text" id="website" name="website" class="form-control" placeholder="http://" value="{{ old('website') ?: @$gln->website }}" />
                    @if ($errors->has('website'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('website') }}</div>
                    @endif
                  </div>

                  <div class="form-group {{ $errors->has('contact_info') ? 'has-error has-feedback' : '' }}">
                    <label for="contact-info" class="control-label text-semibold">Thông tin liên hệ</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Thông tin để liên hệ với Doanh nghiệp. VD: Email, SĐT"></i>
                    <textarea id="contact-info" name="contact_info" rows="5" cols="5" class="form-control">{{ old('contact_info') ?: @$gln->contact_info }}</textarea>
                    @if ($errors->has('contact_info'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('contact_info') }}</div>
                    @endif
                  </div>

                  <div class="form-group {{ $errors->has('additional_info') ? 'has-error has-feedback' : '' }}">
                    <label for="contact-info" class="control-label text-semibold">Lý do</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do yêu cầu thêm mã"></i>

                    <div class="radio"><label><input type="radio" name="additional_info" value="Doanh nghiệp thêm mã sản phẩm mới"> Doanh nghiệp thêm mã sản phẩm mới (Đối với Nhà sản xuất)</label></div>
                    <div class="radio"><label><input type="radio" name="additional_info" value="Doanh nghiệp phân phối thêm sản phẩm mới"> Doanh nghiệp phân phối thêm sản phẩm mới (Đối với Nhà nhập khẩu)</label></div>
                    <div class="radio"><label><input type="radio" id="other" name="additional_info" value="other"> Khác</label></div>
                    <textarea id="contact-info" name="additional_info_other" rows="5" cols="5" class="form-control">{{ old('additional_info_other') ?: @$gln->additional_info_other }}</textarea>

                    <!--<textarea id="contact-info" name="additional_info" rows="5" cols="5" class="form-control">{{ old('additional_info') ?: @$gln->additional_info }}</textarea>-->
                    @if ($errors->has('additional_info'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('additional_info') }}</div>
                    @endif
                  </div>


                  @if (!isset($gln))
                  <div class="form-group {{ $errors->has('certificate_file') ? 'has-error has-feedback' : '' }}">
                    <label for="contact-info" class="control-label text-semibold">Giấy chứng nhận chủ sở hữu GLN</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Giấy chứng nhận Quý Doanh nghiệp là chủ sở hữu của mã GLN này"></i>
                    <input type="file" name="certificate_file" class="js-file">
                    <input type="file" name="certificate_file2" class="js-file">
                    <input type="file" name="certificate_file3" class="js-file">
                    <input type="file" name="certificate_file4" class="js-file">
                    <input type="file" name="certificate_file5" class="js-file">
                    @if ($errors->has('certificate_file'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('certificate_file') }}</div>
                    @endif
                  </div>
                  @endif

                  <div class="text-right">
                    <button type="submit" class="btn btn-primary">{{ isset($gln) ? 'Cập nhật' : 'Thêm mới' }}</button>
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

    @if (!isset($gln))

    $('#gln').on('focusout', function () {
      var gln = $(this).val();

      $.ajax({
        url: '{{ route('Business::gln@suggestInfo', ['']) }}/' + gln,
        success: function (response) {
          if (response.hasOwnProperty('name')) {
            $('#name').val(response.name);
            $('#address').val(response.address);
            $('#phone-number').val(response.phone);
            $('#country').val(response.cid).trigger("change");
          }
        }
      });
    });

    @endif
  });
  </script>
@endpush
