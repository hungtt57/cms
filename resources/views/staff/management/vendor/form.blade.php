@extends('_layouts/staff')

@section('content')
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>
          <a href="" class="btn btn-link">
            <i class="icon-arrow-left8"></i>
          </a>
          {{ isset($vendor) ? 'Sửa  ' : 'Thêm' }}
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
          <div class="col-md-offset-2 col-md-8">
            @if (session('success'))
              <div class="alert bg-success alert-styled-left">
                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                {{ session('success') }}
              </div>
            @endif
            <div class="panel panel-flat">
              <div class="panel-body">
                <form method="POST" enctype="multipart/form-data" action="{{ isset($vendor) ? route('Staff::Management::vendor@update', [$vendor->id] ): route('Staff::Management::vendor@store') }}">
                  {{ csrf_field() }}
                  @if (isset($vendor))
                    <input type="hidden" name="_method" value="PUT">
                  @endif
                  <!---------- GLN------------>
                  <div class="form-group {{ $errors->has('gln_code') ? 'has-error has-feedback' : '' }}">
                    <label for="name" class="control-label text-semibold">Gln_code</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Mã GLN"></i>
                    <input type="text" id="gln_code" name="gln_code" class="form-control" value="{{ old('gln_code') ?: @$vendor->gln_code }}" />
                    @if ($errors->has('gln_code'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('gln_code') }}</div>
                    @endif
                  </div>
                  <!------------------ Internal--------------->
                    <div class="form-group {{ $errors->has('internal_code') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Internal_code</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Mã Internal"></i>
                      <input type="text" id="internal_code" name="internal_code" class="form-control" value="{{ old('internal_code') ?: @$vendor->internal_code }}" />
                      @if ($errors->has('internal_code'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('internal_code') }}</div>
                      @endif
                    </div>
                    <!---------- Name------------>
                    <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Name</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                      <input type="text" id="name" name="name" class="form-control" value="{{ old('name') ?: @$vendor->name }}" />
                      @if ($errors->has('name'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('name') }}</div>
                      @endif
                    </div>
                    <!---------- Adress------------>
                    <div class="form-group {{ $errors->has('address') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Address</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Địa Chỉ"></i>
                      <input type="text" id="address" name="address" class="form-control" value="{{ old('address') ?: @$vendor->address }}" />
                      @if ($errors->has('address'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('address') }}</div>
                      @endif
                    </div>
                    <!---------- Phone------------>
                    <div class="form-group {{ $errors->has('phone') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Phone</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Số Điện thoại"></i>
                      <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') ?: @$vendor->phone }}" />
                      @if ($errors->has('phone'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('phone') }}</div>
                      @endif
                    </div>
                    <!---------- Email------------>
                    <div class="form-group {{ $errors->has('email') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Email</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Email"></i>
                      <input type="text" id="email" name="email" class="form-control" value="{{ old('email') ?: @$vendor->email }}" />
                      @if ($errors->has('email'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('email') }}</div>
                      @endif
                    </div>
                    <!---------- Website------------>
                    <div class="form-group {{ $errors->has('website') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Website</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Địa chỉ website"></i>
                      <input type="text" id="website" name="website" class="form-control" value="{{ old('website') ?: @$vendor->website }}" />
                      @if ($errors->has('website'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('website') }}</div>
                      @endif
                    </div>

                  <!------------- Country--------------->

                    <div class="form-group {{ $errors->has('country') ? 'has-error has-feedback' : '' }}">
                      <label for="country" class="control-label text-semibold">Quốc gia</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Quốc gia"></i>
                      <select id="country" name="country" class="js-select">
                        @foreach ($countries as $country)
                          <option value="{{ $country->id }}" {{ ((old('country') and old('country') == $country->id) or (isset($vendor) and $vendor->country_id == $country->id)) ? ' selected="selected"' : '' }}>{{ $country->name }}</option>
                        @endforeach
                      </select>
                      @if ($errors->has('country'))
                        <div class="help-block">{{ $errors->first('country') }}</div>
                      @endif
                    </div>

                    <!-------------Other--------------->

                    <div class="form-group {{ $errors->has('other') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Other</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Khác"></i>
                      <input type="text" id="other" name="other" class="form-control" value="{{ old('other') ?: @$vendor->other }}" />
                      @if ($errors->has('other'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('other') }}</div>
                      @endif
                    </div>

                    <!-------------verification--------------->

                    <div class="panel panel-flat">
                      <div class="table-responsive">
                        <table class="table table-hover">
                          <thead>
                          <tr>
                            <th>Verification</th>
                            <th>Yes</th>
                            <th>No</th>
                          </tr>
                          </thead>
                          <tbody>
                          <tr role="row" id="">
                            <td></td>
                            <td>
                              <div class="radio">
                                <label class="radio-inline">
                                  <input type="radio" name="verify_by"  class="js-radio" value="1" {{ (isset($vendor->verify_by) and $vendor->verify_by == '1') ? ' checked="checked"' : ''  }}>
                                </label>
                              </div>
                            </td>

                            <td>
                              <div class="radio">
                                <label class="radio-inline">
                                  <input type="radio" name="verify_by"   class="js-radio" value="0" {{ (isset($vendor->verify_by) and $vendor->verify_by == '0') ? ' checked="checked"' : ''  }}>
                                </label>
                              </div>
                            </td>

                          </tr>

                          </tbody>
                        </table>


                      </div>

                    </div>

                    <!-------------Prefix--------------->

                    <div class="form-group {{ $errors->has('prefix') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Prefix</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                      <input type="text" id="prefix" name="prefix" class="form-control" value="{{ old('prefix') ?: @$vendor->prefix }}" />
                      @if ($errors->has('prefix'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('prefix') }}</div>
                      @endif
                    </div>

                    <div class="form-group">
                      <label for="message" class="control-label text-semibold">Cảnh báo</label>
                      <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên Nhà sản xuất hoặc Nhà phân phối sản phẩm"></i>
                      <select id="message" name="warning_id" class="js-select">
                        <option value="">Không có</option>
                        @foreach ($messages as $message)
                        <option value="{{ $message->id }}" {{ (isset($warning) and $warning and @$warning->message_id == $message->id) ? ' selected="selected"' : '' }}>{{ $message->short_msg }}</option>
                        @endforeach
                      </select>
                    </div>

                    @if (isset($distributors))
                    <div class="form-group">
                      <label for="vendor" class="control-label text-semibold">Nhà phân phối</label>
                      <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên Nhà sản xuất hoặc Nhà phân phối sản phẩm"></i>
                      <div class="multi-select-full">
                        <select id="d-multiselect" name="distributors_selected[]" class="multiselect" multiple="multiple">
                          @foreach ($distributors as $distributor)
                          <option value="{{ $distributor->id }}" {{ isset($distributorsData[$distributor->id]) ? ' selected="selected"' : '' }} >{{ $distributor->name }}</option>
                          @endforeach
                        </select>
                      </div>
                      <table class="table">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Tên</th>
                          <th>Quốc gia</th>
                          <th>Độc quyền?</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach ($distributors as $distributor)
                      <tr id="d-{{ $distributor->id }}" class="hidden">
                        <td><input type="checkbox" name="distributors[{{ $distributor->id }}][enabled]" {{ isset($distributorsData[$distributor->id]) ? ' checked="checked"' : '' }} value="1" /></td>
                        <td>{{ $distributor->name }}</td>
                        <td>{{ @$distributor->country->name }}</td>
                        <td><input type="checkbox" name="distributors[{{ $distributor->id }}][is_monopoly]" {{ (isset($distributorsData[$distributor->id]) and $distributorsData[$distributor->id]['is_monopoly'] == 1) ? ' checked="checked"' : '' }} value="1" /></td>
                      </tr>
                      @endforeach
                      </tbody>
                      </table>
                    </div>
                    @endif


                    <div class="text-right">
                      <button type="submit" class="btn btn-primary">{{ isset($vendor) ? 'Cập nhật' : 'Thêm mới' }}</button>
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
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
@endpush

@push('scripts_foot')
<script>
  $(document).ready(function () {
    // Basic
    $(".js-select").select2();

    $('#a-multiselect').multiselect({
      enableCaseInsensitiveFiltering: true,
      enableFiltering: true,
        onChange: function(a, b) {
          var id = '#a-' + $(a).val();

          $(id).toggleClass('hidden', !b);
            $.uniform.update();
        }
    });

    $('#d-multiselect').multiselect({
      enableCaseInsensitiveFiltering: true,
      enableFiltering: true,
        onChange: function(d, b) {
          var id = '#d-' + $(d).val();

          $(id).toggleClass('hidden', !b);
            $.uniform.update();
        }
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
    $(".js-radio, .js-checkbox").uniform({ radioClass: "choice" });

    // File input
    $(".js-file").uniform({
      fileButtonClass: "action btn btn-default"
    });

    $(".js-tooltip, .js-help-icon").popover({
      container: "body",
      html: true,
      trigger: "hover",
      delay: { "hide": 1000 }
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

    @if ($errors->has('password'))
    $('a#show-password-inputs').trigger('click');
    @endif

  });
</script>
@endpush
