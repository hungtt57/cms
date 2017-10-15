@extends('_layouts/auth')

@section('page_title', 'Xác thực Account')

@push('styles_head')
<style>
    .footer {
        color: #ffffff !important;
    }
    body{
        background-color: #3F51B5;
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
                <form role="form" method="POST" action="{{ route('accountActive::postResetPasswordTest') }}">
                    {{ csrf_field() }}
                    <div class="text-center">
                        <div class="icon-object"><i class="icon-lock"></i></div>
                    </div>

                    <div class="panel panel-body login-form mt-20">
                        <input type="hidden" name="icheck_id" value="{{$icheck_id}}">
                        <input type="hidden" name="token" value="{{$token}}">
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
                        <div class="form-group has-feedback has-feedback-left{{ $errors->has('password') ? ' has-error' : '' }}">
                            <input type="password" name="password" class="form-control text-indigo border-bottom-indigo"
                                   placeholder="Password">
                            <div class="form-control-feedback">
                                <i class="icon-user text-muted"></i>
                            </div>
                            @if ($errors->has('password'))
                                <span class="help-block">
                              <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        </div>

                        <button type="submit" class="btn bg-indigo btn-block">Cập nhật</button>

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

