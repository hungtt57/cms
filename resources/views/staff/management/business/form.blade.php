@extends('_layouts/staff')

@section('content')
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>
          <a href="{{ route('Staff::Management::business@index') }}" class="btn btn-link">
            <i class="icon-arrow-left8"></i>
          </a>
          {{ isset($business) ? 'Sửa Doanh nghiệp ' . $business->name : 'Thêm Doanh nghiệp' }}
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
              @if (session('error'))
                <div class="alert bg-danger alert-styled-left">
                  <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                  {{ session('error') }}
                </div>
              @endif
            <div class="panel panel-flat">
              <div class="panel-body">
                <form method="POST" enctype="multipart/form-data" action="{{ isset($business) ? route('Staff::Management::business@update', [$business->id]) : route('Staff::Management::business@store') }}">
                  {{ csrf_field() }}
                  @if (isset($business))
                  <input type="hidden" name="_method" value="PUT">
                  @endif

                  <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
                    <label for="name" class="control-label text-semibold">Tên</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') ?: @$business->name }}" />
                    @if ($errors->has('name'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('name') }}</div>
                    @endif
                  </div>

                  @if (!isset($business))
                    <div class="form-group {{ $errors->has('gln') ? 'has-error has-feedback' : '' }}">
                      <label for="gln" class="control-label text-semibold">GLN</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Địa chỉ của Doanh nghiệp"></i>
                      <input type="text" id="gln" name="gln" class="form-control" value="{{ old('gln') ?: @$business->gln }}" />
                      @if ($errors->has('gln'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('gln') }}</div>
                      @endif
                    </div>

                    <div class="form-group {{ $errors->has('prefix') ? 'has-error has-feedback' : '' }}">
                      <label for="gln" class="control-label text-semibold">Prefix</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Prefix của Doanh nghiệp"></i>
                      <input type="text" id="prefix" name="prefix" class="form-control" value="{{ old('prefix') ?: @$business->prefix }}" />
                      @if ($errors->has('prefix'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('prefix') }}</div>
                      @endif
                    </div>

                  @endif

                  <div class="form-group {{ $errors->has('logo') ? 'has-error' : '' }}">
                    <div class="display-block">
                      <label class="control-label text-semibold">Logo</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Logo của Doanh nghiệp. Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 2Mb"></i>
                    </div>
                    <div class="media no-margin-top">
                      <div class="media-left">
                        <img src="{{ (isset($business) and $business->logo) ? $business->logo('thumb_small') : asset('assets/images/image.png') }}" style="width: 64px; height: 64px;" alt="">
                      </div>
                      <div class="media-body">
                        <input type="file" name="logo" class="js-file">
                        <span class="help-block no-margin-bottom">Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 2Mb</span>
                      </div>
                    </div>
                    @if ($errors->has('logo'))
                      <div class="help-block">{{ $errors->first('logo') }}</div>
                    @endif
                  </div>

                  <div class="form-group {{ $errors->has('country_id') ? 'has-error has-feedback' : '' }}">
                    <label for="country" class="control-label text-semibold">Quốc gia</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Quốc gia"></i>
                    <select id="country" name="country_id" class="js-select">
                      @foreach ($countries as $country)
                      <option value="{{ $country->id }}" {{ ((old('country_id') and old('country_id') == $country->id) or (isset($business) and $business->country_id == $country->id)) ? ' selected="selected"' : '' }}>{{ $country->name }}</option>
                      @endforeach
                    </select>
                    @if ($errors->has('country_id'))
                      <div class="help-block">{{ $errors->first('country_id') }}</div>
                    @endif
                  </div>

                  <div class="form-group {{ $errors->has('address') ? 'has-error has-feedback' : '' }}">
                    <label for="address" class="control-label text-semibold">Địa chỉ</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Địa chỉ của Doanh nghiệp"></i>
                    <input type="text" id="address" name="address" class="form-control" value="{{ old('address') ?: @$business->address }}" />
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
                    <input type="text" id="email" name="email" class="form-control" value="{{ old('email') ?: @$business->email }}" />
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
                    <input type="text" id="phone-number" name="phone_number" class="form-control" value="{{ old('phone_number') ?: @$business->phone_number }}" />
                    @if ($errors->has('phone_number'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('phone_number') }}</div>
                    @endif
                  </div>

                  <div class="form-group {{ $errors->has('fax') ? 'has-error has-feedback' : '' }}">
                    <label for="fax" class="control-label text-semibold">Fax</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Email của Doanh nghiệp"></i>
                    <input type="text" id="fax" name="fax" class="form-control" value="{{ old('fax') ?: @$business->fax }}" />
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
                    <input type="text" id="website" name="website" class="form-control" placeholder="http://" value="{{ old('website') ?: @$business->website }}" />
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
                    <textarea id="contact-info" name="contact_info" rows="5" cols="5" class="form-control">{{ old('contact_info') ?: @$business->contact_info }}</textarea>
                    @if ($errors->has('contact_info'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('contact_info') }}</div>
                    @endif
                  </div>

                  <div class="form-group {{ $errors->has('login_email') ? 'has-error has-feedback' : '' }}">
                    <label for="login-email" class="control-label text-semibold">Email đăng nhập</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Website của Doanh nghiệp"></i>
                    <input type="text" id="login-email" name="login_email" class="form-control" value="{{ old('login_email') ?: @$business->login_email }}" />
                    @if ($errors->has('login_email'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('login_email') }}</div>
                    @endif
                  </div>

                  @if (isset($business))
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
                          <input type="checkbox" id="password-change-required" name="password_change_required" class="js-checkbox">
                          <span class="text-semibold">Yêu cầu Doanh nghiệp đổi mật khẩu trong lần đăng nhập đầu tiếp theo</span>
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

                      <div class="form-group{{ $errors->has('password_change_required') ? ' has-error' : '' }}">
                        <div class="checkbox">
                          <label>
                            <input type="checkbox" id="password-change-required" name="password_change_required" class="js-checkbox">
                            <span class="text-semibold">Yêu cầu Doanh nghiệp đổi mật khẩu trong lần đăng nhập đầu tiên</span>
                          </label>
                        </div>
                        @if ($errors->has('password_change_required'))
                          <div class="help-block">{{ $errors->first('password_change_required') }}</div>
                        @endif
                      </div>
                    </div>
                  @endif

                  {{--<div class="form-group {{ $errors->has('manager_id') ? 'has-error has-feedback' : '' }}">--}}
                    {{--<label for="password-confirmation" class="control-label text-semibold">Sales Quản Lý</label>--}}
                    {{--<i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="SALES quản lý doanh nghiệp""></i>--}}

                    {{--<select id="country" name="manager_id"  class="select-border-color border-warning js-manager-select">--}}
                      {{--<option value="" >Không chọn</option>--}}
                      {{--@foreach ($managers as $manager)--}}
                        {{--<option value="{{ $manager->id }}" {{ (isset($business) and $business->manager_id and $business->manager_id == $manager->id) ? ' selected="selected"' : '' }}>{{ $manager->email }}</option>--}}
                      {{--@endforeach--}}
                    {{--</select>--}}

                    {{--@if ($errors->has('manager_id'))--}}
                      {{--<div class="form-control-feedback">--}}
                        {{--<i class="icon-notification2"></i>--}}
                      {{--</div>--}}
                      {{--<div class="help-block">{{ $errors->first('manager_id') }}</div>--}}
                    {{--@endif--}}
                  {{--</div>--}}

                  <div class="form-group {{ $errors->has('icheck_id') ? 'has-error has-feedback' : '' }}">
                    <label for="phone-number" class="control-label text-semibold">ICHECK ID</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Website của Doanh nghiệp"></i>
                    <input type="text" id="phone-icheck_id" name="icheck_id" class="form-control" value="{{(isset($business) && $business->icheck_id) ? $business->icheck_id : old('icheck_id')}}" />
                    @if ($errors->has('icheck_id'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('icheck_id') }}</div>
                    @endif
                  </div>


                  <div class="form-group {{ $errors->has('start-date') ? 'has-error has-feedback' : '' }}">
                    <label for="phone-number" class="control-label text-semibold">Ngày bắt đầu hợp đồng</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Website của Doanh nghiệp"></i>
                    @php
                      $startDate = null;
                      $endDate = null;
                      if(isset($business) and $business->start_date and $business->start_date!='0000-00-00 00:00:00'){
                       $startDate = Carbon\Carbon::parse($business->start_date)->format('Y-m-d');
                      }
                     if(isset($business) and $business->end_date and $business->end_date!='0000-00-00 00:00:00'){
                       $endDate = Carbon\Carbon::parse($business->end_date)->format('Y-m-d');
                      }



                    @endphp
                    <input type="text" id="start-date" name="start_date" class="form-control" value="{{$startDate}}" />
                    @if ($errors->has('start_date'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('start_date') }}</div>
                    @endif
                  </div>
                  <div class="form-group {{ $errors->has('end-date') ? 'has-error has-feedback' : '' }}">
                    <label for="phone-number" class="control-label text-semibold">Ngày kết thúc hợp đồng</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Website của Doanh nghiệp"></i>

                    <input type="text" id="end-date" name="end_date" class="form-control" value="{{$endDate}}" />
                    @if ($errors->has('end_date'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('end_date') }}</div>
                    @endif
                  </div>

                  <div class="panel panel-flat">
                    <div class="table-responsive">
                      <table class="table table-hover">
                        <thead>
                        <tr>
                          <th>Group</th>
                          <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($roles as $row)
                          <tr role="row" id="">
                            <td>{{$row->name}}</td>
                            <td>
                              <div class="checkbox">
                                <label>
                                  <input type="radio" id="" name="role[]" value="{{$row->id}}" {{ isset($userRoles[$row->id]) ? ' checked="checked"' : ''  }}  class="js-checkbox">
                                </label>
                              </div>
                            </td>
                          </tr>
                        @endforeach
                        </tbody>
                      </table>


                    </div>

                  </div>

                  <div class="panel panel-flat">
                    <div class="table-responsive">
                      <table class="table table-hover">
                        <thead>
                        <tr>
                          <th>Permission</th>
                          <th>True</th>
                          <th>False</th>
                          <th>NULL</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($permission as $row)
                          <tr role="row" id="">
                            <td>{{$row->id}}</td>
                            <td>
                              <div class="radio">
                                <label class="radio-inline">
                                  <input type="radio" name="status[{{$row->id}}]" {{ (isset($userPermissions[$row->id]) and $userPermissions[$row->id]->pivot->value == 0) ? ' checked="checked"' : ''  }} class="js-radio" value="0">
                                </label>
                              </div>
                            </td>

                            <td>
                              <div class="radio">
                                <label class="radio-inline">
                                  <input type="radio" name="status[{{$row->id}}]" {{ (isset($userPermissions[$row->id]) and $userPermissions[$row->id]->pivot->value == 1) ? ' checked="checked"' : ''  }} class="js-radio" value="1">
                                </label>
                              </div>
                            </td>

                            <td>
                              <div class="radio">
                                <label class="radio-inline">
                                  <input type="radio" name="status[{{$row->id}}]" {{ (isset($userPermissions[$row->id]) and $userPermissions[$row->id]->pivot->value == 2) ? ' checked="checked"' : ''  }} class="js-radio" value="2">
                                </label>
                              </div>
                            </td>
                          </tr>
                        @endforeach
                        </tbody>
                      </table>


                    </div>

                  </div>


                  <div class="text-right">
                    <button type="submit" class="btn btn-primary">{{ isset($business) ? 'Cập nhật' : 'Thêm mới' }}</button>
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
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/daterangepicker.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/picker.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/picker.date.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/picker.time.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/legacy.js') }}"></script>
@endpush

@push('scripts_foot')
  <script>
  $(document).ready(function () {

      $('#end-date').pickadate({
        format: 'yyyy-mm-dd'
      });

      $('#start-date').pickadate({
        format: 'yyyy-mm-dd',
        onStart: function () {
          var fromPicker = $('#start-date').pickadate('picker');

          if (fromPicker.get('select')) {
            var toPicker = $('#end-date').pickadate('picker');

            toPicker.set('min', fromPicker.get('select').obj);
          }
        },
        onSet: function (context) {
          var toPicker = $('#end-date').pickadate('picker');

          if (toPicker.get('select') && toPicker.get('select').pick <= context.select) {
            toPicker.set('select', new Date(context.select));
          }

          toPicker.set('min', new Date(context.select));
        }
      });


    // Basic
    $(".js-select").select2();
    $(".js-manager-select").select2();
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
