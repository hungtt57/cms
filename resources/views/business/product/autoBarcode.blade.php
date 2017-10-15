@extends('_layouts/default')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>
                  'Thêm sản phẩm'
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
                      action="{{ route('Business::product@autoBarcode') }}">
                    {{ csrf_field() }}

                    <input type="hidden" name="_method" value="PUT">
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

                                    <div class="form-group {{ $errors->has('gln_id') ? 'has-error has-feedback' : '' }}">
                                        <label for="gln" class="control-label text-semibold">Nhà sản xuất</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Tên Nhà sản xuất hoặc Nhà phân phối sản phẩm"></i>
                                        <select id="gln" name="gln_id" class="js-select">
                                            @foreach ($gln as $g)
                                                <option value="{{ $g->id }}" {{ (isset($product) and $product->gln_id == $g->id) ? ' selected="selected"' : '' }}>{{ $g->name }}({{$g->gln}})</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('gln_id'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('gln_id') }}</div>
                                        @endif
                                    </div>


                                    <div class="form-group {{ $errors->has('prefix') ? 'has-error has-feedback' : '' }}">
                                        <label for="gln" class="control-label text-semibold">Prefix</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="prefix"></i>
                                        <select id="prefix" name="prefix" class="js-select">
                                            @foreach ($gln as $prefix)
                                                @if($prefix->prefix)
                                                <option value="{{$prefix->prefix}}">{{ $prefix->prefix }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @if ($errors->has('prefix'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('prefix') }}</div>
                                        @endif
                                    </div>

                                    <div class=" form-group {{ $errors->has('barcode') ? 'has-error has-feedback' : '' }}">
                                        <label for="name" class="control-label text-semibold">Barcode (GTIN, ISBN, UPC,
                                            ...)</label>
                                        <input type="hidden" id="type" name="type" value="0">
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Mã vạch của sản phẩm"></i>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <input type="text" id="barcode" name="barcode" disabled class="form-control"
                                                       value="{{ @$product->barcode }}"/>
                                            </div>
                                            <div class="col-md-4">

                                                <button id="auto-generate" class="btn btn-primary">Tự sinh mã</button>
                                            </div>

                                        </div>

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
                                        <input type="text" id="price" name="price" class="form-control"
                                               value="{{ @$product->price }}"/>
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
                                                <option value="{{ $category->id }}"
                                                        data-level="{{ $category->level }}" {{ (isset($product) and in_array($category->id, $selectedCategories)) ? ' selected="selected"' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="text-right">
                                        <button type="submit"
                                                class="btn btn-primary">Thêm mới</button>
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
                        via_url: 1,
                        url: img
                    },
                    method: 'POST',
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


        $('#auto-generate').on('click',function(e){
            e.preventDefault();
            var prefix = $('#prefix').val();
            if(prefix!=null){
                $.ajax({
                    url: '{{ route('Business::product@ajaxAutoGenerate') }}',
                    data: {
                        'prefix' : prefix
                    },
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).done(function (data) {
                    data = JSON.parse(data);
                        console.log(data);
//                    $('#barcode').val(data);
                }).fail(function (data) {
                    var error = JSON.parse(data.responseText);
                    if(error.prefix){
                        alert(error.prefix[0]);
                    }
//                    alert(error.error);
                });
            }else{
                alert('Vui lòng chọn prefix');
            }

        });
    });


</script>
@endpush
