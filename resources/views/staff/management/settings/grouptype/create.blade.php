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
                    Tạo kiểu nhóm
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
                                <form action="{{ route('Staff::Management::settings@grouptype.store') }}" method="post" enctype="multipart/form-data">
                                    {!! csrf_field() !!}
                                    <div class="form-group {{ $errors->has('type') ? 'has-error has-feedback' : '' }}">
                                        <label class="control-label">Loại</label>
                                        <input type="text" name="type" class="form-control" value="{{ old('type') }}" />
                                        @if ($errors->has('type'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('type') }}</div>
                                        @endif
                                    </div>
                                    <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
                                        <label class="control-label">Tên</label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" />
                                        @if ($errors->has('name'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>
                                    <div class="form-group {{ $errors->has('icon') ? 'has-error' : '' }}">
                                        <div class="display-block">
                                            <label class="control-label text-semibold">Hình ảnh</label>
                                            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Hình ảnh của sản phẩm. Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 5Mb, 100x100"></i>
                                        </div>
                                        <div class="media no-margin-top">
                                            <div class="media-left">
                                                <img src="{{ asset('assets/images/image.png') }}" style="width: 64px; height: 64px;" alt="">
                                            </div>

                                            <div class="media-body">
                                                <input type="file" name="icon" class="js-file">
                                                <span class="help-block no-margin-bottom">Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 5Mb</span>
                                            </div>
                                        </div>
                                        @if ($errors->has('icon'))
                                            <div class="help-block">{{ $errors->first('icon') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="country" class="control-label text-semibold">Danh mục</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Danh mục"></i>
                                        <select id="country" name="categories_refer[]" multiple="multiple" class="select-border-color border-warning js-categories-select">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" data-level="{{ $category->level }}" {{ (! is_null(old('categories_refer')) and in_array($category->id, old('categories_refer'))) ? ' selected="selected"' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-default">Tạo nhóm</button>
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
@endpush

@push('scripts_foot')
<script>
    $(document).ready(function () {

        // Initialize with options
        $(".js-categories-select").select2({
            templateResult: function (item) {
                if (!item.id) {
                    return item.text;
                }

                var originalOption = item.element,
                        prefix = "----------".repeat(parseInt($(item.element).data('level'))),
                        item =  (prefix ? prefix + '| ' : '') + item.text;

                return item;
            },
            templateSelection: function (item) {
                return item.text;
            },
            escapeMarkup: function (m) {
                return m;
            },
            dropdownCssClass: 'border-primary',
            containerCssClass: 'border-primary text-primary-700'
        });

        // Initialize with options
        $(".js-select").select2();

    });
</script>
@endpush