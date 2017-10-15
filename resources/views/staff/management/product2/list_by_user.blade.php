@extends('_layouts/staff')

@section('content')
  <style>
    .properties-block label{
      word-wrap: break-word;
    }
    .col-md-6 label{
      padding-top:8px;
      padding-bottom:8px;

    }
  </style>
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>Sản phẩm do người dùng đóng góp</h2>
      </div>

      <div class="heading-elements">
        <div class="heading-btn-group">

          <button type="submit" class="btn btn-danger" id="approve-all">Chấp nhận</button>
          <button type="submit" class="btn btn-danger" id="disapprove-all">Không chấp nhận</button>
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
            <div class="col-md-4">
              <div class="form-group">
                <div class="input-group">
                  <input class="form-control" type="text" name="search" value="{{Request::input('search')}}" placeholder="Search tên hoặc gtin"/>
                </div>
              </div>
            </div>
            <div class="col-md-2">
              <input id="start-date" name="date"
                     type="text" class="form-control" placeholder="Nhập date định dạng dd-mm-yyyy"  value="{{Request::input('date')}}"
              >
            </div>
            <div class="col-md-4">
              <input class="form-control" type="text" name="name" value="{{Request::input('name')}}" placeholder="Nhập tên người đóng góp"/>
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-success btn-xs">Search</button>
            </div>
          <div class="" style="clear:both"></div>

          </form>
          <!-- End of Search Form -->
        @if (session('success'))
          <div class="alert bg-success alert-styled-left">
            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
            {{ session('success') }}
          </div>
        @endif

        <div class="panel panel-flat">
            <table class="table table-hover" id="datatable">
              <thead>
                <tr>
                  <th><input type="checkbox" id="select-all" class="js-checkbox" /></th>
                  <th>Barcode</th>
                  {{--<th width="200">Tên nd đg</th>--}}
                  <th >Tên sản phẩm</th>
                  <th>Giá</th>
                  <th >Hình ảnh đóng góp</th>
                  <th >Nhà sản xuất</th>
                  <th>Hình ảnh đã có</th>
                  <th width="60%">Danh mục</th>
                  <th>Thuộc tính</th>
                  <th >Mô tả</th>
                  <th>Thời gian đóng góp</th>
                  <th>Icheck ID</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
              <form id="main-form" method='POST'>

                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                @foreach ($products as $index => $product)

                  <tr role="row" id="product-{{ $product->id }}">
                    <td>
                      <input type="checkbox" name="selected[]" class="s" value="{{ $product->id }}">
                    </td>
                    <td><A href="https://www.google.com/search?q={{ $product->gtin_code }}" target="_blank">{{ $product->gtin_code }}</A></td>
                    <td><textarea type="text" class="form-control editable"
                                  data-url="{{route('Staff::Management::product2@contributeInline', [$product->id])}}"
                                  data-id="{{$product->id}}" data-attr="product_name" >{{ $product->product_name }}</textarea></td>
                    {{--<td>{{ @$product->data->vendor2->name }}</td>--}}
                    <td><input type="text" class="form-control pprice editable" data-url="{{route('Staff::Management::product2@contributeInline', [$product->id])}}" data-id="{{$product->gtin_code}}" data-attr="price" value="{{$product->price}}" /></td>

                    <td>
                      <ul class="aimages list-inline">
                        @php $images = json_decode($product->attachments, true); @endphp

                        @foreach($images as $image)
                          <li><a href="{{ get_image_url($image['link']) }}" data-image="{{$image['link']}}" data-url="{{route('Staff::Management::product2@contributeInline', [$product->id])}}"  class="aimage" data-image="{{$image['link']}}" target="_blank"><img src="{{ get_image_url($image['link']) }}" width="50" /></a><a href="#" class="rmfile">x</a>
                          @endforeach

                      </ul>
                      <input type="file" class="fileaaa" data-url="{{route('Staff::Management::product2@contributeInline', [$product->id])}}" style="display:none" />
                      <a href="#" class="addFile">Thêm</a>
                    </td>
                    <td>

                      @if($product->vendor())
                        {{$product->vendor()->name}}
                        @else
                        <input type="text" class="form-control gln_code" id="gln_code{{$product->id}}" placeholder="Nhập gln_code"/>
                      @endif
                    </td>
                    <td>

                      @if(count($product->getImageExist())>0)
                        @foreach($product->getImageExist() as $image)

                          <img src="{{$image}}" width="50" />
                          @endforeach
                      @endif
                    </td>
                      <td >
                        <div style="min-width: 250px;"></div>
                        @php if( $product->categories){
                         $selectedCategories = json_decode($product->categories,true);

                          }else {
                        $selectedCategories =[];
                        }
                                @endphp
                        <select  id="country" name="categories[]" multiple="multiple"
                                 class="editable select-border-color form-control border-warning js-categories-select"
                                   data-attr="categories"
                                 data-url="{{route('Staff::Management::product2@contributeInline', [$product->id])}}"
                                 data-product="{{$product->id}}"
                        >
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
                      <div style="min-width: 250px"></div>
                      {!! $product->renderProperties !!}



                    </td>
                    <td>
                      <textarea class="form-control editable ckeditor" name="attr-1" data-url="{{route('Staff::Management::product2@contributeInline', [$product->id])}}"  data-attr="description">{{$product->getAttr()}}</textarea>
                    </td>


                    <td>{{ $product->createdAt }}</td>
                    <td><a href="{{route('Staff::Management::product2@listByUser',['name' => $product->icheck_id])}}">{{$product->icheck_id}}</a></td>
                    <td>
                      <div class="dropdown">
                        <button id="product-{{ $product->id }}-actions" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link">
                          <i class="icon-more2"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="product-{{ $product->id }}-actions">
                          <!-- <li><a href="{{ route('Staff::Management::product2@editByField', ['gtin' => $product->gtin_code]) }}" class="editByUser"><i class="icon-pencil5"></i> Sửa</a></li> -->
                          <li><a href="{{ route('Staff::Management::product2@approveByUser', [$product->id]) }}" data-id="{{$product->id}}" class="approveByUser" >Chấp nhận</a></li>
                          <li><a href="{{ route('Staff::Management::product2@ignoreByUser', [$product->id]) }}" class="ignoreByUser">Không chấp nhận</a></li>
                          <!-- <li><a href="{{ route('Staff::Management::product2@removeByUser', [$product->gtin_code]) }}" class="removeByUser">Xoá thông tin</a></li> -->
                        </ul>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </form>
              </tbody>
            </table>
            {!! $products->appends(Request::all())->links() !!}
        </div>
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
@endsection

