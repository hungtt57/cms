@extends('_layouts/staff')

@section('page_title', 'Thông báo vá Popup')

@section('content')
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>Thông báo vá Popup</h2>
      </div>

      <div class="heading-elements">
        <div class="heading-btn-group">
          <a href="{{ route('Staff::operation@create') }}" class="btn btn-link"><i class="icon-plus-circle"></i> Thêm Notification</a>
          <a href="#" data-toggle="modal" data-target="#edit-modal" class="btn btn-link"><i class="icon-edit"></i> Sửa</a>
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
                <option value="">Tất cả trạng thái</option>
                @foreach (App\Models\Enterprise\Notification::$statusPost as $status => $text)
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
        <h4>Images: {{ $count0 }} | Video: {{ $count1 }}</h4>
        <div class="panel panel-flat">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <td><input type="checkbox" id="check-all" ></td>
                  <th>#</th>
                  <th>Tên</th>
                  <th>Nội dung</th>
                  <th>Hình ảnh</th>
                  <th>Ngày chạy</th>
                  <th>Ngày kết thúc</th>
                  <th>Trạng thái</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($notification as $index => $value)
                  <tr role="row" id="product-{{ $value->id }}">
                    <td><input type="checkbox" name="checked[]" value="{{ $value->id }}" ></td>
                    <td>{{ $value->id }}</td>
                    <td>{{ $value->name }}</td>
                    <td>{{ $value->content }}</td>
                    <td><img style="width: 70px; height:70px;" src="{{$value->image_video ? \App\Models\Enterprise\ProductDone::get_image_url ($value->image_video) : 'http://bus.runtime.mobi/assets/images/image.png' }}" /></td>  
                    <td>{{ date('Y/m/d',$value->date_start) }}</td>
                    <td>{{ date('Y/m/d',$value->date_stop) }}</td>
                    <td>{{ \App\Models\Enterprise\Notification::$statusPost[$value->status] }}</td>
                    <td>
                      <div class="dropdown">
                        <button id="product-{{ $value->id }}-actions" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link">
                          <i class="icon-more2"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="product-{{ $value->id }}-actions">
                          <li><a href="{{ route('Staff::operation@editNotifies', [$value->id]) }}"><i class="icon-pencil5"></i> Sửa</a></li>
                          <li><a href="#" data-toggle="modal" data-target="#delete-modal" data-name="{{ $value->id}}" data-delete-url="{{ route('Staff::operation@deleteNotifies', [$value->id]) }}"><i class="icon-bin"></i> Xoá</a></li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            {!! $notification->links() !!}
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

  $('#approve-modal').on('show.bs.modal', function (event) {
    var $btn = $(event.relatedTarget),
        $modal = $(this);

    $modal.find('form').attr('action', $btn.data('approve-url'));
    $modal.find('.js-product-name').text($btn.data('name'));
  });

  $('.js-batch-form').on('submit', function (e) {
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



