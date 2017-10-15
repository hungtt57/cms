@extends('_layouts/auth')

@section('page_title', 'Đăng ký')

@section('content')
  <!-- Main navbar -->
  <div class="navbar navbar-inverse bg-indigo">
    <div class="navbar-header">
      <!--<a class="navbar-brand" href="index.html"><img src="assets/images/logo_light.png" alt=""></a>-->
      <a class="navbar-brand" href="{{ route('Business::dashboard') }}"><strong>iCheck</strong> for business</a>

      <ul class="nav navbar-nav pull-right visible-xs-block">
        <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
      </ul>
    </div>

    <div class="navbar-collapse collapse" id="navbar-mobile">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="/login">Đăng nhập</a></li>
      </ul>
    </div>
  </div>
  <!-- /main navbar -->
  <!-- Page container -->
  <div class="page-container">
    <!-- Page content -->
    <div class="page-content">
      <!-- Main content -->
      <div class="content-wrapper">
        <div class="container">
          <div class="text-center">
            <h1 class="content-group">Đăng ký doanh nghiệp</h1>
          </div>

          <div class="row">
            <div class="col-md-7"></div>
            <div class="col-md-5">
              <!-- Simple login form -->
              <form role="form" method="POST" action="{{ url('/register') }}">
                {{ csrf_field() }}
                <div class="panel panel-body border-top-xlg border-top-indigo">
                  <div class="mb-20 text-muted"><span class="text-danger">*</span> là những thông tin bắt buộc. <a target="_blank" href="#">Tìm hiểu thêm</a> về lý do chúng tôi yêu cầu thông tin này.</div>

                  <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
                    <label for="name" class="control-label text-semibold">Tên doanh nghiệp <span class="text-danger">*</span></label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Quý doanh nghiệp"></i>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" />
                    @if ($errors->has('name'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('name') }}</div>
                    @endif
                  </div>

                  <div class="form-group {{ $errors->has('gln') ? 'has-error has-feedback' : '' }}">
                    <label for="gln" class="control-label text-semibold">Mã địa điểm toàn cầu (GLN) <span class="text-danger">*</span></label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Nhập Mã địa điểm toàn cầu (GLN) của Quý doanh nghiệp. Nếu Quý doanh nghiệp có nhiều GLN, Quý danh nghiệp có thể thêm sau khi đăng ký."></i>
                    <input type="text" id="gln" name="gln" class="form-control" value="{{ old('gln') }}" />
                    @if ($errors->has('gln'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('gln') }}</div>
                    @endif
                  </div>

                  <div class="form-group {{ $errors->has('country_id') ? 'has-error has-feedback' : '' }}">
                    <label for="country" class="control-label text-semibold">Quốc gia <span class="text-danger">*</span></label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Quốc gia của Quý doanh nghiêpj"></i>
                    <select id="country" name="country_id" class="js-select">
                      <option></option>
                      @foreach ($countries as $country)
                      <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? ' selected="selected"' : '' }}>{{ $country->name }}</option>
                      @endforeach
                    </select>
                    @if ($errors->has('country_id'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('country_id') }}</div>
                    @endif
                  </div>

                  <div class="form-group {{ $errors->has('address') ? 'has-error has-feedback' : '' }}">
                    <label for="address" class="control-label text-semibold">Địa chỉ <span class="text-danger">*</span></label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Địa chỉ của Quý doanh nghiệp"></i>
                    <textarea id="address" name="address" rows="3" class="form-control">{{ old('address') }}</textarea>
                    @if ($errors->has('address'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('address') }}</div>
                    @endif
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group {{ $errors->has('email') ? 'has-error has-feedback' : '' }}">
                        <label for="email" class="control-label text-semibold">Email <span class="text-danger">*</span></label>
                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Email của Quý doanh nghiệp"></i>
                        <input type="text" id="email" name="email" class="form-control" value="{{ old('email') }}" />
                        @if ($errors->has('email'))
                          <div class="form-control-feedback">
                            <i class="icon-notification2"></i>
                          </div>
                          <div class="help-block">{{ $errors->first('email') }}</div>
                        @endif
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group {{ $errors->has('phone_number') ? 'has-error has-feedback' : '' }}">
                        <label for="phone_number" class="control-label text-semibold">Số điện thoại <span class="text-danger">*</span></label>
                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Số điện thoại của Quý doanh nghiệp"></i>
                        <input type="text" id="phone_number" name="phone_number" class="form-control" value="{{ old('phone_number') }}" />
                        @if ($errors->has('phone_number'))
                          <div class="form-control-feedback">
                            <i class="icon-notification2"></i>
                          </div>
                          <div class="help-block">{{ $errors->first('phone_number') }}</div>
                        @endif
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group {{ $errors->has('fax') ? 'has-error has-feedback' : '' }}">
                        <label for="fax" class="control-label text-semibold">Fax</label>
                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Số fax của Quý doanh nghiệp"></i>
                        <input type="text" id="fax" name="fax" class="form-control" value="{{ old('fax') }}" />
                        @if ($errors->has('fax'))
                          <div class="form-control-feedback">
                            <i class="icon-notification2"></i>
                          </div>
                          <div class="help-block">{{ $errors->first('fax') }}</div>
                        @endif
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group {{ $errors->has('website') ? 'has-error has-feedback' : '' }}">
                        <label for="website" class="control-label text-semibold">Trang web</label>
                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Địa chỉ trang web của Quý doanh nghiệp"></i>
                        <input type="text" id="website" name="website" class="form-control" value="{{ old('website') }}" />
                        @if ($errors->has('website'))
                          <div class="form-control-feedback">
                            <i class="icon-notification2"></i>
                          </div>
                          <div class="help-block">{{ $errors->first('website') }}</div>
                        @endif
                      </div>
                    </div>
                  </div>

                  <div class="form-group{{ $errors->has('tos') ? ' has-error' : '' }}">
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" name="tos" id="tos" class="js-checkbox">
                        Đồng ý với các <a href="#">Điều khoản dịch vụ</a> và <a href="#">Chính sách bảo mật</a> của <strong class="text-indigo">iCheck cho Doanh nghiệp</strong> <span class="text-danger">*</span>
                      </label>
                    </div>
                    @if ($errors->has('tos'))
                      <div class="help-block">{{ $errors->first('tos') }}</div>
                    @endif
                  </div>

                  <div class="text-right">
                    <button type="submit" class="btn bg-indigo">Đăng ký</button>
                  </div>
                </div>
              </form>
              <!-- /simple login form -->
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
    $(".js-select").select2({
      placeholder: "Lựa chọn quốc gia",
      allowClear: false
    });

    $(".js-help-icon").popover({
      container: "body",
      html: true,
      trigger: "hover",
      delay: { "hide": 1000 }
    });


    //
    // Select with icons
    //

    // Format icon
    function iconFormat(icon) {
        var originalOption = icon.element;
        if (!icon.id) { return icon.text; }
        var $icon = "<i class='icon-" + $(icon.element).data('icon') + "'></i>" + icon.text;

        return $icon;
    }

    // Initialize with options
    $(".select-icons").select2({
        templateResult: iconFormat,
        minimumResultsForSearch: Infinity,
        templateSelection: iconFormat,
        escapeMarkup: function(m) { return m; }
    });



    // Styled form components
    // ------------------------------

    // Checkboxes, radios
    $(".js-checkbox").uniform({ radioClass: "choice" });

    // File input
    $(".js-file").uniform({
        fileButtonClass: "action btn btn-default"
    });

  });
  </script>
@endpush
