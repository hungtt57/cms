@extends('_layouts/collaborator')

@section('page_title', 'Bài đánh giá sản phẩm của tôi')

@section('content')
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>Bài đánh giá sản phẩm của tôi</h2>
      </div>

      <div class="heading-elements">
        <div class="heading-btn-group">
          <a href="{{ route('Collaborator::productReview@add') }}" class="btn btn-link"><i class="icon-plus-circle"></i> Viết đánh giá sản phẩm</a>
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
                @foreach (App\Models\Enterprise\ProductReview\Review::$statusTexts as $status => $text)
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

        <h4>Chờ duyệt: {{ $count0 }} | Đã Từ chối: {{ $count1 }} | Đã được duyệt: {{ $count2 }} | Đang post: {{ $count3 }} | Post lỗi: {{ $count4 }}</h4>

        <div class="panel panel-flat">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Sản phẩm</th>
                  <th>Nội dung</th>
                  <th>Trạng thái</th>
                  <th>Lý do</th>
                  <th>Ngày gửi đánh giá</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($reviews as $index => $review)
                  <tr role="row" id="review-{{ $review->id }}" class="
                  {{ (session('new') and in_array($review->id, session('new'))) ? ' border-left-xlg border-left-success' : '' }}">
                    <td>
                      <h3>{{ @$review->product->cached_info->product_name }}</h3>
                    </td>
                    <td>{!! nl2br($review->content) !!}</td>
                    <td>{{ $review->statusText }}</td>
                    <td>{{ $review->note }}</td>
                    <td>{{ $review->created_at }}</td>
                    <td>
                      <div class="dropdown">
                        <button id="review-{{ $review->id }}-actions" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link">
                          <i class="icon-more2"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="review-{{ $review->id }}-actions">
                          @if ($review->status == 2 or $review->status == 0)
                          <li><a href="{{ route('Collaborator::productReview@edit', [$review->id]) }}"><i class="icon-pencil5"></i> Sửa</a></li>
                          @endif
                          <li><a href="#" data-toggle="modal" data-target="#delete-modal" data-name="{{ $review->name }}" data-delete-url="{{ route('Collaborator::productReview@delete', [$review->id]) }}"><i class="icon-bin"></i> Xoá</a></li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            {!! $reviews->appends(Request::all())->links() !!}

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
        <h4 class="modal-title" id="delete-modal-label">Xoá Bài đánh giá</h4>
      </div>
      <div class="modal-body">
        Bạn có chắc chắn muốn xoá bài đánh giá này?
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
            Bạn có chắc chắn?
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
@endsection

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
    $modal.find('.js-review-name').text($btn.data('name'));
  });

  $('#approve-modal').on('show.bs.modal', function (event) {
    var $btn = $(event.relatedTarget),
        $modal = $(this);

    $modal.find('form').attr('action', $btn.data('approve-url'));
    $modal.find('.js-review-name').text($btn.data('name'));
  });

  $(".js-checkbox").uniform({ radioClass: "choice" });

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



