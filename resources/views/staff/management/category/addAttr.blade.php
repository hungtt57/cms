@extends('_layouts/staff')

@section('content')
    <style>
        label {
            padding-top: 5px;
        }

        .form-control {
            /*border-color: transparent transparent #009688;*/
        }
    </style>
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>
                    {{ isset($attr) ? 'Sửa thuộc tính ' . $attr->title : 'Thêm thuộc tính' }}
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

                        <div class="panel panel-flat">
                            @if (session('success'))
                                <div class="alert bg-success alert-styled-left">
                                    <button type="button" class="close" data-dismiss="alert"><span>×</span><span
                                                class="sr-only">Close</span></button>
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="panel-body">
                                <form method="POST"
                                      @if(isset($attr))
                                      action="{{ route('Staff::Management::category@updateAttr',[$attr->id])}}">
                                    @else
                                        action="{{ route('Staff::Management::category@addAttrPost')}}">
                                    @endif


                                    {{ csrf_field() }}
                                    @if(isset($attr))
                                        <input type="hidden" name="_method" value="POST">
                                    @endif
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="name" class="control-label text-semibold">Tên thuộc
                                                tính</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="{{ $errors->has('title') ? 'has-error has-feedback' : '' }}">
                                                <input type="text" id="title" name="title"
                                                       placeholder="Nhập tên thuộc tính" class="form-control"
                                                       value="{{(isset($attr->title))?$attr->title:old('title')}}"/>
                                                @if ($errors->has('title'))
                                                    <div class="form-control-feedback">
                                                        <i class="icon-notification2"></i>
                                                    </div>
                                                    <div class="help-block">{{ $errors->first('title') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="name" class="control-label text-semibold">Key</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="{{ $errors->has('key') ? 'has-error has-feedback' : '' }}">
                                                <input type="text" id="key" name="key" placeholder="Nhập key"
                                                       class="form-control"
                                                       value="{{(isset($attr->key))?$attr->key:old('key') }}"/>
                                                @if ($errors->has('key'))
                                                    <div class="form-control-feedback">
                                                        <i class="icon-notification2"></i>
                                                    </div>
                                                    <div class="help-block">{{ $errors->first('key') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="name" class="control-label text-semibold">Giá trị set
                                                sẵn</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="{{ $errors->has('enum') ? 'has-error has-feedback' : '' }}">
                                                <select multiple="true" name="enum[]" id="enum"
                                                        class="form-control tags">
                                                    @if(isset($attr) and $attr->enum)
                                                        @php $enum = $attr->enum;
                                    $enum = explode(',',$enum);

                                                        @endphp
                                                        @foreach($enum as $e)
                                                            <option selected value="{{$e}}">{{$e}}</option>
                                                        @endforeach

                                                    @endif
                                                </select>
                                                @if ($errors->has('enum'))
                                                    <div class="form-control-feedback">
                                                        <i class="icon-notification2"></i>
                                                    </div>
                                                    <div class="help-block">{{ $errors->first('enum') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="name" class="control-label text-semibold">Loại</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="{{ $errors->has('type') ? 'has-error has-feedback' : '' }}">
                                                <table class="table table-hover">
                                                    <tbody>
                                                    <tr role="row" id="">
                                                        <td>Single</td>
                                                        <td>
                                                            <div class="radio">
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="type" class="js-radio"
                                                                           value="single "
                                                                           @if(isset($attr) and trim($attr->type) == 'single') checked @endif>
                                                                </label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr role="row" id="">
                                                        <td>Multiple</td>
                                                        <td>
                                                            <div class="radio">
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="type" class="js-radio"
                                                                           value="multiple"
                                                                           @if(isset($attr) and trim($attr->type) == 'multiple') checked
                                                                           @endif>
                                                                </label>
                                                            </div>
                                                        </td>
                                                    </tr>


                                                    </tbody>
                                                </table>
                                                @if ($errors->has('type'))
                                                    <div class="form-control-feedback">
                                                        <i class="icon-notification2"></i>
                                                    </div>
                                                    <div class="help-block">{{ $errors->first('type') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="country" class="control-label text-semibold">Danh mục</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="{{ $errors->has('category') ? 'has-error has-feedback' : '' }}">
                                                <select id="country" name="categories[]" multiple="multiple"
                                                        class="select-border-color border-warning js-categories-select">
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"

                                                                @if(isset($selected_category) and $selected_category)
                                                                @if(in_array($category->id,$selected_category))
                                                                selected
                                                                @endif

                                                                @endif


                                                                data-level="{{ $category->level }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('category'))
                                                    <div class="form-control-feedback">
                                                        <i class="icon-notification2"></i>
                                                    </div>
                                                    <div class="help-block">{{ $errors->first('category') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>


                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary">
                                            {{ isset($attr) ? 'Sửa' : 'Thêm ' }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
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
        $(".js-help-icon").popover({
            html: true,
            trigger: "hover",
            delay: {"hide": 1000}
        });

        $(".tags").select2({
            dropdownCssClass: 'border-primary',
            containerCssClass: 'border-primary text-primary-700',
            tags: true,
        });

        // Initialize with options
        $(".js-categories-select").select2({
            templateResult: function (item) {
                if (!item.id) {
                    return item.text;
                }

                var originalOption = item.element,
                        prefix = "----------".repeat(parseInt($(item.element).data('level'))),
                        item = (prefix ? prefix + '| ' : '') + item.text;

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

        // Checkboxes, radios
        $(".js-radio,.js-checkbox").uniform({radioClass: "choice"});
        // File input
        $(".js-file").uniform({
            fileButtonClass: "action btn btn-default"
        });

    });
</script>
@endpush
