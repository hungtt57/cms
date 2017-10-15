@extends('_layouts/staff')

@section('page_title', 'Sản phẩm sẽ được yêu cầu đánh giá')

@section('content')
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>Sản phẩm sẽ được yêu cầu đánh giá</h2>
      </div>

      <div class="heading-elements">
        <div class="heading-btn-group">
          <a href="{{ route('Staff::Management::productReview@group@add') }}" class="btn btn-link"><i class="icon-plus-circle"></i> Thêm Nhóm</a>
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

        <div class="panel panel-flat">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <td><input type="checkbox" id="check-all" ></td>
                  <th>Tên nhóm</th>
                  <th>Số bài đánh giá</th>
                  <th>Số bài đánh giá tối đa</th>
                  <th>Số thành viên hiện tại</th>
                  <th>Ngày tạo</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($groups as $index => $group)
                  <tr role="row" id="group-{{ $group->id }}">
                    <td><input type="checkbox" name="checked[]" value="{{ $group->id }}" ></td>
                    <td>{{ $group->name }}</td>
                    <td>{{ $group->review_count }}</td>
                    <td>{{ $group->max_review }}</td>
                    <td>
                      {{ $group->members->count() }}
                      <button class="btn btn-info btn-xs">Yêu cầu các thành viên kết bạn với nhau</button>
                    </td>
                    <td>{{ $group->created_at }}</td>
                    <td>
                      <div class="dropdown">
                        <button id="group-{{ $group->id }}-actions" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link">
                          <i class="icon-more2"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="group-{{ $group->id }}-actions">
                          <li><a href="{{ route('Staff::Management::productReview@group@edit', [$group->id]) }}"><i class="icon-pencil5"></i> Sửa</a></li>
                          <li><a href="#" data-toggle="modal" data-target="#delete-modal" data-name="{{ $group->name }}" data-delete-url="{{ route('Staff::Management::productReview@group@delete', [$group->id]) }}"><i class="icon-bin"></i> Xoá</a></li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
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
        <h4 class="modal-title" id="delete-modal-label">Xoá Nhóm</h4>
      </div>
      <div class="modal-body">
        Bạn có chắc chắn muốn xoá?
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

<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="edit-modal-label">Sửa Nhiều Nhóm</h4>
      </div>
      <form action="{{ route('Staff::Management::productReview@group@batchUpdate') }}" class="js-batch-form" method="POST">
        <div class="modal-body">
          <div class="form-group">
            <label for="max-review" class="control-label text-semibold">Số review tối đa</label>
            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do bạn chấp nhận đăng tải sản phẩm này lên hệ thống cảu iCheck"></i>
            <input type="text" id="max-review" name="max_review" class="form-control">
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

<div class="modal fade" id="batch-delete-modal" tabindex="-1" role="dialog" aria-labelledby="batch-delete-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="batch-delete-modal-label">Xoá Nhiều Nhóm</h4>
      </div>
      <form action="{{ route('Staff::Management::productReview@group@batchDelete') }}" class="js-batch-form" method="POST">
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
    $modal.find('.js-group-name').text($btn.data('name'));
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
  </script>
@endpush



