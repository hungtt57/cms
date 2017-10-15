@extends('_layouts/staff')

@section('page_title', 'Bài đánh giá sản phẩm')

@section('content')
  <style>
    tbody .rowItem {
      background: #DCF2FA;
    }
  </style>
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>Bài đánh giá sản phẩm</h2>
      </div>

      <div class="heading-elements">
        <div class="heading-btn-group">
          <a href="{{ route('Staff::Management::contributeProduct@add') }}" class="btn btn-success"><i class="icon-checkmark-circle2"></i> Thêm sản phẩm</a>
          <a href="#" data-toggle="modal" data-target="#change-group-modal" class="btn btn-link"><i class="icon-checkmark-circle2"></i> Chuyển group</a>
          <a href="#" data-toggle="modal" data-target="#batch-approve-modal" class="btn btn-link"><i class="icon-checkmark-circle2"></i> Chấp nhận</a>
          <a href="#" data-toggle="modal" data-target="#batch-disapprove-modal" class="btn btn-link"><i class="icon-bin"></i> Không chấp nhận</a>
          <a href="#" data-toggle="modal" data-target="#batch-delete-modal" class="btn btn-link"><i class="icon-bin"></i> Xoá</a>
        </div>
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
              <label>GTIN</label>
              <input type="text" name="gtin" class="form-control" value="{{ Request::input('gtin') }}">
            </div>
            <div class="col-md-2">
              <label>Group</label>

              <select class="form-control" name="group">
                <option value="">Tất cả CTV</option>
                @foreach ($groups as $group)
                  <option value="{{ $group->group_id }}"{{ ((string) Request::input('group') === (string) $group->group_id) ? ' selected="selected"' : '' }}>{{ $group->group_id }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-2">
              <label>Trạng thái</label>
              <select class="form-control" name="status">
                <option value="">Tất cả sản phẩm</option>
                @foreach (App\Models\Collaborator\ContributeProduct::$statusTexts as $status => $text)
                <option value="{{ $status }}"{{ ((string) Request::input('status') === (string) $status) ? ' selected="selected"' : '' }}>{{ $text }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-2">
              <label>CTV</label>
              <select class="form-control js-ctv" name="contributor">
                <option value="">Tất cả CTV</option>
                @foreach ($contributors as $contributor)
                <option value="{{ $contributor->id }}"{{ ((string) Request::input('contributor') === (string) $contributor->id) ? ' selected="selected"' : '' }}>{{ $contributor->name }}</option>
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
            <div class="col-md-4">
              <div class="form-group">
                <label>Điều kiện</label>
                <div class="row">
                  <select id="country" name="condition[]" multiple="multiple"
                          class="select-border-color border-warning js-categories-select">
                    @foreach ($conditions as $key => $condition)
                      <option value="{{$key}}"
                              {{ ((!empty($selectedCondition)) and in_array($key, $selectedCondition)) ? ' selected="selected"' : '' }}
                      >{{ $condition }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-12">

              <button type="submit" class="btn btn-primary btn-lg">Lọc</button>
            </div>
          </div>
        </form>

        <h4>Chờ duyệt: {{ $count0 }} | Đã Từ chối: {{ $count1 }} | Đã được duyệt: {{ $count2 }} | Đang post: {{ $count3 }} | Post lỗi: {{ $count4 }} | Chờ đóng góp: {{ $count5 }}</h4>

        <div class="panel panel-flat">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>
                    @if(Request::get('status') !=App\Models\Collaborator\ContributeProduct::STATUS_APPROVED)
                    <input type="checkbox" id="check-all" >
                      @endif
                  </th>
                  <th>Group</th>
                  <th>Sản phẩm</th>
                  <th>Tên</th>
                  <th>Ảnh</th>
                  <th>Giá</th>
                  <th>Danh mục</th>
                  <th>Thuộc tính</th>
                  <th>GLN</th>
                  <th>Trạng thái</th>
                  <th>Ngày đóng góp</th>
                  <th>Người đóng góp</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>

                @foreach ($products as $index => $product)
                  <tr role="row" id="product-{{ $product->id }}" class="rowItem {{ (session('new') and in_array($product->id, session('new'))) ? ' border-left-xlg border-left-success' : '' }}">
                    <td>
                      @if($product->status != App\Models\Collaborator\ContributeProduct::STATUS_APPROVED)
                      <input type="checkbox" name="checked[]" value="{{ $product->id }}" >
                    @endif
                    </td>
                    <td>
                      <h3>{{ @$product->group }}</h3>
                    </td>
                    <td>
                      <h3>{{ @$product->gtin }}</h3>
                    </td>
                    <td>
                      <h3>{{ @$product->name }}</h3>
                    </td>
                    <td>
                      @if (is_array($product->images))
                      @foreach ($product->images as $image)
                      <div><img src="{{ get_image_url(@$image['path']) }}" height="30" /></div>
                      @endforeach
                      @endif
                    </td>
                    <td>
                      <h3>{{ @$product->price }}</h3>
                    </td>
                    <td>
                      @if (isset($product->categories) and is_array($product->categories))
                      @foreach ($product->categories as $cat)
                        {{@$cat['name']}},
                      @endforeach
                      @endif
                    </td>
                    <td>
                      @if($product->getProperties())
                        {!! $product->getProperties() !!}
                      @endif
                    </td>
                    <td>
                      <input placeholder="Nhap gln_code" type="text" class="form-control gln editable"  data-url="{{route('Staff::Management::contributeProduct@addInlineGln', [$product->id])}}" data-id="{{$product->id}}" data-attr="gln_code" value="{{ $product->gln_code }}" style="width: 200px;"/>
                    </td>
                    <td>{{ $product->statusText }}</td>
                    <td>{{ $product->contributedAt }}</td>
                    <td>{{ @$product->contributor->name }}</td>


                    <td>
                      <div class="dropdown">
                        <button id="product-{{ $product->id }}-actions" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link">
                          <i class="icon-more2"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="gln-{{ $product->id }}-actions">
                          @if($product->status != App\Models\Collaborator\ContributeProduct::STATUS_APPROVED)
                          <li><a href="#" data-toggle="modal" data-target="#approve-modal" data-gln="{{ $product->gln }}" data-approve-url="{{ route('Staff::Management::contributeProduct@approve', [$product->id]) }}"><i class="icon-checkmark-circle2"></i> Chấp nhận</a></li>
                          <li><a href="#" data-toggle="modal" data-target="#disapprove-modal" data-gln="{{ $product->gln }}" data-disapprove-url="{{ route('Staff::Management::contributeProduct@disapprove', [$product->id]) }}"><i class="icon-checkmark-circle2"></i> Không Chấp nhận</a></li>
                          @endif
                          <li><a href="#" data-toggle="modal" data-target="#delete-modal" data-gln="{{ $product->gln }}" data-delete-url="{{ route('Staff::Management::contributeProduct@delete', [$product->id]) }}"><i class="icon-checkmark-circle2"></i> Xoá</a></li>
                        </ul>
                      </div>
                    </td>
                  </tr>

                  <tr>
                    <td colspan="12">
                      @if (is_array($product->attributes))
                      @foreach ($product->attributes as $attr)
                      @if ($attr['content'])
                      <div>{{$attr['title']}}</div>
                      <textarea class="form-control" rows="4">{{$attr['content']}}</textarea>
                      @endif
                      @endforeach
                      @endif
                    </td>
                  </tr>

                @endforeach
              </tbody>
            </table>
            {!! $products->appends(Request::all())->links() !!}
        </div>
        <div class="btn-group">
          <a href="{{ route('Staff::Management::contributeProduct@add') }}" class="btn btn-success"><i class="icon-checkmark-circle2"></i> Thêm sản phẩm</a>
          <a href="#" data-toggle="modal" data-target="#batch-approve-modal" class="btn btn-link"><i class="icon-checkmark-circle2"></i> Chấp nhận</a>
          <a href="#" data-toggle="modal" data-target="#batch-disapprove-modal" class="btn btn-link"><i class="icon-bin"></i> Không chấp nhận</a>
          <a href="#" data-toggle="modal" data-target="#batch-delete-modal" class="btn btn-link"><i class="icon-bin"></i> Xoá</a>
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

<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="delete-modal-label">Xoá</h4>
      </div>
      <form method="POST">
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

  <div class="modal fade" id="change-group-modal" tabindex="-1" role="dialog" aria-labelledby="batch-approve-modal-label">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="batch-approve-modal-label">Chuyển group</h4>
        </div>
        <form action="{{ route('Staff::Management::contributeProduct@changeGroup') }}" class="js-batch-form" method="POST">
          <div class="modal-body">

            <div class="form-group">
                <label>Group :</label>
                <select class="form-control" name="group">
                  @foreach ($groups as $group)
                    <option value="{{ $group->group_id }}"{{ ((string) Request::input('group') === (string) $group->group_id) ? ' selected="selected"' : '' }}>{{ $group->group_id }}</option>
                  @endforeach
                </select>
            </div>
          </div>
          <div class="modal-footer">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="POST">
            <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
            <button type="submit" id="submit-change-group" class="btn btn-danger">Xác nhận</button>
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
      <form action="{{ route('Staff::Management::contributeProduct@batchApprove') }}" class="js-batch-form" method="POST">
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
      <form action="{{ route('Staff::Management::contributeProduct@batchDisapprove') }}" class="js-batch-form" method="POST">
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

<div class="modal fade" id="batch-delete-modal" tabindex="-1" role="dialog" aria-labelledby="batch-delete-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="batch-delete-modal-label">Xoá</h4>
      </div>
      <form action="{{ route('Staff::Management::contributeProduct@batchDelete') }}" class="js-batch-form" method="POST">
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
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
@endpush

@push('scripts_foot')
  <script>

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
        if (attr === "gln_code") {
          data = {
            "gln_code": newVal
          };
        }


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

    $(".js-categories-select").select2({
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
    $(".js-help-icon").popover({
    html: true,
    trigger: "hover",
    delay: { "hide": 1000 }
  });

  $(".js-ctv").select2();
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

  $('#delete-modal').on('show.bs.modal', function (event) {
    var $btn = $(event.relatedTarget),
        $modal = $(this);

    $modal.find('form').attr('action', $btn.data('delete-url'));
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



