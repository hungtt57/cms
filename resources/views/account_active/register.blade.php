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
                <form role="form" method="POST" action="{{ route('accountActive::postRegister') }}">
                    {{ csrf_field() }}
                    <div class="text-center">
                        <div class="icon-object"><i class="icon-lock"></i></div>
                    </div>
               <input type="hidden" name="icheck_id" value="{{$icheck_id}}">
                    <div class="panel panel-body login-form mt-20">
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
                        <div class="form-group has-feedback has-feedback-left{{ $errors->has('email') ? ' has-error' : '' }}">
                            <input type="text" name="email" class="form-control text-indigo border-bottom-indigo"
                                   placeholder="Email">
                            <div class="form-control-feedback">
                                <i class="icon-user text-muted"></i>
                            </div>
                            @if ($errors->has('email'))
                                <span class="help-block">
                              <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group has-feedback has-feedback-left{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <input type="text" name="phone" class="form-control text-indigo border-bottom-indigo"
                                   placeholder="Số điện thoại">
                            <div class="form-control-feedback">
                                <i class="icon-lock2 text-muted"></i>
                            </div>
                            @if ($errors->has('phone'))
                                <span class="help-block">
                                  <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                            @endif
                        </div>

                        <button type="submit" class="btn bg-indigo btn-block">Xác thực</button>

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

