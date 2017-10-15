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
          @if ($business->isActivated)
            Thông tin doanh nghiệp
          @elseif ($business->isPendingActivation)
            Yêu cầu đăng ký của doanh nghiệp
          @endif
        </h2>
      </div>

      <div class="heading-elements">
        <div class="heading-btn-group">
          <a href="{{ route('Staff::Management::business@edit', [$business->id]) }}" class="btn btn-link"><i class="icon-pencil5"></i> Sửa</a>
          @if ($business->isActivated)
            <a href="#" class="btn btn-link disabled"><i class="icon-bin"></i> Xoá</a>
          @elseif ($business->isPendingActivation)
            <a href="#" data-toggle="modal" data-target="#approve-modal" class="btn btn-link"><i class="icon-checkmark-circle"></i> Chấp nhận</a>
            <a href="#" data-toggle="modal" data-target="#disapprove-modal" class="btn btn-link"><i class="icon-cancel-circle"></i> Không Chấp nhận</a>
          @endif
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
        @if (session('success'))
          <div class="alert bg-success alert-styled-left">
            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
            {{ session('success') }}
          </div>
        @endif
        @if (count($errors))
          @foreach ($errors->all() as $error)
            <div class="alert bg-danger alert-styled-left">
              <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
              {{ $error }}
            </div>
          @endforeach
        @endif

        <form class="form-horizontal">
          <div class="form-group">
            <h3 class="no-margin-top">
              {{ $business->name }}
              @if ($business->isActivated)
                <i class="icon-checkmark-circle text-success js-tooltip" data-content="Đã kích hoạt"></i>
              @elseif ($business->isPendingActivation)
                <i class="icon-clock2 text-warning js-tooltip" data-content="Đang chờ kích hoạt"></i>
              @endif
            </h3>
          </div>
          <div class="form-group">
            <label class="col-md-3 control-label">Quốc gia</label>
            <div class="col-md-9">
              <div class="form-control-static">{{ $business->country->name }}</div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 control-label">Địa chỉ</label>
            <div class="col-md-9">
              <div class="form-control-static">{{ $business->address }}</div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 control-label">Email</label>
            <div class="col-md-9">
              <div class="form-control-static">{{ $business->email }}</div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 control-label">Số điện thoại</label>
            <div class="col-md-9">
              <div class="form-control-static">{{ $business->phone_number }}</div>
            </div>
          </div>
          @if ($business->fax)
            <div class="form-group">
              <label class="col-md-3 control-label">Fax</label>
              <div class="col-md-9">
                <div class="form-control-static">{{ $business->fax }}</div>
              </div>
            </div>
          @endif
          @if ($business->website)
            <div class="form-group">
              <label class="col-md-3 control-label">Trang web</label>
              <div class="col-md-9">
                <div class="form-control-static">{{ $business->website }}</div>
              </div>
            </div>
          @endif
          <div class="form-group">
            <label class="col-md-3 control-label">Ngày đăng ký</label>
            <div class="col-md-9">
              <div class="form-control-static">{{ $business->created_at }}</div>
            </div>
          </div>
          @if ($business->isActivated)
            <div class="form-group">
              <label class="col-md-3 control-label">Ngày kích hoạt</label>
              <div class="col-md-9">
                <div class="form-control-static">{{ $business->activated_at }}</div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label">Mã GLN</label>
              <div class="col-md-9">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th><input type="checkbox" id="select-all" class="js-checkbox" /></th>
                      <th>Doanh nghiệp</th>
                      <th>Tên GLN</th>
                      <th>Mã GLN</th>
                      <th>Quốc gia</th>
                      <th>Trạng thái</th>
                      <th>Ngày tạo</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($business->gln as $index => $number)
                      <tr role="row" id="gln-{{ $number->id }}">
                        <td><input type="checkbox" name="selected[{{ $number->id }}]" class="js-checkbox" value="1" /></td>
                        <td>{{ $number->business->name }}</td>
                        <td>{{ $number->name }}</td>
                        <td>{{ $number->gln }}</td>
                        <td>{{ $number->country->name }}</td>
                        <td>{{ $number->statusText }}</td>
                        <td>{{ $number->created_at }}</td>
                        <td>
                          <div class="dropdown">
                            <button id="gln-{{ $number->id }}-actions" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link">
                              <i class="icon-more2"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="gln-{{ $number->id }}-actions">
                              <li><a href="#" data-toggle="modal" data-target="#approve-modal" data-gln="{{ $number->gln }}" data-approve-url="{{ route('Staff::Management::gln@approve', [$number->id]) }}"><i class="icon-checkmark-circle2"></i> Chấp nhận</a></li>
                              <li><a href="{{ route('Staff::Management::gln@edit', [$number->id]) }}"><i class="icon-pencil5"></i> Sửa</a></li>
                              <li><a href="#" data-toggle="modal" data-target="#delete-modal" data-name="{{ $number->name }}" data-delete-url="{{ route('Staff::Management::gln@delete', [$number->id]) }}"><i class="icon-trash"></i> Xoá</a></li>
                            </ul>
                          </div>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          @elseif ($business->isPendingActivation)
            <div class="form-group">
              <label class="col-md-3 control-label">Mã địa điểm toàn cầu (GLN)</label>
              <div class="col-md-9">
                <div class="form-control-static">{{ $business->gln[0]->gln }}</div>
              </div>
            </div>
          @endif
        </form>
      </div>
      <!-- /main content -->
    </div>
    <!-- /page content -->
  </div>
  <!-- /page container -->

  <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="delete-modal-label">Xoá Doanh nghiệp</h4>
        </div>
        <div class="modal-body">
          Bạn có chắc chắn muốn xoá Doanh nghiệp <strong class="text-danger js-business-name"></strong> khỏi hệ thống của iCheck?
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

  <div class="modal fade" id="disapprove-modal" tabindex="-1" role="dialog" aria-labelledby="disapprove-modal-label">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="disapprove-modal-label">Huỷ yêu cầu đăng ký của Doamh nghiệp</h4>
        </div>
        <form method="POST" action="{{ route('Staff::Management::business@disapprove', [$business->id]) }}">
          <div class="modal-body">
            <div class="form-group">
              <label for="reason" class="control-label text-semibold">Lý do</label>
              <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do bạn chấp nhận đăng tải sản phẩm này lên hệ thống cảu iCheck"></i>
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

  <div class="modal fade" id="approve-modal" tabindex="-1" role="dialog" aria-labelledby="approve-modal-label" data-backdrop="static">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form method="POST" action="{{ route('Staff::Management::business@approve', [$business->id]) }}">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="approve-modal-label">Chấp nhận đơn Đăng ký của doanh nghiệp</h4>
          </div>
          <div class="modal-body">
            <div class="form-group {{ $errors->has('login_email') ? 'has-error has-feedback' : '' }}">
              <label for="id" class="control-label text-semibold">Email đăng nhập</label>
              <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Email mà Doanh nghiệp sẽ dùng để đăng nhập vào hệ thống <strong>iCheck cho doanh nghiệp</strong>. <strong class='text-danger'>Email này phải là duy nhất</strong> trên toàn hệ thống <strong>iCheck cho doanh nghiệp</strong>."></i>
              <input type="email" id="email" name="login_email" class="form-control" required="required" />
              @if ($errors->has('login_email'))
                <div class="form-control-feedback">
                  <i class="icon-notification2"></i>
                </div>
                <div class="help-block">{{ $errors->first('login_email') }}</div>
              @endif
            </div>

            <div id="approve-modal-password-random" class="form-group">
              Sử dụng mật khẩu ngẫu nhiên
              <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Hệ thống sẽ sẽ tạo ra một mật khẩu ngẫu nhiên cho Doanh nghiệp."></i>
              <a href="#">Đặt mật khẩu</a>
            </div>

            <div id="approve-modal-password-inputs" class="hidden">
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
                <a href="#">Sử dụng mật khẩu ngẫu nhiên</a>
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
@endsection

@push('js_files_foot')
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
@endpush

@push('scripts_foot')
  <script>
  $(document).ready(function () {
    $(".js-tooltip, .js-help-icon").popover({
      container: "body",
      html: true,
      trigger: "hover",
      delay: { "hide": 1000 }
    });

    $('#approve-modal-password-random').on('click', 'a', function (e) {
      e.preventDefault();

      $('#approve-modal-password-random').addClass('hidden');
      $('#approve-modal-password-inputs').removeClass('hidden');
    });

    $('#disapprove-modal-password-inputs').on('click', 'a', function (e) {
      e.preventDefault();

      $('#disapprove-modal-password-inputs').addClass('hidden');
      $('#disapprove-modal-password-random').removeClass('hidden');
    });

    $(".js-checkbox").uniform({ radioClass: "choice" });

  });
  </script>
@endpush
