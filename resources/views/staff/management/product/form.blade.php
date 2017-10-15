@extends('_layouts/staff')

@section('content')
    <style>
        .div-icon-highlight {
            margin-top: 25px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>
                    <a href="{{ route('Staff::Management::product@index') }}" class="btn btn-link">
                        <i class="icon-arrow-left8"></i>
                    </a>
                    {{ isset($product) ? 'Sửa sản phẩm ' . $product->name : 'Thêm sản phẩm' }}
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
                @if (session('success'))
                    <div class="alert bg-success alert-styled-left">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        {{ session('success') }}
                    </div>
                @endif
                <form method="POST" enctype="multipart/form-data"
                      action="{{ isset($product) ? route('Staff::Management::product@update', [$product->id]) : route('Staff::Management::product@store') }}">
                    {{ csrf_field() }}
                    @if (isset($product))
                        <input type="hidden" name="_method" value="PUT">
                    @endif
                    <div class="row">
                        <div class="col-md-8">
                            <div class="panel panel-flat">
                                <div class="panel-body">
                                    <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
                                        <label for="name" class="control-label text-semibold">Tên</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Tên của sản phẩm"></i>
                                        <input type="text" id="name" name="name" class="form-control"
                                               value="{{ @$product->name }}"/>
                                        @if ($errors->has('name'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('barcode') ? 'has-error has-feedback' : '' }}">
                                        <label for="name" class="control-label text-semibold">Barcode (GTIN, ISBN, UPC,
                                            ...)</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Mã vạch của sản phẩm"></i>
                                        <input type="text" id="barcode" name="barcode" class="form-control"
                                               value="{{ @$product->barcode }}"/>
                                        @if ($errors->has('barcode'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('barcode') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('images') ? 'has-error' : '' }}">
                                        <div class="display-block">
                                            <label class="control-label text-semibold">Hình ảnh</label>
                                            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                               data-content="Hình ảnh của sản phẩm. Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 5Mb"></i>
                                        </div>
                                        <div class="row" id="images">
                                            @if (isset($images))
                                                @foreach ($images as $image)
                                                    <Div class="col-md-2">

                                                        <div class="thumb">
                                                            <img src="{{get_image_url($image['prefix'])}}" alt="">
                                                            <div class="caption-overflow">
                                              <span>
                                                <a href="{{get_image_url($image['prefix'])}}"
                                                   class="btn bg-teal-300 btn-rounded btn-icon"
                                                   data-popup="lightbox"><i class="icon-zoom-in"></i></a>
                                                <a href="#"
                                                   class="btn bg-teal-300 btn-rounded btn-icon btn-remove-image"><i
                                                            class="icon-cancel-circle"></i></a>
                                              </span>
                                                            </div>
                                                        </div>
                                                        <div class="radio"><label><input type="radio"
                                                                                         name="image_default"
                                                                                         value="{{$image['prefix']}}"
                                                                                         @if ($image['default']) checked="checked" @endif>
                                                                Ảnh đại diện</label></div>
                                                        <input type="hidden" name="images[]"
                                                               value="{{$image['prefix']}}">
                                                    </Div>
                                                @endforeach
                                            @endif

                                        </div>
                                        <input type="text" id="image-link" class="form-control"
                                               placeholder="Up ảnh từ link"/>
                                        <button type="button" class="btn btn-primary" id="image-link-button">Upload
                                        </button>
                                        <div class="dropzone" id="my-awesome-dropzone"></div>

                                    </div>


                                    <div class="form-group">
                                        <label for="address" class="control-label text-semibold">Giá</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Giá của sản phẩm này"></i>
                                        <input type="text" id="price" name="price" class="form-control"
                                               value="{{ @$product->price }}"/>
                                    </div>

                                    {{--@foreach ($attributes as $attr)--}}
                                    {{--<div class="form-group">--}}
                                    {{--<label for="attr-{{ $attr->id }}" class="control-label text-semibold">{{ $attr->title }}</label>--}}
                                    {{--<i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="{{ $attr->title }}"></i>--}}
                                    {{--<textarea id="attr-{{ $attr->id }}" name="attrs[{{ $attr->id }}]" rows="5" cols="5" class="form-control">{{ @$product->attrs[$attr->id] }}</textarea>--}}
                                    {{--</div>--}}
                                    {{--@endforeach--}}


                                    <div class="list-highlight">

                                        @if(isset($product) and count($product->attrs))

                                            @foreach($product->attrs as $key => $content)
                                                @php
                                                    if(empty($content)){
                                                             break;
                                                    }
                                                @endphp
                                                <div class="highlight">
                                                    <label for="address" class="control-label text-semibold">HighLight
                                                        <button class="btn btn-primary legitRipple btn-xoa-hightlight">
                                                            Xóa
                                                        </button>
                                                    </label>
                                                    <div class="form-group">
                                                        @php $url = asset('assets/images/no-image.png'); @endphp
                                                        <select name="highlight_id[]"
                                                                class="js_select_attr"
                                                        >
                                                            <option value="" data-icon="">Chọn hight light</option>
                                                            @foreach($attributes as $attr)
                                                                <option value="{{$attr->id}}"
                                                                        data-icon="{{$attr->icon}}"
                                                                        @if($attr->id == $key)
                                                                        selected
                                                                        @php $url = $attr->icon; @endphp
                                                                        @endif

                                                                >{{$attr->title}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="col-md-12 div-icon-highlight">
                                                            <label for="address"
                                                                   class="control-label text-semibold col-md-1"
                                                                   style="margin-top: 40px">Icon</label>
                                                            <div class="col-md-2">
                                                                <img width="100" height="100" class="highlight-image"
                                                                     src="{{$url}}" alt="">
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <textarea id="attr-" name="highlight_content[]" rows="5"
                                                                  cols="5"
                                                                  placeholder="Nhập nội dung highlight"
                                                                  class="form-control ckeditor">{{ $content }}</textarea>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                    </div>
                                    <button id="add-more" class="btn btn-info legitRipple">Add more</button>

                                    <div style="display:none" id="highlight-example">

                                        <div class="highlight">
                                            <label for="address" class="control-label text-semibold">HighLight
                                                <button class="btn btn-primary legitRipple btn-xoa-hightlight">Xóa
                                                </button>
                                            </label>
                                            <div class="form-group">
                                                <select name="highlight_id[]" class="js_select_attr_example">
                                                    <option value="" data-icon="">Chọn hight light</option>
                                                    @foreach($attributes as $attr)
                                                        <option value="{{$attr->id}}"
                                                                data-icon="{{$attr->icon}}">{{$attr->title}}</option>

                                                    @endforeach
                                                </select>
                                                <div class="col-md-12 div-icon-highlight">
                                                    <label for="address" class="control-label text-semibold col-md-1"
                                                           style="margin-top: 40px">Icon</label>
                                                    <div class="col-md-2">
                                                        <img width="100" height="100" class="highlight-image"
                                                             src="{{asset('assets/images/no-image.png')}}" alt="">
                                                    </div>

                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <textarea name="highlight_content[]" rows="5"
                                                          cols="5"
                                                          placeholder="Nhập nội dung highlight"
                                                          class="form-control content-ckeditor"></textarea>
                                            </div>
                                        </div>


                                    </div>


                                </div>
                            </div>

                            @if (isset($reports))
                                <div class="panel panel-flat">
                                    <div class="panel-body">

                                        <div class="form-group">
                                            <label for="vendor" class="control-label text-semibold">Báo cáo của người
                                                dùng ({{ $reports->count() }})</label>
                                            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                               data-content="Tên Nhà sản xuất hoặc Nhà phân phối sản phẩm"></i>
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>Đã được giải quyết</th>
                                                    <th>Báo cáo bởi</th>
                                                    <th>Nội dung</th>
                                                    <th>Trạng thái</th>
                                                    <th>Ngày báo cáo</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($reports as $report)
                                                    <tr>
                                                        <td><input type="checkbox" name="report_resolved[]"
                                                                   value="{{ $report->id }}"/></td>
                                                        <td>{{ $report->userId }}</td>
                                                        <td>{{ $report->content }}</td>
                                                        <td>{{ $report->status }}</td>
                                                        <td>{{ $report->createdAt }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>


                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="panel panel-flat">
                                <div class="panel-body">

                                    <div class="form-group">
                                        <label for="country" class="control-label text-semibold">Danh mục</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Danh mục"></i>
                                        <select id="country" name="categories[]" multiple="multiple"
                                                class="select-border-color border-warning js-categories-select">
                                            @foreach ($categories as $category)

                                                @if(isset($category['sub']))

                                                @else
                                                    <option @if(in_array($category['id'],$selectedCategories)) selected
                                                            @endif
                                                            data-level="{{$category['level']}}"
                                                            data-attr="{{$category['attributes']}}"
                                                    >{{ $category['name']}}</option>
                                                @endif


                                                @if(isset($category['sub']))
                                                    @include('staff.management.product2.dequy', array('items' => $category['sub'],'selectedCategories' => $selectedCategories))
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="message" class="control-label text-semibold">Cảnh báo</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Tên Nhà sản xuất hoặc Nhà phân phối sản phẩm"></i>
                                        <select id="message" name="warning_id" class="js-select">
                                            <option value="">Không có</option>
                                            @foreach ($messages as $message)
                                                <option value="{{ $message->id }}" {{ (isset($warning) and $warning and @$warning->message_id == $message->id) ? ' selected="selected"' : '' }}>{{ $message->short_msg }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="vendor" class="control-label text-semibold">Nhà sản xuất</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Tên Nhà sản xuất hoặc Nhà phân phối sản phẩm"></i>
                                        <select id="vendor" name="gln_id" class="js-select">
                                            @foreach ($gln as $vendor)
                                                <option value="{{ $vendor->id }}" {{ (isset($product) and $product->gln_id == $vendor->id) ? ' selected="selected"' : '' }}>{{ $vendor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    @if (isset($distributors))
                                        <div class="form-group">
                                            <label for="vendor" class="control-label text-semibold">Nhà phân
                                                phối</label>
                                            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                               data-content="Tên Nhà sản xuất hoặc Nhà phân phối sản phẩm"></i>
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Tên</th>
                                                    <th>Quốc gia</th>
                                                    <th>Độc quyền?</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($distributors as $distributor)
                                                    <tr>
                                                        <td><input type="checkbox"
                                                                   name="distributors[{{ $distributor->id }}][enabled]"
                                                                   {{ isset($distributorsData[$distributor->id]) ? ' checked="checked"' : '' }} value="1"/>
                                                        </td>
                                                        <td>{{ $distributor->name }}</td>
                                                        <td>{{ @$distributor->country->name }}</td>
                                                        <td><input type="checkbox"
                                                                   name="distributors[{{ $distributor->id }}][is_monopoly]"
                                                                   {{ (isset($distributorsData[$distributor->id]) and $distributorsData[$distributor->id]['is_monopoly'] == 1) ? ' checked="checked"' : '' }} value="1"/>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif

                                    <div class="text-right">
                                        <button type="submit"
                                                class="btn btn-primary">{{ isset($product) ? 'Cập nhật' : 'Thêm mới' }}</button>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /main content -->
        </div>
        <!-- /page content -->
    </div>
    <!-- /page container -->
@endsection

@push('js_files_foot')
<script src="//cdn.ckeditor.com/4.5.10/full/ckeditor.js"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/uploaders/dropzone.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/media/fancybox.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/barcoder.js') }}"></script>
@endpush

@push('scripts_foot')
<script>
    $(document).ready(function () {

        var count = 1000;
        $('.js_select_attr').select2();
        {{--@foreach ($attributes as $attr)--}}
        {{--CKEDITOR.replace('attr-{{ $attr->id }}', {--}}
            {{--extraPlugins: 'forms'--}}
        {{--});--}}
        {{--@endforeach--}}
$('#add-more').click(function (e) {
            e.preventDefault();
            var html = $('#highlight-example').html();
            $('.list-highlight').append(html);
            $('.list-highlight').find('.js_select_attr_example').select2();
            $('.list-highlight').find('.content-ckeditor').attr('id', 'editor-' + count);
            CKEDITOR.replace('editor-' + count, {
                extraPlugins: 'forms'
            });
            count++;
        });
        $('.js_select_attr').on("select2:select", function (e) {
            var url = "{{asset('assets/images/no-image.png')}}";
            var icon = e.params.data.element.attributes.getNamedItem('data-icon').value;
            if (icon) {
                url = icon;
            }
            $(this).parent().find('.highlight-image').attr('src', url);

        });
        $(document).on('click', '.btn-xoa-hightlight', function () {
            $(this).parent().parent().remove();
        });
        $(document).on('select2:select', '.js_select_attr_example', function (e) {
            var url = "{{asset('assets/images/no-image.png')}}";
            var icon = e.params.data.element.attributes.getNamedItem('data-icon').value;
            if (icon) {
                url = icon;
            }
            $(this).parent().find('.highlight-image').attr('src', url);

        });
        $(".js-help-icon").popover({
            html: true,
            trigger: "hover",
            delay: {"hide": 1000}
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
        $(".js-radio").uniform({radioClass: "choice"});

        // File input
        $(".js-file").uniform({
            fileButtonClass: "action btn btn-default"
        });

        @foreach ($attributes as $attr)
CKEDITOR.replace('attr-{{ $attr->id }}', {
            extraPlugins: 'forms'
        });
        @endforeach

    });


    $('#d-multiselect').multiselect({
        enableCaseInsensitiveFiltering: true,
        enableFiltering: true,
        onChange: function (d, b) {
            var id = '#d-' + $(d).val();

            $(id).toggleClass('hidden', !b);
            $.uniform.update();
        }
    });

    $('#image-link-button').on('click', function () {
        var img = $('#image-link').val();

        $.ajax({
            url: '{{ route('Ajax::Staff::upload@image') }}',
            data: {
                via_url: 1,
                url: img
            },
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).done(function (data) {
            $('#image-link').val("");
            $('#images').append(' <Div class="col-md-2"> <div class="thumb"> <img src="' + data.url + '" alt=""> <div class="caption-overflow"> <span> <a href="' + data.url + '" class="btn bg-teal-300 btn-rounded btn-icon" data-popup="lightbox"><i class="icon-zoom-in"></i></a> <a href="#" class="btn bg-teal-300 btn-rounded btn-icon btn-remove-image"><i class="icon-cancel-circle"></i></a> </span> </div> </div><input type="hidden" name="images[]" value="' + data.prefix + '"> </Div>');
        }).fail(function () {
            alert('Lỗi');
        });
    });

    // Defaults
    Dropzone.autoDiscover = false;
    var myDropzone = new Dropzone("#my-awesome-dropzone", {
        url: '{{ route('Ajax::Staff::upload@image') }}',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        paramName: "file", // The name that will be used to transfer the file
        dictDefaultMessage: 'Drop files to upload <span>or CLICK</span>',
        maxFilesize: 5, // MB
        acceptedFiles: 'image/*'
    });

    myDropzone.on('success', function (a, data, c) {
        $('#images').append(' <Div class="col-md-2"> <div class="thumb"> <img src="' + data.url + '" alt=""> <div class="caption-overflow"> <span> <a href="' + data.url + '" class="btn bg-teal-300 btn-rounded btn-icon" data-popup="lightbox"><i class="icon-zoom-in"></i></a> <a href="#" class="btn bg-teal-300 btn-rounded btn-icon btn-remove-image"><i class="icon-cancel-circle"></i></a> </span> </div> </div> <input type="hidden" name="images[]" value="' + data.prefix + '"> </Div>');
        $('[data-popup="lightbox"]').fancybox({
            padding: 3
        });
    });
    // Image lightbox
    $('[data-popup="lightbox"]').fancybox({
        padding: 3
    });
    $(document).on('click', '.btn-remove-image', function (e) {
        e.preventDefault();

        $(this).parents('.col-md-2').remove();
    });


</script>
@endpush
