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
                   Push Notification app
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
                                <form method="POST" action="{{route('Staff::Management::notificationapp@store') }}">
                                {{ csrf_field() }}

                                <!---------- Name------------>
                                    <div class="form-group ">
                                        <label for="name" class="control-label text-semibold">Nội dung</label>
                                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                                        <input type="text" id="message" name="message" class="form-control" />
                                    </div>
                                    <!------------------ Email--------------->



                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary">Thêm mới</button>
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
{{--<script>--}}
    {{--$(document).ready(function () {--}}
        {{--// Basic--}}
        {{--$(".js-select").select2();--}}

        {{--//--}}
        {{--// Select with icons--}}
        {{--//--}}

        {{--// Format icon--}}
        {{--function iconFormat(icon) {--}}
            {{--var originalOption = icon.element;--}}
            {{--if (!icon.id) { return icon.text; }--}}
            {{--var $icon = "<i class='icon-" + $(icon.element).data('icon') + "'></i>" + icon.text;--}}

            {{--return $icon;--}}
        {{--}--}}

        {{--// Initialize with options--}}
        {{--$(".select-icons").select2({--}}
            {{--templateResult: iconFormat,--}}
            {{--minimumResultsForSearch: Infinity,--}}
            {{--templateSelection: iconFormat,--}}
            {{--escapeMarkup: function(m) { return m; }--}}
        {{--});--}}



        {{--// Styled form components--}}
        {{--// --------------------------------}}

        {{--// Checkboxes, radios--}}
        {{--$(".js-radio, .js-checkbox").uniform({ radioClass: "choice" });--}}

        {{--// File input--}}
        {{--$(".js-file").uniform({--}}
            {{--fileButtonClass: "action btn btn-default"--}}
        {{--});--}}

        {{--$(".js-tooltip, .js-help-icon").popover({--}}
            {{--container: "body",--}}
            {{--html: true,--}}
            {{--trigger: "hover",--}}
            {{--delay: { "hide": 1000 }--}}
        {{--});--}}

        {{--// Toggle password inputs--}}
        {{--$(document).on('click', 'a#show-password-inputs', function (e) {--}}
            {{--e.preventDefault();--}}

            {{--$('#password-inputs').removeClass('hidden').prev().addClass('hidden');--}}
        {{--});--}}

        {{--$(document).on('click', 'a#hide-password-inputs', function (e) {--}}
            {{--e.preventDefault();--}}

            {{--$('#password-inputs').addClass('hidden').prev().removeClass('hidden');--}}
        {{--});--}}

        {{--@if ($errors->has('password'))--}}
        {{--$('a#show-password-inputs').trigger('click');--}}
        {{--@endif--}}

    {{--});--}}
{{--</script>--}}
@endpush
