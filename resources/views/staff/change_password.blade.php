@extends('_layouts/staff')

@section('page_title', 'Đổi mật khẩu')

@section('content')

  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>
          Đổi mật khẩu
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
            @if (count($errors))
              @foreach ($errors->all() as $error)
                <div class="alert bg-danger alert-styled-left">
                  <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                  {{ $error }}
                </div>
              @endforeach
            @endif
            <div class="panel panel-flat">
              <div class="panel-body">
                <form method="POST" action="{{ route('Staff::password_change') }}">
                  {{ csrf_field() }}

                  <div class="form-group {{ $errors->has('old_password') ? 'has-error has-feedback' : '' }}">
                    <label for="old_password" class="control-label text-semibold">Mật khẩu cũ</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Mật khẩu cũ"></i>
                    <input type="password" id="old_password" name="old_password" class="form-control" />
                    @if ($errors->has('old_password'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('name') }}</div>
                    @endif
                  </div>

                  <div class="form-group {{ $errors->has('new_password') ? 'has-error has-feedback' : '' }}">
                    <label for="new_password" class="control-label text-semibold">Mật khẩu mới</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Mật khẩu mới"></i>
                    <input type="password" id="new_password" name="new_password" class="form-control" />
                    @if ($errors->has('new_password'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('name') }}</div>
                    @endif
                  </div>

                  <div class="form-group {{ $errors->has('new_password2') ? 'has-error has-feedback' : '' }}">
                    <label for="new_password2" class="control-label text-semibold">Nhập lại Mật khẩu mới</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Mật khẩu mới"></i>
                    <input type="password" id="new_password2" name="new_password2" class="form-control" />
                    @if ($errors->has('new_password2'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('name') }}</div>
                    @endif
                  </div>

                  <div class="text-right">
                    <button type="submit" class="btn btn-primary">Thay đổi</button>
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

@push('scripts_foot')
  <script>
  $(document).ready(function () {
    $(document).on('submit', 'form', function () {
      $('button[type="submit"]').prop('disabled', true);
    });

  });
  </script>
@endpush
