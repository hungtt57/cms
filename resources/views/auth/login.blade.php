@extends('_layouts/auth')

@section('page_title', 'Đăng nhập')

@section('content')
  <!-- Page container -->
  <div class="page-container">
    <!-- Page content -->
    <div class="page-content">
      <!-- Main content -->
      <div class="content-wrapper">
        <!-- Simple login form -->
        <form role="form" method="POST" action="{{ url('/login') }}">
          {{ csrf_field() }}
          <div class="text-center">
            <div class="icon-object border-primary text-primary"><i class="icon-office"></i></div>
            <h5 class="content-group">Đăng nhập vào tài khoản Doanh nghiệp</h5>
          </div>

          <div class="panel panel-body login-form">

            <div class="form-group has-feedback has-feedback-left{{ $errors->has('login_email') ? ' has-error' : '' }}">
              <input type="text" name="login_email" class="form-control" placeholder="Địa chỉ email">
              <div class="form-control-feedback">
                <i class="icon-user text-muted"></i>
              </div>
              @if ($errors->has('login_email'))
                <span class="help-block">
                  <strong>{{ $errors->first('login_email') }}</strong>
                </span>
              @endif
            </div>

            <div class="form-group has-feedback has-feedback-left{{ $errors->has('password') ? ' has-error' : '' }}">
              <input type="password" name="password" class="form-control" placeholder="Mật khẩu">
              <div class="form-control-feedback">
                <i class="icon-lock2 text-muted"></i>
              </div>
              @if ($errors->has('password'))
                <span class="help-block">
                  <strong>{{ $errors->first('password') }}</strong>
                </span>
              @endif
            </div>

            <div class="form-group login-options">
              <div class="row">
                <div class="col-sm-6">
                  <label class="checkbox-inline">
                    <input type="checkbox" name="remember" class="js-checkbox" checked="checked">
                    Duy trì đăng nhập
                  </label>
                </div>

                <div class="col-sm-6 text-right">
                  <a href="{{ url('/password/reset') }}">Quên mật khẩu?</a>
                </div>
              </div>
            </div>

            <div class="form-group">
              <button type="submit" class="btn bg-primary btn-block">Đăng nhập</button>
            </div>

            <div class="text-center">
              <span class="text-muted">Chưa có tài khoản?</span> <a href="{{ url('/register') }}"><u>Đăng ký</u></a>
            </div>
          </div>
        </form>
        <!-- /simple login form -->
      </div>
      <!-- /main content -->
    </div>
    <!-- /page content -->
  </div>
  <!-- /page container -->
@endsection

@push('js_files_foot')
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
@endpush

@push('scripts_foot')
  <script>
  $(document).ready(function () {
    $('.js-checkbox').uniform({ radioClass: "choice" });
  });
  </script>
@endpush

