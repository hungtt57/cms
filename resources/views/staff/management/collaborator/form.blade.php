@extends('_layouts/staff')

@section('page_title', isset($collaborator) ? 'Sửa Cộng tác viên' : 'Thêm cộng tác viên')

@section('content')
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>
          <a href="{{ route('Staff::Management::collaborator@index') }}" class="btn btn-link">
            <i class="icon-arrow-left8"></i>
          </a>
          {{ isset($collaborator) ? 'Sửa Cộng tác viên ' . $collaborator->name : 'Thêm cộng tác viên' }}
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
                <form method="POST" enctype="multipart/form-data" action="{{ isset($collaborator) ? route('Staff::Management::collaborator@update', [$collaborator->id]) : route('Staff::Management::collaborator@store') }}">
                  {{ csrf_field() }}
                  @if (isset($collaborator))
                  <input type="hidden" name="_method" value="PUT">
                  @endif

                  <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
                    <label for="name" class="control-label text-semibold">Tên</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') ?: @$collaborator->name }}" />
                    @if ($errors->has('name'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('name') }}</div>
                    @endif
                  </div>

                  <div class="form-group {{ $errors->has('avatar') ? 'has-error' : '' }}">
                    <div class="display-block">
                      <label class="control-label text-semibold">Ảnh đại diện</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Logo của Doanh nghiệp. Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 2Mb"></i>
                    </div>
                    <div class="media no-margin-top">
                      <div class="media-left">
                        <img src="{{ (isset($collaborator) and $collaborator->avatar) ? $collaborator->avatar('thumb_small') : asset('assets/images/image.png') }}" style="width: 64px; height: 64px;" alt="">
                      </div>
                      <div class="media-body">
                        <input type="file" name="avatar" class="js-file">
                        <span class="help-block no-margin-bottom">Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 2Mb</span>
                      </div>
                    </div>
                    @if ($errors->has('avatar'))
                      <div class="help-block">{{ $errors->first('avatar') }}</div>
                    @endif
                  </div>

                  <div class="form-group {{ $errors->has('address') ? 'has-error has-feedback' : '' }}">
                    <label for="address" class="control-label text-semibold">Địa chỉ</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Địa chỉ của Doanh nghiệp"></i>
                    <input type="text" id="address" name="address" class="form-control" value="{{ old('address') ?: @$collaborator->address }}" />
                    @if ($errors->has('address'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('address') }}</div>
                    @endif
                  </div>

                  <div class="form-group {{ $errors->has('email') ? 'has-error has-feedback' : '' }}">
                    <label for="email" class="control-label text-semibold">Email</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Website của Doanh nghiệp"></i>
                    <input type="text" id="email" name="email" class="form-control" value="{{ old('email') ?: @$collaborator->email }}" />
                    @if ($errors->has('email'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('email') }}</div>
                    @endif
                  </div>

                  <div class="form-group {{ $errors->has('phone_number') ? 'has-error has-feedback' : '' }}">
                    <label for="phone-number" class="control-label text-semibold">Số điện thoại</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Website của Doanh nghiệp"></i>
                    <input type="text" id="phone-number" name="phone_number" class="form-control" value="{{ old('phone_number') ?: @$collaborator->phone_number }}" />
                    @if ($errors->has('phone_number'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('phone_number') }}</div>
                    @endif
                  </div>



                  <div class="form-group {{ $errors->has('group') ? 'has-error has-feedback' : '' }}">
                    <label for="group" class="control-label text-semibold">Group</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Group"></i>
                    <select id="group" name="group" class="js-select">
                      @foreach ($groups as $group)
                        <option value="{{ $group->group_id }}" {{ ((old('group') and old('group') == $group->group_id) or (isset($collaborator) and $collaborator->group == $group->group_id)) ? ' selected="selected"' : '' }}>{{ $group->group_id }}</option>
                      @endforeach
                    </select>
                    @if ($errors->has('group'))
                      <div class="help-block">{{ $errors->first('group') }}</div>
                    @endif
                  </div>


                  @if (isset($collaborator))
                    <div class="form-group {{ $errors->has('password') ? 'has-error has-feedback' : '' }}">
                      <label for="passwrod" class="control-label text-semibold">Mật khẩu</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Mật khẩu đăng nhập vào hệ thống <strong>iCheck cho doanh nghiệp</strong> của Doanh nghiệp."></i>
                      <input type="password" id="password" name="password" class="form-control" />
                      @if ($errors->has('password'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('password') }}</div>
                      @endif
                    </div>

                    <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error has-feedback' : '' }}">
                      <label for="password-confirmation" class="control-label text-semibold">Xác nhận Mật khẩu</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Nhập lại mật khẩu ở trên."></i>
                      <input type="password" id="password-confirmation" name="password_confirmation" class="form-control" />
                      @if ($errors->has('password_confirmation'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('password_confirmation') }}</div>
                      @endif
                    </div>

                    <div class="form-group{{ $errors->has('password_change_required') ? ' has-error' : '' }}">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" id="password-change-required" name="password_change_required" value="1" class="js-checkbox" checked="checked">
                          <span class="text-semibold">Yêu cầu Cộng tác viên đổi mật khẩu trong lần đăng nhập đầu tiếp theo</span>
                        </label>
                      </div>
                      @if ($errors->has('password_change_required'))
                        <div class="help-block">{{ $errors->first('password_change_required') }}</div>
                      @endif
                    </div>
                  @else
                    <div class="form-group">
                      Sử dụng mật khẩu ngẫu nhiên
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Hệ thống sẽ sẽ tạo ra một mật khẩu ngẫu nhiên cho Doanh nghiệp."></i>
                      <a id="show-password-inputs" href="#">Đặt mật khẩu</a>
                    </div>

                    <div id="password-inputs" class="hidden">
                      <div class="form-group {{ $errors->has('password') ? 'has-error has-feedback' : '' }}">
                        <label for="passwrod" class="control-label text-semibold">Mật khẩu</label>
                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Mật khẩu đăng nhập vào hệ thống <strong>iCheck cho doanh nghiệp</strong> của Doanh nghiệp."></i>
                        <input type="password" id="password" name="password" class="form-control" />
                        @if ($errors->has('password'))
                          <div class="form-control-feedback">
                            <i class="icon-notification2"></i>
                          </div>
                          <div class="help-block">{{ $errors->first('password') }}</div>
                        @endif
                        <a id="hide-password-inputs" href="#">Sử dụng mật khẩu ngẫu nhiên</a>
                      </div>

                      <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error has-feedback' : '' }}">
                        <label for="password-confirmation" class="control-label text-semibold">Xác nhận Mật khẩu</label>
                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Nhập lại mật khẩu ở trên."></i>
                        <input type="password" id="password-confirmation" name="password_confirmation" class="form-control" />
                        @if ($errors->has('password_confirmation'))
                          <div class="form-control-feedback">
                            <i class="icon-notification2"></i>
                          </div>
                          <div class="help-block">{{ $errors->first('password_confirmation') }}</div>
                        @endif
                      </div>
                    </div>

                    <div class="form-group{{ $errors->has('password_change_required') ? ' has-error' : '' }}">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" id="password-change-required" name="password_change_required" value="1" class="js-checkbox" checked="checked">
                          <span class="text-semibold">Yêu cầu Cộng tác viên đổi mật khẩu trong lần đăng nhập đầu tiên</span>
                        </label>
                      </div>
                      @if ($errors->has('password_change_required'))
                        <div class="help-block">{{ $errors->first('password_change_required') }}</div>
                      @endif
                    </div>
                  @endif

                  <div class="text-right">
                    <button type="submit" class="btn btn-primary">{{ isset($collaborator) ? 'Cập nhật' : 'Thêm mới' }}</button>
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
