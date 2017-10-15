@extends('_layouts/default')

@section('content')
    <style>
        .col-md-6 label {
            padding-top: 8px;
            padding-bottom: 8px;
        }
    </style>
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>
                    <a href="{{ route('Business::product@index') }}" class="btn btn-link">
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
                @if (session('error'))
                    <div class="alert bg-danger alert-styled-left">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        {{ session('error') }}
                    </div>
                @endif
                <form method="POST" enctype="multipart/form-data"
                      action="{{ isset($product) ? route('Business::product@update', [$product->id]) : route('Business::product@store') }}">
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
                                               value="@if(isset($product)){{$product->name}} @else {{old('name')}} @endif"/>
                                        @if ($errors->has('name'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>
                                    @if(!isset($product))
                                        <div class="form-group {{ $errors->has('gln_id') ? 'has-error has-feedback' : '' }}">
                                            <label for="gln" class="control-label text-semibold">Nhà sản xuất</label>
                                            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                               data-content="Tên Nhà sản xuất hoặc Nhà phân phối sản phẩm"></i>
                                            <select id="gln" name="gln_id" class="js-select">
                                                @foreach ($gln as $gln)
                                                    <option value="{{ $gln->id }}"
                                                            data-prefix="{{$gln->prefix}}">{{ $gln->name }}
                                                        ({{$gln->gln}}) Prefix:{{$gln->prefix}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('gln_id'))
                                                <div class="form-control-feedback">
                                                    <i class="icon-notification2"></i>
                                                </div>
                                                <div class="help-block">{{ $errors->first('gln_id') }}</div>
                                            @endif
                                        </div>

                                        <div class=" form-group {{ $errors->has('barcode') ? 'has-error' : '' }}">
                                            <label for="name" class="control-label text-semibold">Barcode (Tự nhập
                                                hoặc chọn sinh mã tự động)</label>
                                            <input type="hidden" id="type" name="type" value="0">
                                            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                               data-content="Mã vạch của sản phẩm"></i>
                                            <div class="row">
                                                <div class="col-md-2" style="border: 1px solid black;">
                                                    <input type="text" style="color:black" id="prefix-barcode"
                                                           class="form-control" name="prefix" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" id="barcode" name="barcode" class="form-control"
                                                           placeholder="Nhập mã phân định sản phẩm "
                                                           value="{{old('barcode')}}"/>
                                                </div>
                                                <div class="col-md-1" style="border: 1px solid black;">
                                                    <input type="text" style="color:black" id="check-code"
                                                           class="form-control" name="check_code" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <button id="get-check-code" class="btn btn-info">Lấy mã kiểm
                                                        tra
                                                    </button>
                                                    <button id="auto-generate" class="btn btn-primary">Tự sinh mã
                                                    </button>
                                                </div>

                                            </div>


                                            @if ($errors->has('barcode'))
                                                <div class="form-control-feedback">
                                                    <i class="icon-notification2"></i>
                                                </div>
                                                <div class="help-block">{{ $errors->first('barcode') }}</div>
                                            @endif
                                            @if ($errors->has('prefix'))
                                                <div class="form-control-feedback">
                                                    <i class="icon-notification2"></i>
                                                </div>
                                                <div class="help-block">{{ $errors->first('prefix') }}</div>
                                            @endif
                                            @if ($errors->has('check_code'))
                                                <div class="form-control-feedback">
                                                    <i class="icon-notification2"></i>
                                                </div>
                                                <div class="help-block">{{ $errors->first('check_code') }}</div>
                                            @endif
                                        </div>

                                    @else


                                        <div class="form-group">
                                            <label for="gln" class="control-label text-semibold">Nhà sản xuất</label>
                                            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                               data-content="Tên Nhà sản xuất hoặc Nhà phân phối sản phẩm"></i>
                                            <p>{{$product->gln->name}}({{@$product->gln->gln}})</p>
                                        </div>

                                        <div class=" form-group">
                                            <label for="name" class="control-label text-semibold">Barcode (Tự nhập
                                                hoặc chọn sinh mã tự động)</label>
                                            <p>{{$product->barcode}}</p>


                                        </div>
                                    @endif


                                    <div class="form-group {{ $errors->has('images') ? 'has-error' : '' }}">
                                        <div class="display-block">
                                            <label class="control-label text-semibold">Hình ảnh</label>
                                            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                               data-content="Hình ảnh của sản phẩm. Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 5Mb"></i>
                                        </div>
                                        <div class="row" id="images">
                                            @if (isset($product->image) && $product->image !=null)
                                                @foreach (json_decode($product->image,true) as $image)
                                                    <Div class="col-md-2">

                                                        <div class="thumb">
                                                            <img src="{{get_image_url($image)}}" alt="">
                                                            <div class="caption-overflow">
                                              <span>
                                                <a href="{{get_image_url($image)}}"
                                                   class="btn bg-teal-300 btn-rounded btn-icon"
                                                   data-popup="lightbox"><i class="icon-zoom-in"></i></a>
                                                <a href="#"
                                                   class="btn bg-teal-300 btn-rounded btn-icon btn-remove-image"><i
                                                            class="icon-cancel-circle"></i></a>
                                              </span>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="images[]"
                                                               value="{{$image}}">
                                                    </Div>
                                                @endforeach
                                            @endif

                                        </div>
                                        <input type="text" id="image-link" class="form-control"
                                               placeholder="Up ảnh từ link"/>
                                        <button type="button" class="btn btn-primary" id="image-link-button">Upload
                                        </button>
                                        <div class="dropzone" id="my-awesome-dropzone"></div>
                                        @if ($errors->has('images'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('images') }}</div>
                                        @endif
                                    </div>


                                    <div class="form-group">
                                        <label for="address" class="control-label text-semibold">Giá</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Giá của sản phẩm này"></i>
                                        <input type="number" id="price" name="price" class="form-control"
                                               value="@if(isset($product)){{$product->price}}@else {{old('price')}} @endif"/>
                                    </div>

                                    @foreach ($attributes as $attr)
                                        <div class="form-group">
                                            <label for="attr-{{ $attr->id }}"
                                                   class="control-label text-semibold">{{ $attr->title }}</label>
                                            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                               data-content="{{ $attr->title }}"></i>
                                            <textarea id="attr-{{ $attr->id }}" name="attrs[{{ $attr->id }}]" rows="5"
                                                      cols="5"
                                                      class="form-control">{{ @$product->attrs[$attr->id] }}</textarea>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
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
                                    <div class="form-group " id="list-attrs">

                                    </div>

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


        $('.js-categories-select').on("select2:select", function (e) {
            var id_select = e.params.data.id;
            var url = '{{route('Ajax::business@ajaxGetAttributes')}}';
            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                data: {
                    id: id_select
                },
                dataType: 'json',
                success: function (data) {

                    $.each(data, function (index, value) {
                        if ($('#' + index).length > 0) {
                            var count = parseInt($('#' + index).attr('data-count'));
                            $('#' + index).attr('data-count', count + 1);
                        } else {
                            $('#list-attrs').append(value);
                            $('.js-attr').select2({
                                dropdownCssClass: 'border-primary',
                                containerCssClass: 'border-primary text-primary-700'
                            });
                        }
                    });
                },
                error: function () {

                }
            });

        });

        $('.js-categories-select').on("select2:unselect", function (e) {

            var attr_id = e.params.data.element.attributes.getNamedItem('data-attr').value;
            if (attr_id) {
                attr_id = attr_id.split(',');
                attr_id.forEach(function (id) {
                    if ($('#' + id).length > 0) {
                        var count = parseInt($('#' + id).attr('data-count'));
                        if (count < 2) {
                            $('#' + id).remove();
                        } else {
                            $('#' + index).attr('data-count', count - 1);
                        }
                    }

                });
            }

        });
                @if(isset($product))
            var selected = $('.js-categories-select').val();
            $.ajax({
                type: "POST",
                url: '{{route('Ajax::business@ajaxGetAttributesByCategory')}}',
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                data: {
                    selected: selected,
                    id: '{{$product->id}}'
                },
                dataType: 'json',
                success: function (data) {
                    $.each(data, function (index, value) {

                        $('#list-attrs').append(value);
                        $('.js-attr').select2({
                            dropdownCssClass: 'border-primary',
                            containerCssClass: 'border-primary text-primary-700'
                        });


                    });
                },
                error: function () {

                }
            });
        @endif








        // Initialize with options
        $(".js-select").select2();

        // Checkboxes, radios
        $(".js-radio").uniform({radioClass: "choice"});

        // File input
        $(".js-file").uniform({
            fileButtonClass: "action btn btn-default"
        });

        $('#image-link-button').on('click', function () {
            var img = $('#image-link').val();

            if (img) {
                $.ajax({
                    url: '{{ route('Business::image') }}',
                    data: {
                        'via_url': 1,
                        'url': img
                    },
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).done(function (data) {
                    $('#image-link').val("");
                    $('#images').append(' <Div class="col-md-2"> <div class="thumb"> <img src="' + data.url + '" alt=""> <div class="caption-overflow"> <span> <a href="' + data.url + '" class="btn bg-teal-300 btn-rounded btn-icon" data-popup="lightbox"><i class="icon-zoom-in"></i></a> <a href="#" class="btn bg-teal-300 btn-rounded btn-icon btn-remove-image"><i class="icon-cancel-circle"></i></a> </span> </div> </div> <input type="hidden" name="images[]" value="' + data.prefix + '"> </Div>');
                }).fail(function (data) {
                    var error = JSON.parse(data.responseText);
                    alert(error.error);

                });
            }

        });

        // Defaults
        Dropzone.autoDiscover = false;
        var myDropzone = new Dropzone("#my-awesome-dropzone", {
            url: '{{ route('Business::image') }}',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            paramName: "file", // The name that will be used to transfer the file
            dictDefaultMessage: 'Drop files to upload <span>or CLICK</span>',
            maxFilesize: 5, // MB
            acceptedFiles: 'image/*'
        });

        myDropzone.on('success', function (a, data, c) {
            $('#images').append(' <Div class="col-md-2"> <div class="thumb"> <img src="' + data.url + '" alt=""> <div class="caption-overflow"> <span> <a href="' + data.url + '" class="btn bg-teal-300 btn-rounded btn-icon" data-popup="lightbox"><i class="icon-zoom-in"></i></a> <a href="#" class="btn bg-teal-300 btn-rounded btn-icon btn-remove-image"><i class="icon-cancel-circle"></i></a> </span> </div> </div><input type="hidden" name="images[]" value="' + data.prefix + '"> </Div>');
            $('[data-popup="lightbox"]').fancybox({
                padding: 3
            });
        });


        myDropzone.on('error', function (a, data, c) {
            alert(data.error);
        });

        // Image lightbox
        $('[data-popup="lightbox"]').fancybox({
            padding: 3
        });

        $(document).on('click', '.btn-remove-image', function (e) {
            e.preventDefault();

            $(this).parents('.col-md-2').remove();
        });


        @foreach ($attributes as $attr)
        CKEDITOR.replace('attr-{{ $attr->id }}', {
            extraPlugins: 'forms'
        });
        @endforeach
         $('#prefix-barcode').val($('#gln option:selected').attr('data-prefix'));
        $('#gln').change(function () {
            $('#prefix-barcode').val($('#gln option:selected').attr('data-prefix'));
        });


        $('#auto-generate').on('click', function (e) {
            e.preventDefault();
            var prefix = $('#gln option:selected').attr('data-prefix');
            if (prefix != null) {
                $.ajax({
                    url: '{{ route('Business::product@ajaxAutoGenerate') }}',
                    data: {
                        'prefix': prefix
                    },
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).done(function (data) {
                    $('#barcode').val(data.barcode);
                    $('#check-code').val(data.checkCode);
                }).fail(function (data) {
                    var error = JSON.parse(data.responseText);
                    if (error.prefix) {
                        alert(error.prefix[0]);
                    }

                });
            } else {
                alert('Vui lòng chọn prefix');
            }

        });

        $('#get-check-code').on('click', function (e) {
            e.preventDefault();
            var barcode = $('#barcode').val();
            var prefix = $('#prefix-barcode').val();
            var length = barcode.length + prefix.length;
            if (length != 12) {
                alert('Vui lòng nhập mã phân định sản phẩm có độ dài là : ' + (12 - prefix.length) + ' kí tự');
            } else {
                $.ajax({
                    url: '{{ route('Business::product@ajaxGetCheckCode') }}',
                    data: {
                        'prefix': prefix,
                        'barcode': barcode
                    },
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).done(function (data) {
                    $('#check-code').val(data.checkCode);
                }).fail(function (data) {
                    var error = JSON.parse(data.responseText);
                    if (error.prefix) {
                        alert(error.prefix[0]);
                    }

                });
            }


        });

    });


</script>
@endpush