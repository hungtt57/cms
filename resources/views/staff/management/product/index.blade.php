@extends('_layouts/staff')

@section('content')
  <style>
    .properties-block label{
      word-wrap: break-word;
    }
    .temp{
      min-width:200px;
    }
    .col-md-6 label{
      padding-top:8px;
      padding-bottom:8px;

    }
    /*.table>thead>tr>th{*/
      /*padding: 12px 20px;*/
      /*display: inline-block;*/
    /*}*/
  </style>
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>Sản phẩm</h2>
      </div>

      <div class="heading-elements">
        <div class="heading-btn-group">
          <button type="button" class="btn btn-primary" id="select-all">Chọn tất cả</button>
          <button type="button" class="btn btn-default" id="unselect-all">Bỏ Chọn tất cả</button>

            <button type="submit" class="btn btn-danger" id="delete-all">Chấp nhận</button>
          <button type="button" class="btn btn-link bt-warning" data-toggle="modal" data-target="#disapproveall-modal" >Không chấp nhận </button>
          <a href="{{ route('Staff::Management::product@add') }}" class="btn btn-link"><i class="icon-add"></i> Thêm Sản phẩm</a>
          <a href="#" class="btn btn-link " id="destroy"><i class="icon-trash"></i> Xoá</a>
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

          <!-- Search Form -->
          <form role="form">

            <!-- Search Field -->
            <div class="row">
              <div class="form-group">
                <div class="input-group">
                  <input class="form-control" type="text" name="gln" placeholder="Search by GLN" required value="{{ Request::input('gln') }}" />
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-success btn-xs" data-toggle="modal" data-target="#edit-pro">Search</button>

                  </span>
                </div>
              </div>
            </div>

          </form>
          <!-- End of Search Form -->

        @if (session('success'))
          <div class="alert bg-success alert-styled-left">
            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
            {{ session('success') }}
          </div>
        @endif
<form id="main-form" method="POST">
{{ csrf_field() }}
  <input type="hidden" name="reason" id="reasonall-form">
        <div class="panel panel-flat">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th><input type="checkbox" id="checkbox-all" class="js-checkbox" /></th>
                  <th>Tên</th>
                  <th>Hình ảnh</th>
                  <th>Barcode</th>
                  <th>Nhà sản xuất</th>
                  <th>Giá</th>
                  <th width="500px">Danh mục</th>
                  <th>Thuộc tính</th>
                  {{--<th>Thông tin</th>--}}
                  {{--<th>Cảnh báo</th>--}}
                  {{--<th>Trạng thái</th>--}}
                  <th>Ngày cập nhật</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>

                @foreach ($products as $index => $product)
                  <tr role="row" id="product-{{ $product->id }}">
                    <td><input type="checkbox" name="selected[]" class="js-checkbox s" value="{{ $product->id }}" /></td>
                    <td><textarea type="text" class="form-control editable"  data-url="{{route('Staff::Management::product@inline', [$product->id])}}" data-id="{{$product->id}}" data-attr="name" >{{ $product->name }}</textarea></td>

                    <td>
                      @if($product->image)
                        @if(is_array(json_decode($product->image,true)))
                        @foreach(json_decode($product->image,true) as $image)

                          <img width="150" height="150" src="{{ get_image_url($image, 'thumb_small') }}" />
                          @endforeach
                        @endif
                      @endif

                    </td>
                    <td>
                      <?php
                      try {
                        echo DNS1D::getBarcodeSVG(trim($product->barcode), "EAN13");
                      } catch (\Exception $e) {
                        echo $e->getMessage();
                      }
                      ?>
                        {{ $product->barcode }}

                    </td>
                    <td>{{ @$product->gln->name }}({{@$product->gln->gln}})</td>
                    <td><input type="text" class="form-control pprice editable" data-url="{{route('Staff::Management::product@inline', [$product->id])}}" data-id="{{$product->id}}" data-attr="price" value="{{$product->price}}" /></td>

                    <td style="width: 500px !important;">
                      <select  id="country" name="categories[]" multiple="multiple"
                               class="editable select-border-color border-warning js-categories-select"
                               data-attr="categories"
                               data-url="{{route('Staff::Management::product@inline', [$product->id])}}"
                                data-product="{{$product->id}}">
                        @php $selectedCategories =  $cat[$product->id] @endphp

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
                    <td id="properties{{$product->id}}" class="properties-block" data-id="{{$product->id}}">
                      <div class="temp"></div>
                      {!! $product->renderProperties !!}





                    </td>

                    {{--<td> <textarea class="form-control editable" name="attr-1" data-url="{{route('Staff::Management::product@inline', [$product->id])}}"  data-attr="attr-1">@if(isset($product->attrs['1'])){{$product->attrs['1']}} @endif</textarea> </td>--}}
                    <td>{{ $product->updated_at }}</td>
                    <td>
                      <div class="dropdown">
                        <button id="product-{{ $product->id }}-actions" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link">
                          <i class="icon-more2"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="product-{{ $product->id }}-actions">
                          <li><a href="#" data-toggle="modal" data-target="#approve-modal" data-name="{{ $product->name }}" data-id="{{ $product->id }}" data-approve-url="{{ route('Staff::Management::product@approve', [$product->id]) }}"><i class="icon-checkmark-circle2"></i> Chấp nhận</a></li>
                          <li><a href="#" data-toggle="modal" data-target="#disapprove-modal" data-name="{{ $product->name }}" data-disapprove-url="{{ route('Staff::Management::product@disapprove', [$product->id]) }}"><i class="icon-blocked"></i> Không chấp nhận</a></li>
                          <li><a href="{{ route('Staff::Management::product@edit', [$product->id]) }}"><i class="icon-pencil5"></i> Sửa</a></li>
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