@push('js_files_foot')
  <script type="text/javascript" src="{{ asset('https://cdn.jsdelivr.net/jsbarcode/3.3.14/JsBarcode.all.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/daterangepicker.js') }}"></script>
{{--<script src="//cdn.ckeditor.com/4.5.10/full/ckeditor.js"></script>--}}
@endpush

@push('scripts_foot')
  <script>

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
      var urlInline = '{{route('Ajax::Staff::Management::product@updateAttrInlineContribute')}}';
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

  $(".js-help-icon").popover({
    html: true,
    trigger: "hover",
    delay: { "hide": 1000 }
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
    $modal.find('.js-product-name').text($btn.data('name'));
  });

  $(".js-checkbox").uniform({ radioClass: "choice" });

  $('.ignoreByUser').on('click', function (e) {
    e.preventDefault();

    var $tr = $(this).parents('tr');

    var r = confirm("Bạn có chắc chắn muốn bỏ qua sản phẩm này?");

    if (r == true) {
      $.ajax({
        url: $(this).attr('href'),
        success: function () {
          $tr.addClass('danger');

          setTimeout(function () {
            $tr.remove();

            if (!$('#datatable > tbody > tr').length) {
              window.location.reload();
            }
          }, 1000);
        },
        error: function () {
          alert('Lỗi, hãy thử lại sau');
        }
      });
    }
  });

  $('.approveByUser').on('click', function (e) {

    var r = confirm("Bạn có chắc chắn muốn chấp nhận sản phẩm này?");

    if (r == false) {
      e.preventDefault();

    }else{
      e.preventDefault();
      var id = $(this).attr('data-id');
      var url = $(this).attr('href');
      var gln_code = $('#gln_code'+id).val();

      if(gln_code==undefined || gln_code ==''){
        window.location.href = url;
      }else
        {
        url = url + '?gln_code='+gln_code;
        window.location.href = url;
      }


    }


  });

  $('.removeByUser').on('click', function (e) {
    e.preventDefault();

    var $tr = $(this).parents('tr');

    var r = confirm("Bạn có chắc chắn muốn xoá thông tin do người dùng đóng góp của sản phẩm này?");

    if (r == true) {
      $.ajax({
        url: $(this).attr('href'),
        success: function () {
          $tr.addClass('danger');

          setTimeout(function () {
            $tr.remove();

            if (!$('#datatable > tbody > tr').length) {
              window.location.reload();
            }
          }, 1000);
        },
        error: function () {
          alert('Lỗi, hãy thử lại sau');
        }
      });
    }
  });
  var editingWnd;

  $('.editByUser').on('click', function (e) {
    e.preventDefault();

    var $tr = $(this).parents('tr');
    var url = $(this).attr('href') + '&_noheader=1';

    editingWnd = window.open(url, "_blank", "toolbar=no,scrollbars=no,resizable=yes,width=800,height=600");
  });
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
    closeOnSelect: false,
    dropdownCssClass: 'border-primary',
    containerCssClass: 'border-primary text-primary-700'
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
        cache:false,
        contentType: false,
        processData: false,
        success:function(data){

            var images = [];

            $this.prev('.aimages').find('.aimage').each(function () {
              images.push($(this).data('image'));
            });

            images.push(data.prefix);

            $.ajax({
              type: "PUT",
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


    var images = [];

    $this2.prev('.aimages').find('.aimage').each(function () {
      images.push($(this).data('image'));
    });
    if(images.length == 0){
      images = 'del-all';
    }

    $.ajax({
      type: "PUT",
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
      if (attr === "product_name") {
        data = {
          "product_name": newVal
        };
      } else if (attr === "price") {
        data = {
          "price": newVal
        };
      }else if (attr === "description") {
        data = {
          "attrs": {
            "1": newVal
          }
        };
      }


      $.ajax({
        type: "PUT",
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
        type: "PUT",
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
$('#select-all').on('click',function(){
  $('.s').prop('checked', this.checked);
});
$('#approve-all').on('click', function () {
  if(confirm('Bạn có chắc chắn thực hiện hành động này?')){
    $('#main-form').attr('action', '{{ route('Staff::Management::product2@approveListByUser') }}').submit();
  }

});

$('#disapprove-all').on('click', function () {
  if(confirm('Bạn có chắc chắn thực hiện hành động này?')){
    $('#main-form').attr('action', '{{ route('Staff::Management::product2@ignoreListByUser') }}').submit();
  }
});

  </script>
@endpush



