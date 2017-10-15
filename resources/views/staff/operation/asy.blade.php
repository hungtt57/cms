@extends('_layouts/staff')

@section('page_title', 'Sản phẩm được yêu cầu đồng bộ')

@section('content')
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>Sản phẩm được yêu cầu đồng bộ</h2>
      </div>

      <div class="heading-elements">
        <div class="heading-btn-group">
          <a href="{{ route('Staff::operation@add') }}" class="btn btn-link"><i class="icon-plus-circle"></i> Thêm Sản phẩm</a>
          <a href="#" data-toggle="modal" data-target="#batch-cancel-modal" class="btn btn-link"><i class="icon-edit"></i> Không Duyệt</a>
          <a href="#" data-toggle="modal" data-target="#batch-accept-modal" class="btn btn-link"><i class="icon-edit"></i> Duyệt</a>
          <a href="#" data-toggle="modal" data-target="#batch-delete-modal" class="btn btn-link"><i class="icon-bin"></i> Xoá</a>
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
        <form action="{{ Request::fullUrl() }}" style="margin-bottom: 40px;">
          <div class="row">
            <div class="col-md-2">
              <label>Trạng thái</label>
              <select class="form-control" name="status">
                <option value="">Tất cả review</option>
                @foreach (App\Models\Enterprise\ProductDone::$statusTexts as $status => $text)
                <option value="{{ $status }}"{{ ((string) Request::input('status') === (string) $status) ? ' selected="selected"' : '' }}>{{ $text }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Ngày tạo</label>
                <div class="row">
                  <div class="col-md-6">
                    <input type="text" name="created_at_from" id="created-at-from" value="{{ Request::input('created_at_from') }}" class="form-control js-date-picker" placeholder="Từ ngày">
                  </div>
                  <div class="col-md-6">
                    <input type="text" name="created_at_to" id="created-at-to" value="{{ Request::input('created_at_to') }}" class="form-control js-date-picker" placeholder="Đến ngày">
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <button type="submit" class="btn btn-primary btn-lg">Lọc</button></a>
            </div>
          </div>
        </form>
        <h4>Không chấp nhận: {{ $count0 }} | Hoàn thành: {{ $count1 }} | Đang chờ duyệt {{ $count2 }}
        | Cần hoàn thiện {{ $count3 }} | Lỗi {{ $count4 }} | Cần Sửa {{$count5}}.
        </h4>
        <div class="panel panel-flat">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <td><input type="checkbox" id="check-all" ></td>
                  <th>Mã sản phẩm</th>
                  <th>Tên</th>
                  <th>Hình ảnh</th>
                  <th>Danh mục</th>
                  <th>Giá sản phẩm</th>
                  <th>Ngày tạo</th>
                  <th>Trạng thái</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($products as $index => $product)
                  <tr role="row" id="product-{{ $product->id }}">
                    <td><input type="checkbox" name="checked[]" value="{{ $product->id }}" ></td>
                    <td>{{ $product->gtin }}</td>
                    <td>{{ $product->product_name }}</td>
                    <td><img style="width: 70px; height:70px;" src="{{$product->product_image ? \App\Models\Enterprise\ProductDone::get_image_url ($product->product_image) : 'http://bus.runtime.mobi/assets/images/image.png' }}" /></td>
                    <td><?= $product->getCate($product->id); ?></td>
                    <td>{{ number_format($product->product_price) }} đ</td>
                    <td>{{ $product->created_at }}</td>
                    <td>{{ App\Models\Enterprise\ProductDone::$statusTexts[$product->status] }}</td>
                    <td>
                      <div class="dropdown">
                        <button id="product-{{ $product->id }}-actions" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link">
                          <i class="icon-more2"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="product-{{ $product->id }}-actions">
                          <li><a href="{{ route('Staff::operation@edit', [$product->id]) }}"><i class="icon-pencil5"></i> Sửa</a></li>
                          <li><a href="#" data-toggle="modal" data-target="#delete-modal" data-name="{{ $product->id }}" data-delete-url="{{ route('Staff::operation@delete', [$product->id]) }}"><i class="icon-bin"></i> Xoá</a></li>
                          <li><a href="#" data-toggle="modal" data-target="#accept-modal" data-name="{{ $product->id }}" data-accept-url="{{ route('Staff::operation@accept', [$product->id]) }}"><i class="icon-bag"></i> Chấp nhận</a></li>
                          <li><a href="#" data-toggle="modal" data-target="#cancel-modal" data-name="{{ $product->id }}" data-cancel-url="{{ route('Staff::operation@cancel', [$product->id]) }}"><i class="icon-plus-circle"></i> Hủy bỏ</a></li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            {!! $products->links() !!}
          </div>
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

<div class="modal fade" id="batch-delete-modal" tabindex="-1" role="dialog" aria-labelledby="batch-delete-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="batch-delete-modal-label">Xoá Nhiều Sản phẩm</h4>
      </div>
      <form action="{{ route('Staff::operation@batchDelete') }}" class="js-batch-form" method="POST">
        <div class="modal-body">
          <div class="form-group">
            Bạn có chắc chắn thực hiện hành động này?
          </div>
        </div>
        <div class="modal-footer">
          {{ csrf_field() }}
          <input type="hidden" name="_method" value="DELETE">
          <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
          <button type="submit" class="btn btn-danger">Xác nhận</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="batch-accept-modal" tabindex="-1" role="dialog" aria-labelledby="batch-accept-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="batch-accept-modal-label">Duyệt nhiều sản phẩm cùng lúc</h4>
      </div>
      <form action="{{ route('Staff::operation@accepts') }}" class="js-accepts-form" method="POST">
        <div class="modal-body">
          <div class="form-group">
            Bạn có chắc chắn thực hiện hành động này?
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

  <div class="modal fade" id="batch-cancel-modal" tabindex="-1" role="dialog" aria-labelledby="batch-cancel-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="batch-accept-modal-label">Từ chối nhiều sản phẩm cùng lúc</h4>
      </div>
      <form action="{{ route('Staff::operation@cancels') }}" class="js-cancels-form" method="POST">
        <div class="modal-body">
          <div class="form-group">
            Bạn có chắc chắn thực hiện hành động này?
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
  
<div class="modal fade" id="accept-modal" tabindex="-1" role="dialog" aria-labelledby="accept-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="approve-modal-label">Chấp nhận đăng tải sản phẩm</h4>
      </div>
      <form method="POST">
        <div class="modal-body">
          <div class="form-group">
            Bạn có chắc chắn chấp nhận đưa sản phẩm này lên hệ thống của iCheck?
          </div>
          <div class="form-group">
            <label for="note" class="control-label text-semibold">Lý do</label>
            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do bạn chấp nhận đăng tải sản phẩm này lên hệ thống cảu iCheck"></i>
            <textarea id="note" name="note" rows="5" cols="5" class="form-control" placeholder="Lý do"></textarea>
          </div>
          <div class="form-group">
            <label for="price" class="control-label text-semibold">Số tiền thưởng</label>
            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do bạn chấp nhận đăng tải sản phẩm này lên hệ thống cảu iCheck"></i>
            <input type="text" id="amount" name="price" class="form-control" placeholder="500">
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

  
<div class="modal fade" id="cancel-modal" tabindex="-1" role="dialog" aria-labelledby="cancel-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="approve-modal-label">Hủy sản phẩm này</h4>
      </div>
      <form method="POST">
        <div class="modal-body">
          <div class="form-group">
            <label for="note" class="control-label text-semibold">Lý do</label>
            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do bạn hủy sản phẩm này lên hệ thống cảu iCheck"></i>
            <textarea id="note" name="note" rows="5" cols="5" class="form-control" placeholder="Lý do"></textarea>
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
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
@endpush

@push('js_files_foot')
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/picker.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/picker.date.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/picker.time.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/legacy.js') }}"></script>
@endpush

@push('scripts_foot')
  <script>
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

  $('#accept-modal').on('show.bs.modal', function (event) {
    var $btn = $(event.relatedTarget),
        $modal = $(this);
    $modal.find('form').attr('action', $btn.data('accept-url'));
  });
  $('#cancel-modal').on('show.bs.modal', function (event) {
    var $btn = $(event.relatedTarget),
        $modal = $(this);
    $modal.find('form').attr('action', $btn.data('cancel-url'));
  });
  
  $('.js-accepts-form, .js-cancels-form, .js-batch-form').on('submit', function (e) {
    var ids = [];
    $('[name^="checked[]"]:checked').each(function () {
      ids.push($(this).val());
    });
    var $input = $("<input>").attr({'type': 'hidden', 'name': 'ids'}).val(ids);
    $(this).append($input);
  });
  
  $(".js-checkbox").uniform({ radioClass: "choice" });

  $(document).ready(function () {
    $('#check-all').change(function (e) {
      var $this = $(this);

      $('[name^="checked"]').prop('checked', $this.prop('checked'));
    });
  });

  $(document).on('submit', 'form', function () {
    $('button[type="submit"]').prop('disabled', true);
  });
  
  $('#created-at-to').pickadate({
    format: 'yyyy-mm-dd'
  });
  $('#created-at-from').pickadate({
    format: 'yyyy-mm-dd',
    onStart: function () {
      var fromPicker = $('#created-at-from').pickadate('picker');

      if (fromPicker.get('select')) {
        var toPicker = $('#created-at-to').pickadate('picker');

        toPicker.set('min', fromPicker.get('select').obj);
      }
    },
    onSet: function (context) {
      var toPicker = $('#created-at-to').pickadate('picker');

      if (toPicker.get('select') && toPicker.get('select').pick <= context.select) {
        toPicker.set('select', new Date(context.select));
      }

      toPicker.set('min', new Date(context.select));
    }
  });
  </script>
@endpush



