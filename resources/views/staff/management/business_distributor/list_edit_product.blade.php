@extends('_layouts/staff')

@section('content')
    <!-- Page header -->
    <style>
        .table>thead>tr>th{
            padding:5px 5px !important;
            text-align: center;
        }
        .properties-block label{
            word-wrap: break-word;
            padding-top: 8px;
            padding-bottom: 8px;
        }
        input{
            border-bottom-color: #bbb !important;
        }

    </style>
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Đăng kí sửa sản phẩm</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                    <button type="button" class="btn btn-primary" id="select-all">Chọn tất cả</button>
                    <button type="button" class="btn btn-default" id="unselect-all">Bỏ Chọn tất cả</button>
                    <button type="submit" class="btn btn-danger" id="approve-all">Chấp nhận</button>
                    {{--<button type="submit" class="btn btn-danger" id="disapprove-all">Không chấp nhận</button>--}}
                    <button type="button" class="btn btn-link bt-warning" data-toggle="modal" data-target="#disapproveall-modal" >Không chấp nhận </button>
                    {{--<a href="#" class="btn btn-link " id="destroy"><i class="icon-trash"></i> Xoá</a>--}}
                </div>
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
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert bg-danger alert-styled-left">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        {{ session('error') }}
                    </div>
                @endif
                <form id="main-form" method="POST">
                    {{ csrf_field() }}
                    <div class="panel panel-flat">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Tên công ty</th>
                                <th>Tên sản phẩm</th>
                                <th>Barcode</th>
                                <th>Ảnh</th>
                                <th>Giá</th>
                                <th>Danh mục</th>
                                <th>Thuộc tính</th>
                                <th>Thông tin sản phẩm</th>
                                <th>Chứng chỉ và chứng nhận</th>
                                <th>Phân biệt thật giá</th>

                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach ($products as $index => $product)
                                <tr role="row" id="product-{{ $product->id }}">
                                    <td><input type="checkbox" name="selected[]" class="js-checkbox s" value="{{ $product->id }}"/></td>
                                    <td>{{ $product->business->name }}</td>

                                    <td><textarea type="text" class="form-control pname editable"
                                               data-url="{{route('Staff::Management::businessDistributor@inline', [$product->id])}}"
                                               data-id="{{$product->id}}" data-attr="product_name"
                                              >{{ $product->product_name }}</textarea></td>
                                    <td>
                                        <?php
                                        try {
                                            echo DNS1D::getBarcodeSVG(trim($product->gtin_code ), "EAN13");
                                        } catch (\Exception $e) {
                                            echo $e->getMessage();
                                        }
                                        ?>
                                        {{ $product->gtin_code }}
                                    </td>
                                    <td>
                                        <ul class="aimages list-inline">
                                            @if(!empty($product->image))


                                             @foreach(json_decode($product->image) as $image)

                                                    <li>
                                                        <li id="product-image"><a href="{{ get_image_url($image) }}" data-image="{{$image}}" data-url="{{route('Staff::Management::businessDistributor@inline', [$product->id])}}"  class="aimage" data-image="{{$image}}" target="_blank"><img src="{{ get_image_url($image) }}" width="50" /></a>
                                                    </li>
                                                @endforeach



                                            @endif

                                        </ul>
                                        {{--<input type="file" class="fileaaa" data-url="{{route('Staff::Management::businessDistributor@inline', [$product->id])}}" style="display:none" />--}}
                                        {{--<a href="#" class="addFile">Thêm</a>--}}
                                    </td>

                                    <td><input type="text" class="form-control pprice editable" data-url="{{route('Staff::Management::businessDistributor@inline', [$product->id])}}" data-id="{{$product->id}}" data-attr="price" value="{{$product->price}}" /></td>

                                    <td style="width: 300px;">
                                        @php
                                            $selectedCategories = [];
                                              if(json_decode($product->categories)){

                                               if(is_array(json_decode($product->categories))){
                                            $selectedCategories = json_decode($product->categories);
                                               }
                                          }
                                        @endphp
                                        <select  id="country" name="categories[]" multiple="multiple"
                                                 class="editable select-border-color border-warning js-categories-select"
                                                 data-attr="categories"
                                                 data-url="{{route('Staff::Management::businessDistributor@inline', [$product->id])}}"
                                                    data-product ="{{$product->id}}">
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
                                    </td>
                                    {{--properties--}}
                                    <td id="properties{{$product->id}}" class="properties-block" data-id="{{$product->id}}">
                                        <div style="min-width: 200px"></div>
                                        {!! $product->renderProperties !!}

                                    </td>

                                    <td>
                                        <textarea class="form-control editable" name="attr-1" data-url="{{route('Staff::Management::businessDistributor@inline', [$product->id])}}"  data-attr="attr-1">{{$product->getAttr(1)}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control editable" name="attr-2" data-url="{{route('Staff::Management::businessDistributor@inline', [$product->id])}}"  data-attr="attr-2">{{$product->getAttr(2)}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control editable" name="attr-4" data-url="{{route('Staff::Management::businessDistributor@inline', [$product->id])}}"  data-attr="attr-4">{{$product->getAttr(4)}}</textarea>
                                    </td>
                                    <td>
                                        {{$product->statustext}}
                                    </td>
                                    <td>
                                        {{$product->createdAt}}
                                    </td>

                                    <td>
                                        <div class="dropdown">
                                            <button id="product-{{ $product->id }}-actions" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link">
                                                <i class="icon-more2"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="product-{{ $product->id }}-actions">
                                                <li><a href="#" data-toggle="modal" data-target="#approve-modal" data-name="{{ $product->id }}" data-id="{{ $product->id }}" data-approve-url="{{ route('Staff::Management::businessDistributor@approveEdit', ['id' => $product->id]) }}"><i class="icon-checkmark-circle2"></i> Chấp nhận</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#disapprove-modal" data-name="{{ $product->id }}" data-disapprove-url="{{ route('Staff::Management::businessDistributor@disapproveEdit', ['id' => $product->id]) }}"><i class="icon-blocked"></i> Không chấp nhận</a></li>

                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {!! $products->links() !!}
                    </div>
                </form>
            </div>
            <!-- /main content -->
        </div>
        <!-- /page content -->
    </div>
    <!-- /page container -->


    <div class="modal fade" id="approve-modal" tabindex="-1" role="dialog" aria-labelledby="approve-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="approve-modal-label">Chấp nhận đăng tải Sản phẩm</h4>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            Bạn có chắc chắn chấp nhận cho doanh nghiệp phân phối sản phẩm?
                        </div>
                        <div class="form-group">
                            <label for="reason" class="control-label text-semibold">Lý do</label>
                            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do bạn chấp nhận đăng tải sản phẩm này lên hệ thống cảu iCheck"></i>
                            <textarea id="reason" name="reason" rows="5" cols="5" class="form-control" placeholder="Lý do"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{ csrf_field() }}

                        <input type="hidden" name="_method" value="POST">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
                        <button type="submit" class="btn btn-danger">Xác nhận</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="disapprove-modal" tabindex="-1" role="dialog" aria-labelledby="disapprove-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="disapprove-modal-label">Không chấp nhận cho doanh nghiệp phân phối sản phẩm ?</h4>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="reason" class="control-label text-semibold">Lý do</label>
                            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do bạn chấp nhận đăng tải sản phẩm này lên hệ thống cảu iCheck"></i>
                            <textarea id="reason" name="reason" rows="5" cols="5" class="form-control" placeholder="Lý do"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="POST">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
                        <button type="submit" class="btn btn-danger">Xác nhận</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="disapproveall-modal" tabindex="-1" role="dialog" aria-labelledby="disapprove-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="disapprove-modal-label">Không chấp nhận đăng tải Sản phẩm</h4>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="reason" class="control-label text-semibold">Lý do</label>
                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do bạn chấp nhận đăng tải sản phẩm này lên hệ thống cảu iCheck"></i>
                        <textarea id="reasonall" name="reason" rows="5" cols="5" class="form-control" placeholder="Lý do"></textarea>
                    </div>
                </div>
                <div class="modal-footer">

                    {{--<input type="hidden" name="_method" value="PUT">--}}
                    <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
                    <button type="submit" id="dissapproveall-button" class="btn btn-danger">Xác nhận</button>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('js_files_foot')
