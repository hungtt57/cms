@extends('_layouts/default')

@section('page_title', 'Mã địa điểm toàn cầu (GLN)')

@section('content')
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>Mã địa điểm toàn cầu (GLN)</h2>
      </div>

      <div class="heading-elements">
        <div class="heading-btn-group">
          <a href="{{ route('Business::gln@add') }}" class="btn btn-link"><i class="icon-plus-circle"></i> Thêm GLN</a>
          <a href="#" class="btn btn-link disabled"><i class="icon-trash"></i> Xoá</a>
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
        <div class="row">
          <div class="col-md-4">
            <form action="{{ Request::fullUrl() }}">
              <div class="form-group has-feedback has-feedback-left">
                <input type="text" name="q" class="form-control input-xlg" value="{{ Request::input('q') }}" placeholder="Tìm kiếm theo tên hoặc GLN ...">
                <div class="form-control-feedback">
                  <i class="icon-search"></i>
                </div>
              </div>
            </form>
          </div>
          <div class="col-md-4">
            <a class="btn btn-default btn-raised" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="icon-filter3"></i> Lọc</a>
          </div>
        </div>
        <form action="{{ Request::fullUrl() }}" class="collapse" id="collapseExample" style="margin-bottom: 40px;">
          <div class="row">
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
              <button type="submit" class="btn btn-primary btn-lg">Filter</button><a href="{{ url('/') }}" class="btn btn-default btn-lg">Clear All</a>
            </div>
          </div>
        </form>

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
                <th><input type="checkbox" id="select-all" class="js-checkbox" /></th>
                <th>Tên</th>
                <th>Mã GLN</th>
                <th>Quốc gia</th>
                <th>Prefix</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($gln as $index => $number)
                <tr role="row" id="gln-{{ $number->id }}">
                  <td><input type="checkbox" name="selected[{{ $number->id }}]" class="js-checkbox" value="1" /></td>
                  <td>{{ $number->name }}</td>
                  <td>{{ $number->gln }}</td>
                  <td>{{ $number->country->name }}</td>
                  <td>{{$number->prefix}}</td>
                  <td>{{ $number->statusText }}</td>
                  <td>{{ $number->created_at }}</td>
                  <td>
                    <div class="dropdown">
                      <button id="gln-{{ $number->id }}-actions" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link">
                        <i class="icon-more2"></i>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="gln-{{ $number->id }}-actions">
                        {{--<li><a href="{{ route('Business::gln@edit', [$number->id]) }}"><i class="icon-pencil5"></i> Sửa</a></li>--}}
                        <li><a href="#" data-toggle="modal" data-target="#delete-modal" data-name="{{ $number->name }}" data-delete-url="{{ route('Business::gln@delete', [$number->id]) }}"><i class="icon-trash"></i> Xoá</a></li>
                      </ul>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
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
        <h4 class="modal-title" id="delete-modal-label">Xoá Nhà sản xuất</h4>
      </div>
      <div class="modal-body">
        Quý Doanh nghiệp có chắc chắn muốn xoá Nhà sản xuất <strong class="text-danger js-gln-name"></strong> khỏi hệ thống của iCheck?
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
  $('#delete-modal').on('show.bs.modal', function (event) {
    var $btn = $(event.relatedTarget),
        $modal = $(this);

    $modal.find('form').attr('action', $btn.data('delete-url'));
    $modal.find('.js-gln-name').text($btn.data('name'));
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



