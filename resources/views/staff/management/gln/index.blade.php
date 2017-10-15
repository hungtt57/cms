@extends('_layouts/staff')

@section('content')
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>Mã địa điểm toàn cầu (GLN)</h2>
      </div>

      <div class="heading-elements">

        <div class="heading-btn-group">
          <a href="{{ route('Staff::Management::gln@add') }}" class="btn btn-link"><i class="icon-add"></i> Thêm GLN</a>
          <a href="#" class="btn btn-link disabled"><i class="icon-trash"></i> Xoá</a>
        </div>
      </div>
    </div>
  </div>
  <!-- /page header -->
  <!-- Page container -->
  <div class="page-container">
    <div class="col-md-3 col-md-offset-3" style="margin-bottom: 30px">
      <form action="" id="form">

        <select onchange="change()" class="form-control" name="status" id="status-filter">
          <option value="1994" {{ ((string) Request::input('status') === (string) 1994) ? ' selected="selected"' : '' }}>Tất cả</option>
          @foreach(App\Models\Enterprise\GLN::$statusTexts  as $key => $status)
            <option value="{{$key}}" {{ ((string) Request::input('status') === (string) $key) ? ' selected="selected"' : '' }}>{{$status}}</option>
            @endforeach
        </select>
      </form>
    </div>
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
                  <th><input type="checkbox" id="select-all" class="js-checkbox" /></th>
                  <th>Doanh nghiệp</th>
                  <th>Tên GLN</th>
                  <th>Mã GLN</th>
                  <th>Quốc gia</th>
                  <th>Prefix</th>
                  <th>Lý do</th>
                  <th>Chứng nhận</th>
                  <th>Cảnh báo</th>
                  <th>Trạng thái</th>
                  <th>Ngày tạo</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($gln as $index => $number)
                  <tr role="row" id="gln-{{ $number->id }}">
                    <td><input type="checkbox" name="selected[{{ $number->id }}]" class="js-checkbox" value="1" /></td>

                    <td><a href="{{ route('Staff::Management::business@show', [$number->business->id]) }}">{{ $number->business->name }}</a></td>
                    <td>{{ $number->name }}</td>
                    <td>{{ $number->gln }}</td>
                    <td>{{ $number->country->name }}</td>
                    <td>{{ $number->prefix }}</td>
                    <td>{{ $number->additional_info }}</td>
                    <td>
                      @if ($number->certificate_file)
                      <a href="{{ route('Staff::Management::gln@viewCert', [$number->certificate_file]) }}" target="_blank">Xem chứng nhận 1</a>
                      @endif
                      @if ($number->certificate_file2)
                      <a href="{{ route('Staff::Management::gln@viewCert', [$number->certificate_file2]) }}" target="_blank">Xem chứng nhận 2</a>
                      @endif
                      @if ($number->certificate_file3)
                      <a href="{{ route('Staff::Management::gln@viewCert', [$number->certificate_file3]) }}" target="_blank">Xem chứng nhận 3</a>
                      @endif
                      @if ($number->certificate_file4)
                      <a href="{{ route('Staff::Management::gln@viewCert', [$number->certificate_file4]) }}" target="_blank">Xem chứng nhận 4</a>
                      @endif
                      @if ($number->certificate_file5)
                      <a href="{{ route('Staff::Management::gln@viewCert', [$number->certificate_file5]) }}" target="_blank">Xem chứng nhận 5</a>
                      @endif
                    </td>
                    <td>{{ $number->warning }}</td>
                    <td>{{ $number->statusText }}</td>
                    <td>{{ $number->created_at }}</td>
                    <td>
                      <div class="dropdown">
                        <button id="gln-{{ $number->id }}-actions" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link">
                          <i class="icon-more2"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="gln-{{ $number->id }}-actions">
                          <li><a href="#" data-toggle="modal" data-target="#approve-modal" data-gln="{{ $number->gln }}" data-approve-url="{{ route('Staff::Management::gln@approve', [$number->id]) }}"><i class="icon-checkmark-circle2"></i> Chấp nhận</a></li>
                          <li><a href="{{ route('Staff::Management::gln@edit', [$number->id]) }}"><i class="icon-pencil5"></i> Sửa</a></li>
                          <li><a href="#" data-toggle="modal" data-target="#delete-modal" data-name="{{ $number->name }}" data-delete-url="{{ route('Staff::Management::gln@delete', [$number->id]) }}"><i class="icon-trash"></i> Xoá</a></li>
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
@endsection

@push('js_files_foot')
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
@endpush

@push('scripts_foot')
  <script>
  $('#delete-modal').on('show.bs.modal', function (event) {
    var $btn = $(event.relatedTarget),
        $modal = $(this);

    $modal.find('form').attr('action', $btn.data('delete-url'));
    $modal.find('.js-gln-name').text($btn.data('name'));
  });

  $('#approve-modal').on('show.bs.modal', function (event) {
    var $btn = $(event.relatedTarget),
        $modal = $(this);

    $modal.find('form').attr('action', $btn.data('approve-url'));
    $modal.find('.js-product-name').text($btn.data('name'));
  });
  function change(){
    $('#form').submit();
  }
  $(".js-checkbox").uniform({ radioClass: "choice" });
  </script>
@endpush



