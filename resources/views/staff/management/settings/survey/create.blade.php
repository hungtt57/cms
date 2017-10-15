@extends('_layouts/default')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>
                    <a href="" class="btn btn-link">
                        <i class="icon-arrow-left8"></i>
                    </a>
                    Tạo khảo sát
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
                    <div class="col-md-8 col-md-offset-2">
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <form action="{{ route('Staff::Management::settings@survey.store') }}" method="post" enctype="multipart/form-data">
                                    {!! csrf_field() !!}
                                    <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
                                        <div class="display-block">
                                            <label class="control-label text-semibold">Hình ảnh</label>
                                            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Hình ảnh của sản phẩm. Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 5Mb, 100x100"></i>
                                        </div>
                                        <div class="media no-margin-top">
                                            <div class="media-left">
                                                <img src="{{ asset('assets/images/image.png') }}" style="width: 64px; height: 64px;" alt="">
                                            </div>

                                            <div class="media-body">
                                                <input type="file" name="image" class="js-file">
                                                <span class="help-block no-margin-bottom">Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 5Mb</span>
                                            </div>
                                        </div>
                                        @if ($errors->has('image'))
                                            <div class="help-block">{{ $errors->first('image') }}</div>
                                        @endif
                                    </div>
                                    <div class="form-group {{ $errors->has('message') ? 'has-error has-feedback' : '' }}">
                                        <label class="control-label">Thông điệp</label>
                                        <input type="text" name="message" class="form-control" value="" />
                                        @if ($errors->has('message'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('message') }}</div>
                                        @endif
                                    </div>
                                    <div class="form-group {{ $errors->has('location') ? 'has-error has-feedback' : '' }}">
                                        <label class="control-label">Vị trí nhận survey</label>
                                        <input type="text" name="location" class="form-control tokenfield-primary" value="" />
                                        @if ($errors->has('location'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('location') }}</div>
                                        @endif
                                    </div>
                                    <div class="form-group {{ $errors->has('link') ? 'has-error has-feedback' : '' }}">
                                        <label class="control-label">Liên kết</label>
                                        <input type="text" name="link" class="form-control" value="" />
                                        @if ($errors->has('link'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('link') }}</div>
                                        @endif
                                    </div>
                                    <button type="submit" class="btn btn-default">Tạo survey</button>
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
    <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/tags/tokenfield.min.js') }}"></script>
@endpush

@push('scripts_foot')
<script>
    $(document).ready(function () {

        // Add class on init
        $('.tokenfield-primary').on('tokenfield:initialize', function (e) {
            $(this).parent().find('.token').addClass('bg-primary')
        });

        // Initialize plugin
        $('.tokenfield-primary').tokenfield();

        // Add class when token is created
        $('.tokenfield-primary').on('tokenfield:createdtoken', function (e) {
            $(e.relatedTarget).addClass('bg-primary')
        });

    });
</script>
@endpush