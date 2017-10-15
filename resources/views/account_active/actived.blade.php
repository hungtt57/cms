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
    /*.login-form {*/
        /*position: relative;*/
        /*background-image: url('http://i.imgur.com/pym9CNW.png');*/
        /*background-repeat: no-repeat;*/
    /*}*/
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

                    <div class="panel panel-body login-form mt-20">
                        @if ($message)
                            {{ $message }}
                        @endif


                    </div>

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
//    https://www.facebook.com/icheckbaovenguoiviet
window.setTimeout(function(){

    // Move to a new location or you can do something else
    window.location.href = "https://www.facebook.com/icheckbaovenguoiviet";

}, 2000);
    $(document).ready(function () {
        $('.js-checkbox').uniform({
            radioClass: "choice",
            wrapperClass: 'border-indigo text-indigo'
        });
    });
</script>
@endpush

