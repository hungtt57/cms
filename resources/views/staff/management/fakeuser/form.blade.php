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
                    {{ isset($user) ? 'Sửa Thành viên ' : 'Thêm Thành viên' }}
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
                                    <form method="POST" enctype="multipart/form-data" action="{{ isset($user) ? route('Staff::Management::fake@update', [$user->id] ): route('Staff::Management::fake@store') }}">
                                        {{ csrf_field() }}
                                        @if (isset($user))
                                            <input type="hidden" name="_method" value="PUT">
                                    @endif
                                    <!---------- Name------------>
                                        <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
                                            <label for="name" class="control-label text-semibold">Tên</label>
                                            <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') ?: @$user->name }}" />
                                            @if ($errors->has('name'))

                                                <div class="form-control-feedback">
                                                    <i class="icon-notification2"></i>
                                                </div>
                                                <div class="help-block">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>
                                        <!----- Upload Image Here ---->
                                        <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
                                            <div class="display-block">
                                                <label class="control-label text-semibold">Logo</label>
                                                <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Logo của Doanh nghiệp. Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 2Mb"></i>
                                            </div>
                                            <div class="media no-margin-top">
                                                <div class="media-left">
                                                    <img src="{{ (isset($user) and $user->avatar) ? get_image_url($user->avatar, 'thumb_small') : asset('assets/images/image.png') }}" style="width: 64px; height: 64px;" alt="">
                                                </div>
                                                <div class="media-body">
                                                    <input type="file" name="image" class="js-file">
                                                    <span class="help-block no-margin-bottom">Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 2Mb</span>
                                                </div>
                                            </div>

                                            @if ($errors->has('image'))

                                                <div class="help-block">{{ $errors->first('image') }}</div>
                                            @endif
                                        </div>





                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">{{ isset($user) ? 'Cập nhật' : 'Thêm mới' }}</button>
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



    });
</script>
@endpush