<script type="text/javascript" src="{{ asset('https://cdn.jsdelivr.net/jsbarcode/3.3.14/JsBarcode.all.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
@endpush

@push('scripts_foot')
<script>
    $('#approve-all').on('click', function () {
        if(confirm('Bạn có chắc chắn thực hiện hành động này?')){
            $('#main-form').attr('action', '{{ route('Staff::Management::businessDistributor@approveListEdit') }}').submit();
        }
    });

    $('#dissapproveall-button').on('click',function(){
        var reason = $('#reasonall').val();
        $('#reasonall-form').val(reason);
        $('#main-form').attr('action', '{{ route('Staff::Management::businessDistributor@disapproveListEdit') }}').submit();
    });

    $(".js-help-icon").popover({
        html: true,
        trigger: "hover",
        delay: { "hide": 1000 }
    });

    $('#select-all').on('click', function () {
        $('.s').prop('checked', true);
    });

    $('#unselect-all').on('click', function () {
        $('.s').prop('checked', false);
    });

    $('#delete-modal').on('show.bs.modal', function (event) {
        var $btn = $(event.relatedTarget),
                $modal = $(this);

        $modal.find('form').attr('action', $btn.data('delete-url'));
        $modal.find('.js-product-name').text($btn.data('name'));
    });

    $('#approve-modal').on('show.bs.modal', function (event) {
        var $btn = $(event.relatedTarget),
                $modal = $(this);

        $modal.find('form').attr('action', $btn.data('approve-url'));
        $modal.find('.js-product-name').text($btn.data('name'));
    });

    $('#disapprove-modal').on('show.bs.modal', function (event) {
        var $btn = $(event.relatedTarget),
                $modal = $(this);

        $modal.find('form').attr('action', $btn.data('disapprove-url'));

    });
    $('#destroy').on('click',function(event){
        if(confirm('Bạn có chắc chắn muốn xóa !!')){
            var checked = '';
            $('.s').each(function(){
                if(this.checked){
                    checked = $(this).val() + ',' + checked;
                }

            });

            $.ajax({
                url : "{{route('Ajax::Staff::Management::product@destroy')}}",
                data : {ids : checked},
                type : 'get',
                dataType: "text",
                success:function(data){
                    if(data == 'oke'){
                        location.reload();
                    }
                }
            });
        }
    });
    //$(".js-checkbox").uniform({ radioClass: "choice" });

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



    $(document).on('select2:select','.js-categories-select',function(e){
        var id_select = e.params.data.id;
        var product_id = $(this).attr('data-product');
        var urladdAttrInline = '{{route('Ajax::Staff::Management::businessDistributor@addAttrInline')}}';
        $.ajax({
            type: "POST",
            url: urladdAttrInline,
            headers: {
                'X-CSRF-Token': "{{ csrf_token() }}"
            },
            data: {
                id : id_select,
                product_id:product_id
            },
            dataType:'json',
            success: function (data) {
                $.each(data, function(index, value) {
                    if($('#'+product_id+index).length > 0){
                        var count = parseInt($('#'+product_id+index).attr('data-count'));
                        $('#'+product_id+index).attr('data-count',count+1);
                    }else{
                        $('#properties'+product_id).append(value);
                        $('.js-attr').select2({
                            dropdownCssClass: 'border-primary',
                            containerCssClass: 'border-primary text-primary-700'
                        });
                    }

                });

            },
            error: function () {
                alert('Lỗi, hãy thử lại sau');
            }
        });
    });


    $(document).on('change','.properties-product',function(e){
        var value = $(this).val();
        var attr_id = $(this).parent().parent().attr('data-id');
        var product_id = $(this).parent().parent().parent().attr('data-id');
        var urlInline = '{{route('Ajax::Staff::Management::businessDistributor@updateAttrInline')}}';
        $.ajax({
            type: "POST",
            url: urlInline,
            headers: {
                'X-CSRF-Token': "{{ csrf_token() }}"
            },
            data: {
                value: value,
                attr_id:attr_id,
                product_id:product_id
            },
            success: function (data) {
                if(data.delete){
                    $('#'+product_id+attr_id).remove();
                }

            },
            error: function () {
                alert('Lỗi, hãy thử lại sau');
            }
        });
    });



    $('.addFile').on('click', function (e) {
        e.preventDefault();
        $(this).prev().trigger('click');
    });
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
        console.log(oldData);
    });

    $(document).on('blur', '.editable', function () {

        var $this = $(this);
        var id = $this.data('id');
        var attr = $this.data('attr');

        var newVal = $this.val();
        var url = $this.data('url');

        if (newVal !== oldData[id][attr]) {
            var data = {};
            if (attr === "product_name") {
                data = {
                    "product_name": newVal
                };
            } else if (attr === "price") {
                data = {
                    "price": newVal
                };
            }else if (attr === "attr-1") {
                data = {
                    "attr-1": newVal
                };
            }else if (attr === "attr-2") {
                data = {
                    "attr-2": newVal
                };
            }else if (attr === "attr-4") {
                data = {
                    "attr-4": newVal
                };
            }
            console.log(data);

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


    $('.js-categories-select').change(function(){
        var categories = $(this).val();
        var url = $(this).data('url');

        $.ajax({
            type: "POST",
            url: url,
            headers: {
                'X-CSRF-Token': "{{ csrf_token() }}"
            },
            data: {
                categories : JSON.stringify(categories)
            },
            success: function () {
            },
            error: function () {
                alert('Lỗi, hãy thử lại sau');
            }
        });
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
            cache:false,
            contentType: false,
            processData: false,
            success:function(data){

                var images = '';
                images=data.prefix;

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
                    if($('#product-image').length > 0){

                        $('#product-image').html('<a href="' + data.url + '" class="aimage" data-image="' + data.prefix + '" target="_blank"><img src="' + data.url + '" width="50" /></a><a href="#" class="rmfile">x</a>');
                    }else{
                        $this.prev('.aimages').append('<li id="product-image"><a href="' + data.url + '" class="aimage" data-image="' + data.prefix + '" target="_blank"><img src="' + data.url + '" width="50" /></a><a href="#" class="rmfile">x</a></li>');
                    }
//
                    },
                    error: function () {
                        alert('Lỗi, hãy thử lại sau');
                    }
                });
            },
            error: function(data){
                alert('Loi roi aaaaa!')
            }
        });
    });

    $(document).on('click', '.rmfile', function (e) {
        e.preventDefault();
        var $this = $(this);

        var $this2 = $(this).parents('td').find('.fileaaa');
        var url = $this2.data('url');

        $this.parents('li').remove();


        var images = 'del';

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

    if ($(".barcode").length > 0){
        JsBarcode(".barcode").init();
    }
</script>
@endpush



