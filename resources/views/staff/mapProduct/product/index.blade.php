@extends('_layouts/staff')

@section('content')
    <style>
        .message {
            display: block;
            width: 150px;
            padding: 10px;
            background-color: #4CAF50;
            text-align: center;
            color: white;
            float: right;
            cursor: pointer;
        }
        .td-image{
            width:300px;
        }
        .main-map-product{
            background-color:#DCF2FA;
        }
    </style>
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Map sản phẩm</h2>
            </div>
            <div class="heading-elements">
                <button class="btn btn-info" id="button-map">Map</button>
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
                <!-- Search Form -->
                <div class="row" style="margin-bottom:20px">
                    <form role="form">
                        <div class="col-md-4">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="{{ Request::input('name') }}" placeholder="Nhập tên">
                        </div>
                        <div class="col-md-4">
                            <label>Gtin</label>
                            <input type="text" name="gtin" class="form-control" value="{{ Request::input('gtin') }}" placeholder="Nhập gtin">
                        </div>
                        {{--<div class="col-md-4">--}}
                            {{--<label>GLN</label>--}}
                            {{--<input type="text" name="gln" class="form-control" value="{{ Request::input('gln') }}" placeholder="Nhập gln">--}}
                        {{--</div>--}}


                        {{--<div class="col-md-4">--}}
                            {{--<label class="col-md-3">Điều kiện</label>--}}
                            {{--<div class="col-md-9">--}}
                            {{--<select id="country" name="condition[]" multiple="multiple"--}}
                                    {{--class="select-border-color border-warning js-categories-select">--}}
                                {{--@foreach ($conditions as $key => $condition)--}}
                                    {{--<option value="{{$key}}"--}}
                                            {{--{{ ((!empty($selectedCondition)) and in_array($key, $selectedCondition)) ? ' selected="selected"' : '' }}--}}
                                    {{-->{{ $condition }}</option>--}}
                                {{--@endforeach--}}
                            {{--</select>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        <div class="col-md-4">
                            <label >Sản phẩm craw </label>
                                <select id="country" name="crawCondition"
                                        class="select-border-color border-warning js-categories-select">
                                    <option value="0">Tất cả</option>
                                    <option value="1" @if(Request::input('crawCondition') == 1) selected @endif>Theo barcode</option>
                                    <option value="2" @if(Request::input('crawCondition') == 2) selected @endif>Theo tên</option>
                                </select>


                        </div>

                        <div class="col-md-2" style="padding-top:20px">
                            <button type="submit" class="btn btn-success btn-xs">Search</button>
                        </div>
                    </form>
                </div>
                <!-- End of Search Form -->
                @if (session('success'))
                    <div class="alert bg-success alert-styled-left">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->has())
                    <div class="alert bg-danger alert-styled-left">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert bg-danger alert-styled-left">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        {!! session('error')  !!}
                    </div>
                @endif

                <div class="panel panel-flat">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all" class="js-checkbox"/></th>
                            <th>Barcode</th>
                            <th>Tên</th>
                            <th>Hình ảnh</th>
                            <th>Giá</th>
                            <th>Thông tin</th>
                            <th></th>
                        </tr>
                        </thead>

                        @if(!empty($products))
                            <form action="{{route('Staff::mapProduct::product@mapList')}}" method="POST" id="main-form">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                @foreach ($products as $product)
                                    <tbody>
                                    <tr role="row" id="product-{{ $product->id }}" class="main-map-product">
                                        <td>
                                            @if($product->map_product)
                                                <input type="checkbox" name="selected[]" class="s"
                                                       value="{{ $product->id }}">
                                            @endif
                                        </td>
                                        <td>
                                            {{$product->gtin_code}}
                                        </td>
                                        <td style="width:100px">{{ $product->product_name }}</td>
                                        <td>
                                            @if($product->image_default)
                                                <img width="50" height="50"
                                                     src="{{ get_image_url($product->image_default, 'thumb_small') }}"/>
                                            @endif
                                        </td>
                                        <td>
                                            {{$product->price_default}} {{($product->currency) ? $product->currency->symbol : 'đ'}}
                                        </td>
                                        <td></td>
                                        <td>
                                            @if($product->map_product)
                                            <div class="message message{{$product->id}}" data-id="{{$product->id}}"></div>
                                                @endif
                                        </td>
                                    </tr>
                                </tbody>
                                    @if($product->map_product)
                                        <tbody class="list_map_product list_mproduct{{$product->id}}" data-id="{{$product->id}}">
                                                    @foreach($product->map_product as $mProduct)
                                                        <tr class="tr-map">
                                                            <td></td>
                                                            <td>
                                                                <input type="radio" class="radio-map"
                                                                       name="list_map_product[{{$product->id}}]"
                                                                       value="{{$mProduct['id']}}">
                                                            </td>
                                                            <td style="word-break: break-all;min-width:300px">
                                                                <textarea class="form-control name editable"
                                                                       data-url="{{route('Staff::mapProduct::product@inline', ['id' => $mProduct['id'],'productId' => $product->id])}}"
                                                                       data-id="{{$mProduct['id']}}" data-attr="name" >{{$mProduct['text']}}</textarea>
                                                            </td>
                                                            <td class="td-image">
                                                                <ul class="aimages list-inline">
                                                                    @php $images = json_decode($product->attachments, true); @endphp

                                                                    @foreach($mProduct['images'] as $image)
                                                                        <li><a href="{{$image }}" data-image="{{$image}}"  data-url="{{route('Staff::mapProduct::product@inline', ['id' => $mProduct['id'],'productId' => $product->id])}}"  class="aimage"  target="_blank">
                                                                                <img src="{{$image }}" width="50" /></a><a href="#" class="rmfile">x</a>
                                                                    @endforeach

                                                                </ul>
                                                                <input type="file" class="fileaaa"  data-url="{{route('Staff::mapProduct::product@inline', ['id' => $mProduct['id'],'productId' => $product->id])}}" style="display:none" />
                                                                <a href="#" class="addFile">Thêm</a>

                                                            </td>
                                                            <td>
                                                                <textarea class="form-control price editable"
                                                                          data-url="{{route('Staff::mapProduct::product@inline', ['id' => $mProduct['id'],'productId' => $product->id])}}"
                                                                          data-id="{{$mProduct['id']}}" data-attr="price" >{{@$mProduct['price']}}</textarea>
                                                            </td>
                                                            <td style="word-break: break-all;">
                                                                    <textarea class="form-control price editable"
                                                                              data-url="{{route('Staff::mapProduct::product@inline', ['id' => $mProduct['id'],'productId' => $product->id])}}"
                                                                              data-id="{{$mProduct['id']}}" data-attr="description" >{{@$mProduct['description']}}</textarea>
                                                            </td>
                                                            <td>
                                                                <a href="{{$mProduct['url']}}" target="_blank">Link</a>
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                        </tbody>
                                    @endif

                                @endforeach
                            </form>
                        @endif

                    </table>

                </div>
                @if(!empty($products))
                    <div class="row" style="text-align: right">
                        {!! $products->appends(Request::all())->links() !!}
                    </div>

                @endif
            </div>
            <!-- /main content -->
        </div>
        <!-- /page content -->
    </div>




@endsection

@push('js_files_foot')
<script type="text/javascript"
        src="{{ asset('https://cdn.jsdelivr.net/jsbarcode/3.3.14/JsBarcode.all.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
@endpush

@push('scripts_foot')
<script>


    var $ShowHideMore = $('.list_map_product');
    $ShowHideMore.each(function () {
        var id = $(this).attr('data-id');
        var $times = $(this).find('.tr-map');
        if ($times.length > 2) {
            $(this).find('tr:nth-of-type(n+2)').addClass('moreShown').hide();
            $(this).parent().find('div.message'+id).addClass('more-times').html('+ Show more');
        }
    });

    $(document).on('click', 'div.message', function () {
        var that = $(this);
        var id = $(this).attr('data-id');
        var thisParent = $('.list_mproduct'+id);
        if (that.hasClass('more-times')) {
            thisParent.find('.moreShown').show();
            that.toggleClass('more-times', 'less-times').html('- Show less');
        } else {
            thisParent.find('.moreShown').hide();
            that.toggleClass('more-times', 'less-times').html('+ Show more');
        }
    });

    $('#button-map').click(function () {
        if (confirm('Bạn có chắc chắn map sản phẩm')) {
            $('#main-form').submit();
        }
    });
    $(".js-categories-select").select2({
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

    $(".js-checkbox").uniform({radioClass: "choice"});

    $('#select-all').on('click', function () {
        $('.s').prop('checked', this.checked);
        setChecked();
    });
    function setChecked() {
        $('.s').each(function () {
            var checked = this.checked;
            if (checked) {
                $(this).parent().parent().find('.radio-map').first().prop('checked', true);
            } else {
                $(this).parent().parent().find('.radio-map').prop('checked', false);
            }
        });
    }
    $('.s').click(function () {
        var checked = this.checked;
        if (checked) {
            $(this).parent().parent().find('.radio-map').first().prop('checked', true);
        } else {
            $(this).parent().parent().find('.radio-map').prop('checked', false);
        }
    });
    $(".js-example-basic-single").select2();
    $(".js-edit").select2();



    var oldData = {};
    $(document).on('focus', '.editable', function () {
        var $this = $(this);

        var id = $this.data('id');
        var attr = $this.data('attr');
        var old= $this.val();

        if (!oldData[id]) {
            oldData[id] = {};
        }
        oldData[id][attr] = old;
    });

    $(document).on('blur', '.editable', function () {

        var $this = $(this);
        var id = $this.data('id');
        var attr = $this.data('attr');

        var newVal = $this.val();
        var url = $this.data('url');

        if (newVal !== oldData[id][attr]) {
            var data = {};
            if (attr === "name") {
                if(newVal==''){
                    newVal = 'dell-all-1994';
                }
                data = {
                    "name": newVal
                };
            } else if (attr === "price") {
                data = {
                    "price": newVal
                };
            } else if (attr === 'description'){
                data = {
                  'description' : newVal
                };
            }

            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                data: data,
                success: function () {
                },
                error: function () {
                    alert('Lỗi, hãy thử lại sau');
                }
            });
        }
    });
    $('.addFile').on('click', function (e) {
        e.preventDefault();
        $(this).prev().trigger('click');
    });

    $('.fileaaa').on('change', function (e) {
        var $this = $(this);
        var formData = new FormData(this);
        formData.append("file", e.target.files[0]);
        var url = $this.data('url');
        $.ajax({
            type:'POST',
            url: '{{ route('Ajax::Staff::upload@image') }}',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data:formData,
            dataType: 'json',
            cache:false,
            contentType: false,
            processData: false,
            success:function(data){

                var images = [];

                $this.prev('.aimages').find('.aimage').each(function () {
                    images.push($(this).data('image'));
                });

                images.push(data.url);

                $.ajax({
                    type: "POST",
                    url: url,
                    headers: {
                        'X-CSRF-Token': "{{ csrf_token() }}"
                    },
                    data: {
                        "images": images
                    },
                    success: function () {
                        $this.prev('.aimages').append('<li><a href="' + data.url + '" class="aimage" data-image="' + data.prefix + '" target="_blank"><img src="' + data.url + '" width="50" /></a><a href="#" class="rmfile">x</a>');
                    },
                    error: function () {
                        alert('Lỗi, hãy thử lại sau');
                    }
                });
            },
            error: function(data){
                if(data.responseText){
                    alert(JSON.parse(data.responseText).error);
                }else{
                    alert('Loi roi aaaaa!');
                }

            }
        });
    });

    $(document).on('click', '.rmfile', function (e) {
        e.preventDefault();
        var $this = $(this);

        var $this2 = $(this).parents('td').find('.fileaaa');
        var url = $this2.data('url');

        $this.parents('li').remove();


        var images = [];

        $this2.prev('.aimages').find('.aimage').each(function () {
            images.push($(this).data('image'));
        });
        if(images.length == 0){
            images = 'del-all';
        }

        $.ajax({
            type: "POST",
            url: url,
            headers: {
                'X-CSRF-Token': "{{ csrf_token() }}"
            },
            data: {
                "images": images,

            },
            success: function () {
            },
            error: function () {
                alert('Lỗi, hãy thử lại sau');
            }
        });
    });


</script>
@endpush



