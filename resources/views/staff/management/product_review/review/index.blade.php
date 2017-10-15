@extends('_layouts/staff')

@section('page_title', 'Bài đánh giá sản phẩm')

@section('content')
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>Bài đánh giá sản phẩm</h2>
      </div>

      <div class="heading-elements">
        <div class="heading-btn-group">
          <a href="#" data-toggle="modal" data-target="#batch-approve-modal" class="btn btn-link"><i class="icon-checkmark-circle2"></i> Chấp nhận</a>
          <a href="#" data-toggle="modal" data-target="#batch-disapprove-modal" class="btn btn-link"><i class="icon-bin"></i> Không chấp nhận</a>
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
            <table class="table table-hover">
              <thead>
                <tr>
                  <td><input type="checkbox" id="check-all" ></td>
                  <th>Sản phẩm</th>
                  <th>Người gửi đánh giá</th>
                  <th>Nội dung</th>
                  <th>Trạng thái</th>
                  <th>Ngày gửi đánh giá</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($reviews as $index => $review)
                  <tr role="row" id="review-{{ $review->id }}" class="
                  {{ (session('new') and in_array($review->id, session('new'))) ? ' border-left-xlg border-left-success' : '' }}">
                    <td><input type="checkbox" name="checked[]" value="{{ $review->id }}" ></td>
                    <td>
                      <h3>{{ @$review->product->cached_info->product_name }}</h3>
                      {{ @$review->gtin }}
                    </td>
                    <td>{{ @$review->reviewer->name }}</td>
                    <td>{!! nl2br($review->content) !!}</td>
                    <td>{{ $review->statusText }}</td>
                    <td>{{ $review->created_at }}</td>
                    <td>
                      <div class="dropdown">
                        <button id="review-{{ $review->id }}-actions" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link">
                          <i class="icon-more2"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="gln-{{ $review->id }}-actions">
                          <li><a href="#" data-toggle="modal" data-target="#approve-modal" data-gln="{{ $review->gln }}" data-approve-url="{{ route('Staff::Management::productReview@review@approve', [$review->id]) }}"><i class="icon-checkmark-circle2"></i> Chấp nhận</a></li>
                          <li><a href="#" data-toggle="modal" data-target="#disapprove-modal" data-gln="{{ $review->gln }}" data-disapprove-url="{{ route('Staff::Management::productReview@review@disapprove', [$review->id]) }}"><i class="icon-checkmark-circle2"></i> Không Chấp nhận</a></li>
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
        <h4 class="modal-title" id="approve-modal-label">Chấp nhận đăng tải Bài đánh giá sản phẩm</h4>
      </div>
      <form method="POST">
        <div class="modal-body">
          <div class="form-group">
            Bạn có chắc chắn chấp nhận đăng tải Bài đánh giá này lên hệ thống của iCheck?
          </div>
          <div class="form-group">
            <label for="note" class="control-label text-semibold">Lý do</label>
            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do bạn chấp nhận đăng tải sản phẩm này lên hệ thống cảu iCheck"></i>
            <textarea id="note" name="note" rows="5" cols="5" class="form-control" placeholder="Lý do"></textarea>
          </div>
          <div class="form-group">
            <label for="amount" class="control-label text-semibold">Số tiền thưởng</label>
            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do bạn chấp nhận đăng tải sản phẩm này lên hệ thống cảu iCheck"></i>
            <input type="text" id="amount" name="amount" class="form-control" placeholder="500">
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
        <h4 class="modal-title" id="disapprove-modal-label">Không chấp nhận Bài đánh giá</h4>
      </div>
      <form method="POST">
        <div class="modal-body">
          <div class="form-group">
            <label for="note" class="control-label text-semibold">Lý do</label>
            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do bạn chấp nhận đăng tải sản phẩm này lên hệ thống cảu iCheck"></i>
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

<div class="modal fade" id="batch-approve-modal" tabindex="-1" role="dialog" aria-labelledby="batch-approve-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="batch-approve-modal-label">Chấp nhận nhiều bài đánh giá</h4>
      </div>
      <form action="{{ route('Staff::Management::productReview@review@batchApprove') }}" class="js-batch-form" method="POST">
        <div class="modal-body">
          <div class="form-group">
            Bạn có chắc chắn chấp nhận đăng tải Bài đánh giá này lên hệ thống của iCheck?
          </div>
          <div class="form-group">
            <label for="note" class="control-label text-semibold">Lý do</label>
            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do bạn chấp nhận đăng tải sản phẩm này lên hệ thống cảu iCheck"></i>
            <textarea id="note" name="note" rows="5" cols="5" class="form-control" placeholder="Lý do"></textarea>
          </div>
          <div class="form-group">
            <label for="amount" class="control-label text-semibold">Số tiền thưởng</label>
            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do bạn chấp nhận đăng tải sản phẩm này lên hệ thống cảu iCheck"></i>
            <input type="text" id="amount" name="amount" class="form-control" placeholder="500">
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

<div class="modal fade" id="batch-disapprove-modal" tabindex="-1" role="dialog" aria-labelledby="batch-disapprove-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="batch-disapprove-modal-label">Không chấp nhận nhiều bài đánh giá</h4>
      </div>
      <form action="{{ route('Staff::Management::productReview@review@batchDisapprove') }}" class="js-batch-form" method="POST">
        <div class="modal-body">
          <div class="form-group">
            <label for="note" class="control-label text-semibold">Lý do</label>
            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do bạn chấp nhận đăng tải sản phẩm này lên hệ thống cảu iCheck"></i>
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

  $('#approve-modal').on('show.bs.modal', function (event) {
    var $btn = $(event.relatedTarget),
        $modal = $(this);

    $modal.find('form').attr('action', $btn.data('approve-url'));
    $modal.find('.js-review-name').text($btn.data('name'));
  });

  $('#disapprove-modal').on('show.bs.modal', function (event) {
    var $btn = $(event.relatedTarget),
        $modal = $(this);

    $modal.find('form').attr('action', $btn.data('disapprove-url'));
    $modal.find('.js-review-name').text($btn.data('name'));
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