<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="delete-modal-label">Xoá Sản phẩm</h4>
      </div>
      <div class="modal-body">
        Bạn có chắc chắn muốn xoá Sản phẩm <strong class="text-danger js-product-name"></strong> khỏi hệ thống của iCheck?
      </div>
      <div class="modal-footer">
        <form method="POST">
          {{ csrf_field() }}
          <input type="hidden" name="_method" value="DELETE">
          <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
          <button type="submit" class="btn btn-danger">Xác nhận</button>
        </form>
      </div>
    </div>
  </div>
</div>

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
            Bạn có chắc chắn chấp nhận đăng tải Sản phẩm <strong class="text-danger js-product-name"></strong> lên hệ thống của iCheck?
          </div>
          <div class="form-group">
            <label for="reason" class="control-label text-semibold">Lý do</label>
            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do bạn chấp nhận đăng tải sản phẩm này lên hệ thống cảu iCheck"></i>
            <textarea id="reason" name="reason" rows="5" cols="5" class="form-control" placeholder="Lý do"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          {{ csrf_field() }}
          <input type="hidden" name="selected[]" class="n-ids" value="">
          <input type="hidden" name="_method" value="PUT">
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
        <h4 class="modal-title" id="disapprove-modal-label">Không chấp nhận đăng tải Sản phẩm</h4>
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
          <input type="hidden" name="_method" value="PUT">
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
    $('#select-all').on('click', function () {
      console.log(1);
      $('.s').prop('checked', true);
    });

    $('#checkbox-all').on('click', function () {

      $('.s').not(this).prop('checked', this.checked);
    });
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

      var urladdAttrInline = '{{route('Ajax::Staff::Management::product@addAttrInline')}}';
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
//        updateAttrInline
      var urlInline = '{{route('Ajax::Staff::Management::product@updateAttrInline')}}';
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

    $('.js-attr').select2({
      dropdownCssClass: 'border-primary',
      containerCssClass: 'border-primary text-primary-700'
    });














  $('#delete-all').on('click', function () {
    $('#main-form').attr('action', '{{ route('Staff::Management::product@approve') }}').submit();
  });

  $(".js-help-icon").popover({
    html: true,
    trigger: "hover",
    delay: { "hide": 1000 }
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
    $modal.find('.n-ids').val($btn.data('id'));
    $modal.find('.js-product-name').text($btn.data('name'));
  });

  $('#disapprove-modal').on('show.bs.modal', function (event) {
    var $btn = $(event.relatedTarget),
        $modal = $(this);

    $modal.find('form').attr('action', $btn.data('disapprove-url'));
    $modal.find('.js-product-name').text($btn.data('name'));
  });

    $('#dissapproveall-button').on('click',function(){
        var reason = $('#reasonall').val();
      $('#reasonall-form').val(reason);
      $('#main-form').attr('action', '{{ route('Staff::Management::product@disapproveAll') }}').submit();
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
      }else if (attr === "attr-1") {
        data = {
          "attr-1": newVal
        };
      };

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



