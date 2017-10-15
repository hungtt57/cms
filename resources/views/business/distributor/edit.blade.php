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
                    {{ isset($product) ? 'Sửa sản phẩm phân phối' . $product->name : 'Thêm sản phẩm' }}
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
                      action="{{route('Business::product@updateProduct',['id' => $product->id])}}">
                    {{ csrf_field() }}

                    <input type="hidden" name="_method" value="POST">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="panel panel-flat">
                                <div class="panel-body">
                                    <div class="form-group {{ $errors->has('product_name') ? 'has-error has-feedback' : '' }}">
                                        <label for="name" class="control-label text-semibold">Tên</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Tên của sản phẩm"></i>
                                        <input type="text" id="name" name="product_name" class="form-control"
                                               @if(!empty($product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()) and $product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()->status==2)
                                               value="{{$product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()->product_name}}"
                                               @else
                                               value="{{$product->product_name }}"
                                                @endif

                                        />
                                        @if ($errors->has('product_name'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('product_name') }}</div>
                                        @endif
                                    </div>


                                    <div class="form-group {{ $errors->has('gtin_code') ? 'has-error has-feedback' : '' }}">
                                        <label for="name" class="control-label text-semibold">Barcode (GTIN, ISBN, UPC,
                                            ...)</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Mã vạch của sản phẩm"></i>

                                        <input type="hidden" id="barcode" name="gtin_code" class="form-control"
                                               value="{{ @$product->gtin_code }}"/>
                                        <p>{{$product->gtin_code}}</p>
                                        @if ($errors->has('gtin_code'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('gtin_code') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('images') ? 'has-error' : '' }}">
                                        <div class="display-block">
                                            <label class="control-label text-semibold">Hình ảnh</label>
                                            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                               data-content="Hình ảnh của sản phẩm. Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 5Mb"></i>
                                        </div>
                                        <div class="row" id="images">

                                            @if(!empty($product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()) and $product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()->status==2)
                                                @if($product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()->image)
                                                    @foreach(json_decode($product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()->image) as $image)
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
                                            @else
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

                                                            <input type="hidden" name="images[]"
                                                                   value="{{$image['prefix']}}">
                                                        </Div>
                                                    @endforeach
                                                @endif
                                            @endif


                                        </div>
                                        <input type="text" id="image-link" class="form-control"
                                               placeholder="Up ảnh từ link"/>
                                        <button type="button" class="btn btn-primary" id="image-link-button">Upload
                                        </button>
                                        <div class="dropzone" id="my-awesome-dropzone"></div>
                                        @if ($errors->has('images'))
                                            <div class="help-block">{{ $errors->first('images') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="address" class="control-label text-semibold">Giá</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Giá của sản phẩm này"></i>
                                        <input type="number" id="price" name="price" class="form-control"
                                               @if(!empty($product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()) and $product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()->status==2)
                                               value="{{$product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()->price}}"
                                               @else
                                               value="{{ $product->price_default }}"
                                                @endif
                                        />
                                    </div>
                                    @if(Auth::user()->id == 1)

                                        @if(!empty($product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()) and $product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()->status==2)
                                            @php $producTemp = $product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first();
                                                $attrs = json_decode($producTemp->attrs,true);
                                            @endphp

                                            @if(is_array($attrs))
                                                @foreach($attrs as $key => $attr)
                                                    @if($attr)
                                                        <div class="form-group">
                                                            <label for="attr-{{ $key }}"
                                                                   class="control-label text-semibold">{{getAttr($key)->title}}</label>
                                                            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                                               data-content=""></i>
                                                            <textarea id="attr-{{$key}}" name="attrs[{{$key}}]"
                                                                      rows="5" cols="5" class="form-control ckeditor">
                                                        {{$attr}}
                                                      </textarea>

                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @else


                                            @php $attrs = $product->attributes;@endphp

                                            @foreach($attrs as $attr)
                                                @if($attr->pivot->content)
                                                    <div class="form-group">
                                                        <label for="attr-{{ $attr->id }}"
                                                               class="control-label text-semibold">{{$attr->title}}</label>
                                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                                           data-content=""></i>
                                                        <textarea id="attr-{{$attr->id}}" name="attrs[{{$attr->id}}]"
                                                                  rows="5" cols="5" class="form-control ckeditor">
                                                        {{$attr->pivot->content}}
                                                      </textarea>

                                                    </div>
                                                @endif


                                            @endforeach
                                        @endif





                                    @else
                                        @foreach ($attributes as $attr)
                                            @if($attr->id!=3 and $attr->is_core == 1)
                                                <div class="form-group">
                                                    <label for="attr-{{ $attr->id }}"
                                                           class="control-label text-semibold">{{ $attr->title }}</label>
                                                    <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                                       data-content="{{ $attr->title }}"></i>
                                                    <textarea id="attr-{{ $attr->id }}" name="attrs[{{ $attr->id }}]"
                                                              rows="5" cols="5" class="form-control ckeditor">

                                                      @if(!empty($product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()) and $product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()->status==2)
                                                            {{$product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()->getAttr($attr->id)}}
                                                        @else
                                                            {{ (isset($product) and $product->attributes) ? @$product->attributes->first(function ($key, $value) use ($attr) {
                                                               return $value->id == $attr->id;
                                                           })->pivot->content : ''}}
                                                        @endif

                                                </textarea>

                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
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
                                        @if(!empty($product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()) and $product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()->status==2)

                                            @php $c = json_decode($product->productsDistributorTemp()->where('business_id',Auth::user()->id)->first()->categories,true);
                                                  if($c!=null and is_array($c)){
                                                     $selectedCategories = $c;
                                                  }
                                            @endphp

                                        @endif


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
                                    <div id="list-attrs"></div>

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
            $('#price').keydown(function (e) {
                if (e.keyCode === 109 || e.keyCode === 189) {
                    alert('minus sign pressed');
                }
                if (e.which == 8) {
                    return
                }
                if (e.which < 48 || e.which > 57 || e.keyCode == 13) {
                    e.preventDefault();
                }
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
                url: '{{route('Ajax::business@ajaxGetAttributesByEditProductDistributor')}}',
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                data: {
                    selected: selected,
                    gtin_code: '{{$product->gtin_code}}'
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
                }).fail(function () {
                    alert('Lỗi');
                });
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


                {{--@foreach ($attributes as $attr)--}}
                {{--@if($attr->id!=3)--}}
                {{--CKEDITOR.replace('attr-{{ $attr->id }}', {--}}
                {{--extraPlugins: 'forms'--}}
                {{--});--}}
                {{--@endif--}}
                {{--@endforeach--}}

        }
    );
</script>
@endpush
