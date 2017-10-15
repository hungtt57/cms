@extends('_layouts/auth')

@section('page_title', 'Đăng nhập')

@push('styles_head')
  <style>
    .footer {
      color: #ffffff !important;
    }
    .login-form {
      position: relative;
      background-image: url('http://i.imgur.com/pym9CNW.png');
      background-repeat: no-repeat;
    }
  </style>
@endpush

@section('content')
  <!-- Page container -->
  <div class="page-container bg-indigo">
    <!-- Page content -->
    <div class="page-content">
      <!-- Main content -->
      <div class="content-wrapper">
        <!-- Simple login form -->
        <form role="form" method="POST" action="{{ route('Staff::postLogin') }}">
          {{ csrf_field() }}
          <div class="text-center">
            <div class="icon-object"><i class="icon-lock"></i></div>
          </div>

          <div class="panel panel-body login-form mt-20">

            <div class="form-group has-feedback has-feedback-left{{ $errors->has('email') ? ' has-error' : '' }}">
              <input type="text" name="email" class="form-control text-indigo border-bottom-indigo" placeholder="Email">
              <div class="form-control-feedback">
                <i class="icon-user text-muted"></i>
              </div>
              @if ($errors->has('email'))
                <span class="help-block">
                  <strong>{{ $errors->first('email') }}</strong>
                </span>
              @endif
            </div>

            <div class="form-group has-feedback has-feedback-left{{ $errors->has('password') ? ' has-error' : '' }}">
              <input type="password" name="password" class="form-control text-indigo border-bottom-indigo" placeholder="Mật khẩu">
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
                  <a href="{{ url('/password/reset') }}" class="text-indigo">Quên mật khẩu?</a>
                </div>
              </div>
            </div>

            <button type="submit" class="btn bg-indigo btn-block">Open, Sesame</button>

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
    $('.js-checkbox').uniform({
      radioClass: "choice",
      wrapperClass: 'border-indigo text-indigo'
    });
  });
  </script>
@endpush

