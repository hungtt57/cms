@extends((isset($_noheader) and $_noheader == true) ? '_layouts/staff_noheader' : '_layouts/staff')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>
                    <a href="{{ route('Staff::Management::product2@index') }}" class="btn btn-link">
                        <i class="icon-arrow-left8"></i>
                    </a>
                    {{ isset($product) ? 'Sửa sản phẩm ' . $product->name : 'Thêm sản phẩm' }}
                </h2>
                @if(isset($product))
                    <form method="POST" action="{{route('Staff::Management::product2@delete',['gtin' => $product->gtin_code])}}">
                        <input type="hidden" name="_method" value="POST">
                        {{ csrf_field() }}
                        <div class="text-right">
                            <button type="submit"
                                    class="btn btn-primary btn-xoa">Xóa</button>
                        </div>
                    </form>
                @endif
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
                      action="{{ isset($product) ? route('Staff::Management::product2@update', [$product->id]) : route('Staff::Management::product2@store') }}">
                    {{ csrf_field() }}
                    @if (isset($product))
                        <input type="hidden" name="_method" value="PUT">
                    @endif
                    <div class="row">
                        <div class="col-md-8">
                            <div class="panel panel-flat">
                                <div class="panel-body">
                                    <div class="form-group {{ $errors->has('product_name') ? 'has-error has-feedback' : '' }}">
                                        <label for="product_name" class="control-label text-semibold">Tên</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Tên của sản phẩm"></i>
                                        <input type="text" id="product_name"  name="product_name" class="form-control"
                                               value="{{ (isset($product->product_name)) ? $product->product_name : old('product_name')}}"/>
                                        @if (@$m->name_user)
                                            <div class="help-block">Tên do người dùng đóng góp:
                                                <strong>{{ $m->name_user }}</strong><br/><a
                                                        href="{{ route('Staff::Management::product2@moonCake', ['id' => $m->id]) }}"
                                                        target="_blank">This is Bánh trung thu</a><
                                            </div>
                                        @endif
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
                                        <input type="text" id="gtin_code" name="gtin_code" class="form-control"
                                               value="@if(isset($product->gtin_code)){{$product->gtin_code}}@elseif(Request::input('gtin_code')){{Request::input('gtin_code')}}@else{{old('gtin_code')}}@endif"/>
                                        @if ($errors->has('gtin_code'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('gtin_code') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
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
                                                <a href="{{get_image_url($image['prefix'])}}" class="btn bg-teal-300 btn-rounded btn-icon"
                                                   data-popup="lightbox"><i class="icon-zoom-in"></i></a>
                                                <a href="#" class="btn bg-teal-300 btn-rounded btn-icon btn-remove-image"><i
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
                                        <input type="text" id="price_default" name="price_default" class="form-control"
                                               value="{{(isset($product->price_default)) ? $product->price_default : old('price_default')}}"/>
                                        @if ($errors->has('price_default'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('price_default') }}</div>
                                        @endif
                                    </div>



                                    <div class="form-group {{ $errors->has('currency_default') ? 'has-error has-feedback' : '' }}">
                                        <label for="name" class="control-label text-semibold">Currency</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="currency của sản phẩm"></i>
                                        <select id="currency_default" name="currency_default"  class="js-select">
                                            @foreach($currencies as $key => $value)
                                                <option @if(isset($product) and $product->currency_default == $value->id) selected="selected"  @endif value="{{$value->id}}">{{$value->code}}({{$value->symbol}})</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('currency_default'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('currency_default') }}</div>
                                        @endif
                                    </div>





                                @foreach ($attributes as $attr)
                                        <div class="form-group">
                                            <label for="attr-{{ $attr->id }}"
                                                   class="control-label text-semibold">{{ $attr->title }}</label>
                                            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                               data-content="{{ $attr->title }}"></i>
                                            <textarea id="attr-{{ $attr->id }}" name="attrs[{{ $attr->id }}]" rows="5"
                                                      cols="5" class="form-control">{{ (isset($product) and $product->attributes) ? @$product->attributes->first(function ($key, $value) use ($attr) {
    return $value->id == $attr->id;
})->pivot->content : '' }}</textarea>
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
                                                    <option @if(in_array($category['id'],$selectedCategories)) selected @endif
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

                                    <div class="form-group" id="members-div">
                                        <label for="name" class="control-label text-semibold">Link VIDEO :</label>
                                        @if(isset($videos))
                                        @foreach($videos as $key => $video)
                                        <input type="text" class="form-control f3" id="video{{$key}}"
                                               name="videos[]" placeholder="Add Link of Team" value="{{$video}}"><a href="#" onclick="removeMember({{$key}})">Xóa</a>
                                        @endforeach
                                        @endif
                                        <input id="add-new" type="button" value='Thêm link' class="btn btn-default member-button ">
                                    </div>



                                    <div class="form-group {{ $errors->has('date_validity') ? 'has-error has-feedback' : '' }}">
                                        <label for="name" class="control-label text-semibold">Ngày kí hợp đồng</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Ngày kí hợp đồng"></i>
                                        <input type="text" id="date_validity" name="date_validity" class="form-control" value="{{ @$product->date_validity }}" />
                                        @if ($errors->has('date_validity'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('date_validity') }}</div>
                                        @endif
                                    </div>


                                    <div class="form-group {{ $errors->has('expiration_date') ? 'has-error has-feedback' : '' }}">
                                        <label for="name" class="control-label text-semibold">Ngày hết hạn hợp đồng</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Ngày hết hạn hợp đồng"></i>
                                        <input type="text" id="expiration_date" name="expiration_date" class="form-control" id="" value="{{ @$product->expiration_date }}" />
                                        @if ($errors->has('expiration_date'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('expiration_date') }}</div>
                                        @endif
                                    </div>


                                    <div class="form-group {{ $errors->has('score') ? 'has-error has-feedback' : '' }}">
                                        <label for="name" class="control-label text-semibold">Score</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Score của sản phẩm"></i>
                                        <input type="text" id="score" name="score" class="form-control" value="{{ @$product->score }}" />
                                        @if ($errors->has('score'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('score') }}</div>
                                        @endif
                                    </div>



                                    <div class="form-group {{ $errors->has('keywords') ? 'has-error has-feedback' : '' }}">
                                        <label for="name" class="control-label text-semibold">Keywords</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="keywords của sản phẩm"></i>
                                        {{--<input type="text" id="keywords" name="keywords" class="form-control" value="{{ @$product->score }}" />--}}
                                        <select id="keywords" name="keywords[]" multiple="multiple">
                                            @if(isset($product))
                                                @php $keywords = $product->keywords;
                                                $keywords = explode(',',$keywords);
                                                @endphp
                                                @if($keywords)
                                                    @foreach($keywords as $k)
                                                        <option selected="selected" value="{{$k}}">{{$k}}</option>
                                                    @endforeach
                                                @endif
                                            @endif
                                        </select>
                                        @if ($errors->has('keywords'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('keywords') }}</div>
                                        @endif
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


                                    <div class="form-group {{ $errors->has('vendor') ? 'has-error' : '' }}">
                                        <label for="vendor" class="control-label text-semibold">Nhà sản xuất</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Tên Nhà sản xuất hoặc Nhà phân phối sản phẩm"></i>

                                        @if (!isset($product) or Auth::guard('staff')->user()->can('update-product-vendor'))
                                            <input type="text" id="vendor" name="vendor" class="form-control"
                                                   value="{{ isset($product) ? @$product->vendor2()->first()->gln_code : old('vendor') }}"/>
                                        @endif
                                        @if (isset($product))
                                            <p>Vendor name: <strong>{{ @$product->vendor2->name}}</strong></p>
                                            <p>Address: <strong>{{ @$product->vendor2->address}}</strong></p>
                                            <p>Phone: <strong>{{ @$product->vendor2->phone}}</strong></p>
                                        @endif
                                        @if ($errors->has('vendor'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('vendor') }}</div>
                                        @endif
                                    </div>


                                    @if (isset($distributors))
                                        <div class="form-group">
                                            <label for="vendor" class="control-label text-semibold">Nhà phân
                                                phối</label>
                                            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                               data-content="Tên Nhà sản xuất hoặc Nhà phân phối sản phẩm"></i>
                                            <div class="multi-select-full">
                                                <select id="d-multiselect" name="distributors_selected[]"
                                                        class="multiselect" multiple="multiple">
                                                    @foreach ($distributors as $distributor)
                                                        <option value="{{ $distributor->id }}" {{ isset($distributorsData[$distributor->id]) ? ' selected="selected"' : '' }} >{{ $distributor->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>Tên</th>
                                                    <th>Quốc gia</th>
                                                    <th>Độc quyền?</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($distributors as $distributor)
                                                    <tr id="d-{{ $distributor->id }}"
                                                        class="{{ !isset($distributorsData[$distributor->id]) ? 'hidden' : '' }} ">
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



                                    <div class="form-group">
                                        <label for="rel" class="control-label text-semibold">Báo cáo của người dùng</label>
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
                                                @if (isset($reports))
                                                <tbody>
                                                    @foreach ($reports as $report)
                                                        <tr>
                                                            <td><input type="checkbox" name="report_resolved[]"
                                                                       value="{{ $report->id }}"/></td>
                                                            <td>{{ $report->icheck_id }}</td>
                                                            <td>{{ $report->note }}</td>
                                                            <td>{{$report->status == 0 ? 'Chờ xử lý' : 'Đã xử lý'}}</td>
                                                            <td>{{ $report->createdAt }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                @endif
                                            </table>
                                    </div>

                                    {{--<div class="form-group {{ $errors->has('status') ? 'has-error has-feedback' : '' }}">--}}
                                        {{--<label for="rel" class="control-label text-semibold">Trạng thái</label>--}}
                                        {{--<i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"--}}
                                           {{--data-content="GTIN sản phẩm liên quan, phân tách bởi dấu ,"></i>--}}
                                        {{--<div class="radio">--}}
                                            {{--<label><input type="radio" name="status"--}}
                                                          {{--value="1" {{ (!isset($product) or @$product->status === 1) ? ' checked="checked"' : '' }}>--}}
                                                {{--Kích hoạt</label>--}}
                                        {{--</div>--}}
                                        {{--<div class="radio">--}}
                                            {{--<label><input type="radio" name="status"--}}
                                                          {{--value="0" {{ @$product->status === 0 ? ' checked="checked"' : '' }}>--}}
                                                {{--Không Kích hoạt</label>--}}
                                        {{--</div>--}}
                                        {{--@if ($errors->has('status'))--}}
                                            {{--<div class="form-control-feedback">--}}
                                                {{--<i class="icon-notification2"></i>--}}
                                            {{--</div>--}}
                                            {{--<div class="help-block">{{ $errors->first('status') }}</div>--}}
                                        {{--@endif--}}




                                    {{--</div>--}}
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

<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/daterangepicker.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/picker.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/picker.date.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/picker.time.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/legacy.js') }}"></script>
@endpush

@push('scripts_foot')
<script>
    $(document).ready(function () {


        $('#date_validity').pickadate({
            format: 'yyyy-mm-dd'
        });
        $('#expiration_date').pickadate({
            format: 'yyyy-mm-dd'
        });

        $('.btn-xoa').click(function(e){
            if(confirm('Bạn có chắn chắn XÓA SẢN PHẨM NÀY trên hệ thống, Hành động này sẽ KHÔNG THỂ hoàn tác!!')){

            }else{
                e.preventDefault();
            }

        });
        $(".js-help-icon").popover({
            html: true,
            trigger: "hover",
            delay: {"hide": 1000}
        });

        $('#gtin_code').on('focusout', function () {
            var gtin = $(this).val();

            if (!Barcoder.validate(gtin)) {
                alert("Cảnh báo: Barcode vừa nhập vào không đúng  định dạng");

            }
        });

        $('#keywords').select2({
            tags: true,
            tokenSeparators: [','],
            placeholder: "Add your tags here"
        });


        $('#a-multiselect').multiselect({
            enableCaseInsensitiveFiltering: true,
            enableFiltering: true,
            onChange: function (a, b) {
                var id = '#a-' + $(a).val();

                $(id).toggleClass('hidden', !b);
                $.uniform.update();
            }
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
                $('#images').append(' <Div class="col-md-2"> <div class="thumb"> <img src="' + data.url + '" alt=""> <div class="caption-overflow"> <span> <a href="' + data.url + '" class="btn bg-teal-300 btn-rounded btn-icon" data-popup="lightbox"><i class="icon-zoom-in"></i></a> <a href="#" class="btn bg-teal-300 btn-rounded btn-icon btn-remove-image"><i class="icon-cancel-circle"></i></a> </span> </div> </div> <div class="radio"><label><input type="radio" name="image_default" value="' + data.prefix + '"> Ảnh đại diện</label></div><input type="hidden" name="images[]" value="' + data.prefix + '"> </Div>');
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
            $('#images').append(' <Div class="col-md-2"> <div class="thumb"> <img src="' + data.url + '" alt=""> <div class="caption-overflow"> <span> <a href="' + data.url + '" class="btn bg-teal-300 btn-rounded btn-icon" data-popup="lightbox"><i class="icon-zoom-in"></i></a> <a href="#" class="btn bg-teal-300 btn-rounded btn-icon btn-remove-image"><i class="icon-cancel-circle"></i></a> </span> </div> </div> <div class="radio"><label><input type="radio" name="image_default" value="' + data.prefix + '"> Ảnh đại diện</label></div><input type="hidden" name="images[]" value="' + data.prefix + '"> </Div>');
            $('[data-popup="lightbox"]').fancybox({
                padding: 3
            });
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
            closeOnSelect: false,
            dropdownCssClass: 'border-primary',
            containerCssClass: 'border-primary text-primary-700'
        });


        $('.js-categories-select').on("select2:select", function(e) {
            var id_select = e.params.data.id;
            var url = '{{route('Ajax::Staff::Management::product2@getAttributesByCategory')}}';
            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                data: {
                    id : id_select
                },
                dataType:'json',
                success: function (data) {

                    $.each(data, function(index, value) {
                        if($('#'+index).length > 0){
                            var count = parseInt($('#'+index).attr('data-count'));
                            $('#'+index).attr('data-count',count+1);
                        }else{
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

        $('.js-categories-select').on("select2:unselect", function(e) {
            var id_categories = $(this).val();
            var attr_id = e.params.data.element.attributes.getNamedItem('data-attr').value;
            if(attr_id){
                attr_id = attr_id.split(',');
                attr_id.forEach(function(id){
                    if($('#'+id).length > 0){
                        var count = parseInt($('#'+id).attr('data-count'));
                        if(count < 2){
                            $('#'+id).remove();
                        }else{
                            $('#'+index).attr('data-count',count-1);
                        }

                    }

                });
            }

        });

            //get attributes
        @if(isset($product))
        var selected = $('.js-categories-select').val();
        $.ajax({
            type: "POST",
            url: '{{route('Ajax::Staff::Management::product2@getAttributesByProduct')}}',
            headers: {
                'X-CSRF-Token': "{{ csrf_token() }}"
            },
            data: {
                selected : selected,
                id : '{{$product->id}}'
            },
            dataType:'json',
            success: function (data) {
                $.each(data, function(index, value) {

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

        @foreach ($attributes as $attr)
        CKEDITOR.replace('attr-{{ $attr->id }}', {
            extraPlugins: 'forms'
        });
        @endforeach


        // Image lightbox
        $('[data-popup="lightbox"]').fancybox({
            padding: 3
        });

        $(document).on('click', '.btn-remove-image', function (e) {
            e.preventDefault();

            $(this).parents('.col-md-2').remove();
        });

    });

    $(document).ready(function(){
        $('#add-new').click(addNewMember);
        $('#remove-new').click(removeMember);
    });
    @if(isset($videos))
     var member_next_id = {{count($videos)}};
    @else
    var member_next_id = 0;
    @endif

    function addNewMember(){
        var member_id = "video" + member_next_id;
        var html = '<input type="text" class="form-control f3" id="'+ member_id + '" name="videos[]" placeholder="Add Link of video"><a href="#" onclick="removeMember(' + member_next_id + ')">Xóa</a>';
        $('#members-div').append(html);
        member_next_id++;
    }

    function removeMember(id){
        var member_sel = "#video" + id;
        $('#members-div').find(member_sel).next().remove();
        $('#members-div').find(member_sel).remove();
    }


</script>
@endpush
