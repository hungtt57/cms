@extends('_layouts/staff')

@section('content')
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>Sản phẩm bị cảnh báo</h2>
      </div>


      <div class="heading-elements">


        <button href="#" class="btn btn-link" data-toggle="modal" data-target="#add-modal" ><i class="icon-plus-circle"></i>Thêm </button>

        <button href="#" class="btn btn-link" data-toggle="modal" data-target="#delete-modal" ><i class="icon-plus-circle"></i>Xóa </button>

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
              <div class="form-group">

                  <select name="type" class="form-control">
                    @foreach ($messages as $message)
                    <option  @if(Request::get('type') == $message->id) selected @endif value="{{ $message->id }}">{{ $message->short_msg }}</option>
                    @endforeach
                  </select>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <input class="form-control" type="text" name="search" placeholder="Search" />
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-success btn-xs">Search</button>

                  </span>
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

        <div class="panel panel-flat">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Tên</th>
                  <th>Hình ảnh</th>
                  <th>Barcode</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($warnings as $w)
                  @if(isset($w->product))
                  <tr role="row" id="product-{{ $w->product->id }}">
                    <td>{{ $w->product->product_name }}</td>
                    <td><img src="{{ get_image_url($w->product->image_default, 'thumb_small') }}" /></td>
                    <td>
                      @if (validate_EAN13Barcode($w->product->gtin_code))
                        <svg class="barcode"
                          jsbarcode-height="50"
                          jsbarcode-format="EAN13"
                          jsbarcode-value="{{$w->product->gtin_code}}"
                          jsbarcode-textmargin="0">
                        </svg>
                      @endif
                      {{$w->product->gtin_code}}
                    </td>
                    <td>
                      <div class="dropdown">
                        <button id="product-{{ $w->product->id }}-actions" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link">
                          <i class="icon-more2"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="product-{{ $w->product->id }}-actions">
                          <li><a href="{{ route('Staff::Management::product2@edit', [$w->product->id]) }}"><i class="icon-pencil5"></i> Sửa</a></li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                  @endif
                @endforeach
              </tbody>
            </table>
            {!! $warnings->appends(Request::all())->links() !!}
        </div>
      </div>
      <!-- /main content -->
    </div>
    <!-- /page content -->
  </div>
  <!-- /page container -->



<div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="approve-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="approve-modal-label">Thêm sản phẩm cảnh báo</h4>
      </div>
      <form method="POST" action="{{route('Staff::Management::product2@activeWarning')}}">
        <div class="modal-body">
          <div class="form-group">
          Chọn cảnh báo:
            <select name="message_id" class="form-control">
              @foreach ($messages as $message)
                <option value="{{ $message->id }}">{{ $message->short_msg }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">

            <label for="gtin" class="control-label text-semibold">Nhập gtin:</label>
            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do bạn chấp nhận đăng tải sản phẩm này lên hệ thống cảu iCheck"></i>
            <textarea id="gtin" name="gtin" rows="5" cols="5" class="form-control" placeholder="Nhập gtin"></textarea>

          </div>

        </div>
        <div class="modal-footer">
          {{ csrf_field() }}
          <input type="hidden" name="_method" value="POST">
          <input type="hidden" name="type" value="add">
          <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
          <button type="submit" class="btn btn-danger">Xác nhận</button>
        </div>
      </form>
    </div>
  </div>
</div>

  <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="approve-modal-label">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="approve-modal-label">Thêm sản phẩm cảnh báo</h4>
        </div>
        <form method="POST" action="{{route('Staff::Management::product2@activeWarning')}}">
          <div class="modal-body">

            <div class="form-group">

              <label for="gtin" class="control-label text-semibold">Nhập gtin:</label>
              <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do bạn chấp nhận đăng tải sản phẩm này lên hệ thống cảu iCheck"></i>
              <textarea id="gtin" name="gtin" rows="5" cols="5" class="form-control" placeholder="Nhập gtin"></textarea>

            </div>

          </div>
          <div class="modal-footer">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="type" value="delete">
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
@endpush

@push('scripts_foot')
  <script>
  JsBarcode(".barcode").init();

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
  </script>
@endpush



