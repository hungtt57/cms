@extends('_layouts/staff')

@section('page_title', 'Doanh nghiệp')

@section('content')
<div id="main-content">
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>Doanh nghiệp</h2>
      </div>

      <div class="heading-elements">
        <div class="heading-btn-group">
          <a href="{{ route('Staff::Management::business@add') }}" class="btn btn-link"><i class="icon-plus-circle"></i> Thêm Doanh nghiệp</a>

          <button type="button" id="btn-batch-disapprove" data-action="disapprove" disabled="disabled" class="btn btn-grey js-batch-button" data-toggle="modal" data-target="#batch-disapprove-modal">
            <i class="icon-cancel-circle"></i> Không chấp nhận</a>
          </button>
          <button type="button" id="btn-batch-activate" data-action="activate" disabled="disabled" class="btn btn-success js-batch-button" data-toggle="modal" data-target="#batch-activate-modal">
            <i class="icon-checkmark-circle"></i> Kích hoạt</a>
          </button>
          <button type="button" id="btn-batch-deactivate" data-action="deactivate" disabled="disabled" class="btn btn-grey js-batch-button" data-toggle="modal" data-target="#batch-deactivate-modal">
            <i class="icon-cancel-circle"></i> Huỷ kích hoạt</a>
          </button>
          <button type="button" id="btn-batch-delete" data-action="delete" disabled="disabled" class="btn btn-danger js-batch-button" data-toggle="modal" data-target="#batch-delete-modal">
            <i class="icon-bin"></i> Xoá</a>
          </button>
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
          <div class="col-md-3 mb-20">
            <form id="query-filter">
              <div class="has-feedback has-feedback-left">
                <input type="text" name="q" class="form-control" value="{{ Request::input('q') }}" placeholder="Tìm kiếm theo tên hoặc email...">
                <div class="form-control-feedback">
                  <i class="icon-search"></i>
                </div>
              </div>
            </form>
          </div>
          <div class="col-md-6 mb-20">
            <div class="row">
              <div class="col-md-3">
                <select class="form-control" id="status-filter">
                  <option value="">Tất cả Doanh nghiệp</option>
                  @foreach (App\Models\Enterprise\Business::$statusTexts as $status => $text)
                  <option value="{{ $status }}"{{ ((string) Request::input('status') === (string) $status) ? ' selected="selected"' : '' }}>{{ $text }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3">
                <select class="form-control" id="role-filter">
                  <option value="">Group</option>
                  <option value="1000">Không có</option>
                  @foreach ($roles as $key => $role)
                    <option value="{{ $role->id }}"{{ ((string) Request::input('role') === (string) $role->id) ? ' selected="selected"' : '' }}>{{ $role->name}}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="col-md-3 mb-20 text-right">
            <span><strong id="from-record">0</strong> - <strong id="to-record">0</strong> trong tổng số <strong id="total-records">0</strong></span>
            <div class="btn-toolbar display-inline-block">
              <div class="btn-group">
                <button class="btn btn-default" id="prev-page-btn" disabled="disabled">
                  <i class="icon-arrow-left3"></i>
                </button>
                <button class="btn btn-default" id="next-page-btn" disabled="disabled">
                  <i class="icon-arrow-right3"></i>
                </button>
              </div>
              <div class="btn-group">
                <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                  <i class="icon-cog position-left"></i><span class="sr-only">Tùy chọn</span><span class="caret"></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-right dropdown-options">
                  <li id="columns-toggle-header" class="dropdown-header">Hiện/ Ẩn cột</li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        @if ($errors->count())
          @foreach ($errors->all() as $error)
            <div class="alert bg-danger alert-styled-left">
              <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
              {{ $error }}
            </div>
          @endforeach
        @endif

        @if (session('success'))
          <div class="alert bg-success alert-styled-left">
            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
            {{ session('success') }}
          </div>
        @endif

        <form id="batch-form" method="POST">
          {{ csrf_field() }}
          <input type="hidden" name="action" />
          <div id="businesses" class="panel panel-flat position-relative">
            <div class="loading-bar is-loading"><div class="slow"></div></div>
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>
                    <input type="checkbox" id="toggle-select-all" class="js-checkbox" />
                    <div class="dropdown display-inline-block">
                      <a href="#" data-toggle="dropdown">
                        <span class="caret"></span>
                      </a>
                      <ul class="dropdown-menu">
                        <li><a href="#" class="js-select-all">Chọn tất cả</a></li>
                        <li><a href="#" class="js-select-none">Bỏ chọn tất cả</a></li>
                        <li><a href="#" class="js-select-pending-activation">Doanh nghiệp chờ kích hoạt</a></li>
                        <li><a href="#" class="js-select-activated">Doanh nghiệp đã kích hoạt</a></li>
                        <li><a href="#" class="js-select-deactivated">Doanh nghiệp ngưng kích hoạt</a></li>
                      </ul>
                    </div>
                  </th>
                  <th data-sortable="true" data-sort-by="name">
                    Tên <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                  </th>
                  <th data-sortable="true" data-sort-by="start_date">
                    Ngày bắt đầu <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                  </th>
                  <th data-sortable="true" data-sort-by="end_date">
                    Ngày kết thúc <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                  </th>
                  <th data-hideable="true">
                    Logo <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Logo của Doanh nghiệp"></i>
                  </th>
                  <th data-hideable="true">
                    Group <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Logo của Doanh nghiệp"></i>
                  </th>
                  <th data-hideable="true">
                    S/p hiện có <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Logo của Doanh nghiệp"></i>
                  </th>
                  <th data-hideable="true">
                    Trạng thái <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Trạng thái tài khoản của Doanh nghiệp trên hệ thống iCheck for Business"></i>
                  </th>
                  <th data-hideable="true" data-sortable="true" data-sort-by="created_at">
                    Ngày đăng ký <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Ngày đăng ký của Doanh nghiệp trên hệ thống iCheck for Business"></i>
                  </th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </form>
      </div>
      <!-- /main content -->
    </div>
    <!-- /page content -->
  </div>
  <!-- /page container -->
</div>

<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="delete-modal-label">Xoá Doanh nghiệp</h4>
      </div>
      <div class="modal-body">
        Bạn có chắc chắn muốn xoá Doanh nghiệp <strong class="text-danger js-business-name"></strong> khỏi hệ thống của iCheck?
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

<div class="modal fade" id="batch-disapprove-modal" tabindex="-1" role="dialog" aria-labelledby="batch-disapprove-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="batch-disapprove-modal-label">Từ chối nhiều đơn đăng ký Doanh nghiệp</h4>
      </div>
      <form action="{{ route('Staff::Management::business@batchDisapprove') }}" class="js-batch-form" method="POST">
        <div class="modal-body">
          <div class="form-group">
            <label for="reason" class="control-label text-semibold">Lý do từ chối</label>
            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do từ chối đơn đăng ký cảu Doanh nghiệp"></i>
            <textarea id="reason" name="reason" rows="5" cols="5" class="form-control" placeholder="Lý do"></textarea>
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

<div class="modal fade" id="batch-activate-modal" tabindex="-1" role="dialog" aria-labelledby="batch-activate-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="batch-activate-modal-label">Kích hoạt nhiều Doanh nghiệp</h4>
      </div>
      <form action="{{ route('Staff::Management::business@batchActivate') }}" class="js-batch-form" method="POST">
        <div class="modal-body">
          <div class="form-group">
            Bạn có chắc chắn thực hiện hành động này?
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

<div class="modal fade" id="batch-deactivate-modal" tabindex="-1" role="dialog" aria-labelledby="batch-deactivate-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="batch-deactivate-modal-label">Huỷ kích hoạt nhiều Doanh nghiệp</h4>
      </div>
      <form action="{{ route('Staff::Management::business@batchDeactivate') }}" class="js-batch-form" method="POST">
        <div class="modal-body">
          <div class="form-group">
            Bạn có chắc chắn thực hiện hành động này?
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

<div class="modal fade" id="approve-modal" tabindex="-1" role="dialog" aria-labelledby="approve-modal-label" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="approve-modal-label">Chấp nhận đơn Đăng ký của doanh nghiệp</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="id" class="control-label text-semibold">Email đăng nhập</label>
            <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Email mà Doanh nghiệp sẽ dùng để đăng nhập vào hệ thống <strong>iCheck cho doanh nghiệp</strong>. <strong class='text-danger'>Email này phải là duy nhất</strong> trên toàn hệ thống <strong>iCheck cho doanh nghiệp</strong>."></i>
            <input type="email" id="email" name="login_email" class="form-control" required="required" />
          </div>

          <div class="form-group">
            Sử dụng mật khẩu ngẫu nhiên
            <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Hệ thống sẽ sẽ tạo ra một mật khẩu ngẫu nhiên cho Doanh nghiệp."></i>
            <a id="show-password-inputs" href="#">Đặt mật khẩu</a>
          </div>

          <div id="password-inputs" class="hidden">
            <div class="form-group">
              <label for="passwrod" class="control-label text-semibold">Mật khẩu</label>
              <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Mật khẩu đăng nhập vào hệ thống <strong>iCheck cho doanh nghiệp</strong> của Doanh nghiệp."></i>
              <input type="password" id="password" name="password" class="form-control" />
              <a id="hide-password-inputs" href="#">Sử dụng mật khẩu ngẫu nhiên</a>
            </div>

            <div class="form-group">
              <label for="password-confirmation" class="control-label text-semibold">Xác nhận Mật khẩu</label>
              <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Nhập lại mật khẩu ở trên."></i>
              <input type="password" id="password-confirmation" name="password_confirmation" class="form-control" />
            </div>

            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" id="password-change-required" name="password_change_required" class="js-checkbox">
                  <span class="text-semibold">Yêu cầu Doanh nghiệp đổi mật khẩu trong lần đăng nhập đầu tiên</span>
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          {{ csrf_field() }}
          <input type="hidden" name="_method" value="PUT">
          <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
          <button type="submit" class="btn btn-primary">Kích hoạt</button>
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
        <h4 class="modal-title" id="disapprove-modal-label">Từ chối yêu cầu đăng ký của Doamh nghiệp</h4>
      </div>
      <form method="POST">
        <div class="modal-body">
          <div class="form-group">
            <label for="reason" class="control-label text-semibold">Lý do từ chối</label>
            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do từ chối đơn đăng ký cảu Doanh nghiệp"></i>
            <textarea id="reason" name="reason" rows="5" cols="5" class="form-control" placeholder="Lý do"></textarea>
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

<div class="modal fade" id="batch-delete-modal" tabindex="-1" role="dialog" aria-labelledby="batch-delete-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="batch-delete-modal-label">Xoá nhiều Doanh nghiệp</h4>
      </div>
      <form action="{{ route('Staff::Management::business@batchDelete') }}" class="js-batch-form" method="POST">
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
  <script src="https://cdn.jsdelivr.net/uri.js/1.18.1/URI.min.js"></script>
  <script src="https://cdn.jsdelivr.net/backbone.localstorage/1.1.16/backbone.localStorage-min.js"></script>
  <script type="text/javascript" src="{{ asset('assets/js/backbone/backbone.paginator.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
@endpush

@push('scripts_foot')
  <script type="text/template" id="column-toggle-template">
    <label class="checkbox">
      <input type="checkbox" class="js-checkbox" data-index="@{{index}}" @{{#if show}} checked="checked" @{{/if}}>
      @{{title}}
    </label>
  </script>
  <script type="text/template" id="business-template">
    <td><input type="checkbox" name="selected[]" class="js-checkbox" value="@{{ id }}" @{{#if checked}} checked="checked" @{{/if}} /></td>
    <td><a href="@{{ links.self }}">@{{ name }}</a></td>
    <td>@{{ start_date }}</td>
    <td>@{{ end_date }}</td>
    <td>
      @{{#if logo}}
      <img src="@{{ logo }}" />
      @{{/if}}
    </td>
    <td>
      <select name="" id="@{{ id }}" class="form-control select-group">
        @{{#each listGroup}}
        <option value="@{{ this.id }}"
        @{{#if selected }}
          selected
        @{{/if}}
        >@{{ this.name }}</option>
        @{{/each}}
      </select>


    </td>
    <td>
      @{{ productExist }}
    </td>
    <td>
      @{{#if isActivated}}
        <span class="label label-success"><i class="icon-checkmark3"></i> @{{ status }}</span>
      @{{/if}}
      @{{#if isDeactivated}}
        <span class="label label-default"><i class="icon-checkmark3"></i> @{{ status }}</span>
      @{{/if}}
      @{{#if isPendingActivation}}
        <span class="label label-warning"><i class="icon-clock2"></i> @{{ status }}</span>

        <a href="@{{ links.self }}">Xem yêu cầu đăng ký</a>
      @{{/if}}
    </td>
    <td>@{{ created_at }}</td>
    <td>
      <div class="dropdown">
        <button id="business-@{{ id }}-actions" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link">
          <i class="icon-more2"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="business-@{{ id }}-actions">
          @{{#if isActivated}}
          <li><a href="@{{ links.product }}"><i class="icon-profile"></i> Xem sản phẩm</a></li>
            <li><a href="@{{ links.self }}"><i class="icon-profile"></i> Xem thông tin</a></li>


          @{{/if}}
          @{{#if isPendingActivation}}
            <li><a href="#" data-toggle="modal" data-target="#approve-modal" data-name="@{{ name }}" data-action-url="@{{ links.approve }}"><i class="icon-checkmark-circle"></i> Chấp nhận</a></li>
            <li><a href="#" data-toggle="modal" data-target="#disapprove-modal" data-name="@{{ name }}" data-action-url="@{{ links.disapprove }}"><i class="icon-cancel-circle"></i> Từ chối</a></li>
          @{{/if}}
          <li><a href="@{{ links.edit }}"><i class="icon-pencil5"></i> Sửa</a></li>
          <li><a href="#" data-toggle="modal" data-target="#delete-modal" data-name="@{{ name }}" data-action-url="@{{ links.delete }}"><i class="icon-bin"></i> Xoá</a></li>
        </ul>
      </div>
    </td>
  </script>
  <script>
  var uri = new URI;

  var Column = Backbone.Model.extend({
    defaults: function() {
      return {
        show: true
      }
    },

    idAttribute: 'index',

    toggle: function() {
      this.save('show', !this.get('show'));
    }
  });

  var ColumnView = Backbone.View.extend({
    tagName: 'li',
    template: Handlebars.compile($('#column-toggle-template').html()),

    events: {
      'change input[type="checkbox"]' : 'toggle'
    },

    initialize: function() {
      this.listenTo(this.model, 'change', this.render);
    },

    render: function() {
      this.$el.html(this.template(this.model.toJSON()));
      this.$('input[type="checkbox"]').uniform({
        radioClass: "choice"
      });

      return this;
    },

    toggle: function() {
      this.model.toggle();
    }
  });

  var ColumnList = Backbone.Collection.extend({
    model: Column,
    localStorage: new Backbone.LocalStorage('table-columns-' + uri.pathname())
  });

  var Business = Backbone.Model.extend({
    defaults: function() {
      return {
        checked: false
      }
    },

    toggleChecked: function() {
      this.set('checked', !this.get('checked'));
    }
  });

  var BusinessView = Backbone.View.extend({
    tagName: 'tr',
    template: Handlebars.compile($('#business-template').html()),

    events: {
      'click .js-checkbox' : 'toggleChecked'
    },

    initialize: function() {
      this.listenTo(this.model, 'change', this.render);
    },

    render: function() {
      this.$el.html(this.template(this.model.toJSON()));
      this.$el.toggleClass('active', this.model.get('checked'));
      this.$('.js-checkbox').uniform({
        radioClass: "choice"
      });

      return this;
    },

    toggleChecked: function() {
      this.model.toggleChecked();
    }
  });

  var BusinessList = Backbone.PageableCollection.extend({
    url: '{{ route('Ajax::Staff::Management::business@index') }}',

    parseState: function (response, queryParams, state, options) {
      return {
        totalRecords: response.meta.pagination.count ? response.meta.pagination.total : null
      };
    },

    parseRecords: function (response, options) {
      return response.data;
    },

    model: Business,

    checked: function() {
      return this.where({checked: true});
    },

    unchecked: function() {
      return this.where({checked: false});
    },

    activated: function() {
      return this.where({
        isActivated: true
      });
    },

    activatedAndChecked: function() {
      return this.where({
        isActivated: true,
        checked: true
      });
    },

    deactivated: function() {
      return this.where({
        isDeactivated: true
      });
    },

    deactivatedAndChecked: function() {
      return this.where({
        isDeactivated: true,
        checked: true
      });
    },

    pendingActivation: function() {
      return this.where({
        isPendingActivation: true
      });
    },

    pendingActivationAndChecked: function() {
      return this.where({
        isPendingActivation: true,
        checked: true
      });
    }
  });

  var AppView = Backbone.View.extend({
    el: $('#main-content'),

    events: {
      'submit #query-filter': 'changeQueryFilter',
      'change #status-filter': 'changeStatusFilter',
      'change #role-filter' : 'changeRoleFilter',
      'click #next-page-btn': 'loadNextPage',
      'click #prev-page-btn': 'loadPrevPage',
      'change .columns-toggle-item input[type="checkbox"]': 'toggleColumnList',
      'change #toggle-select-all': 'toggleSelectAll',
      'click .js-select-all': 'selectAll',
      'click .js-select-none': 'selectNone',
      'click .js-select-pending-activation': 'selectPendingActivation',
      'click .js-select-activated': 'selectActivated',
      'click .js-select-deactivated': 'selectDeactivated',
      'click [data-sortable="true"] > a': 'setSorting'
    },

    initialize: function() {
      var app = this,
          uriQueries = uri.search(true);

      // Batch buttons
      this.$btnBatchDelete = this.$("#btn-batch-delete");
      this.$btnBatchActivate = this.$("#btn-batch-activate");
      this.$btnBatchDeactivate = this.$("#btn-batch-deactivate");
      this.$btnBatchApprove = this.$("#btn-batch-approve");
      this.$btnBatchDisapprove = this.$("#btn-batch-disapprove");

      // Filters
      this.$queryFilter = this.$('#query-filter');
      this.$statusFilter = this.$('#status-filter');
      this.$roleFilter = this.$('#role-filter');
      this.lastQueryFilterValue = this.$queryFilter.find('input').val();

      // Pagination
      this.$fromRecord = this.$("#from-record");
      this.$toRecord = this.$("#to-record");
      this.$totalRecords = this.$("#total-records");
      this.$btnNextPage = this.$("#next-page-btn");
      this.$btnPrevPage = this.$("#prev-page-btn");

      // Table
      this.$selectAll = this.$("#toggle-select-all");
      this.$tableHead = this.$('table > thead');
      this.$tableBody = this.$('table > tbody');
      this.$loadingBar = this.$('.loading-bar');
      this.columns = new ColumnList();
      this.columns.fetch();
      this.currentPage = _.has(uriQueries, 'page') ? (parseInt(uriQueries.page) || 1) : 1;
      this.sortBy = _.has(uriQueries, 'sort_by') ? uriQueries.sort_by : null;
      this.order = _.has(uriQueries, 'order') ? uriQueries.order : null;

      this.$('th').each(function (i, elm) {
        var $elm = $(elm),
            column;

        if ($elm.data('hideable') === true) {
          // Nếu cột này chưa có trong storage, thì thêm vào
          if (!(column = app.columns.get($elm.index()))) {
            column = new Column({
              title: $elm.text(),
              index: $elm.index()
            });
            app.columns.unshift(column);
            column.save();
          }
        }

        if ($elm.data('sortable') === true) {
          var content = $elm.html(),
              $a = $('<a href="#"></a>'),
              $direction = $('<span class="sort-direction"></span>');

          $elm.html($a.html(content));

          if (app.order == 'desc') {
            $a.addClass('desc');
          }

          $a.addClass('sortable').append($direction);

          if (app.sortBy == $elm.data('sort-by')) {
            $a.addClass('active');
          }
        }
      });

      this.columns.sortBy(function (column) {
        return column.get('index');
      }).reverse().forEach(function (column) {
        var view = new ColumnView({
          id: 'toggle-column-' + column.get('index'),
          model: column
        });

        this.$("#columns-toggle-header").after(view.render().el);
      });

      this.businesses = new BusinessList([], {
        state: {
          pageSize: 20,
          sortKey: this.sortBy,
          order: this.order == 'asc' ? -1 : 1
        }
      });

      this.columns.on('change:show', this.toggleColumnList, this);

      this.businesses.on('request', this.showLoadingBar, this);
      this.businesses.on('sync', this.hideLoadingBar, this);
      this.businesses.on('sync', this.renderBatchButtons, this);
      this.businesses.on('sync', this.renderPagination, this);
      this.businesses.on('sync', this.renderSelectAllCheckbox, this);
      this.businesses.on('sync', this.renderTableBody, this);
      this.businesses.on('sync', this.toggleColumnList, this);
      this.businesses.on('change', this.renderBatchButtons, this);
      this.businesses.on('change', this.renderSelectAllCheckbox, this);

      this.refreshData();
    },

    renderBatchButtons: function () {
      var checked = this.businesses.checked().length,
          unchecked = this.businesses.unchecked().length,
          activatedAndChecked = this.businesses.activatedAndChecked().length,
          deactivatedAndChecked = this.businesses.deactivatedAndChecked().length,
          pendingActivationAndChecked = this.businesses.pendingActivationAndChecked().length;

      // Batch buttons
      this.$btnBatchDelete.prop('disabled', !checked);
      this.$btnBatchActivate.prop('disabled', !(deactivatedAndChecked && deactivatedAndChecked == checked));
      this.$btnBatchDeactivate.prop('disabled', !(activatedAndChecked && activatedAndChecked == checked));
      this.$btnBatchApprove.prop('disabled', !(pendingActivationAndChecked && pendingActivationAndChecked == checked));
      this.$btnBatchDisapprove.prop('disabled', !(pendingActivationAndChecked && pendingActivationAndChecked == checked));
    },

    renderPagination: function () {
      var hasNextPage = this.businesses.hasNextPage(),
          hasPreviousPage = this.businesses.hasPreviousPage(),
          state = this.businesses.state,
          fromRecord = (state.currentPage - 1) * state.pageSize + 1,
          toRecord = fromRecord + state.pageSize - 1,
          totalRecords = state.totalRecords || 0;

      if (totalRecords <= 0) {
        fromRecord = 0;
      }

      if (toRecord > totalRecords) {
        toRecord = totalRecords;
      }

      this.$fromRecord.text(fromRecord);
      this.$toRecord.text(toRecord);
      this.$totalRecords.text(totalRecords);

      this.$btnNextPage.prop('disabled', !hasNextPage);
      this.$btnPrevPage.prop('disabled', !hasPreviousPage);

      this.$('.dropdown-menu.dropdown-options').on('click', function(e) {
        e.stopPropagation();
      });
    },

    renderSelectAllCheckbox: function () {
      var checked = this.businesses.checked().length,
          unchecked = this.businesses.unchecked().length;

      this.$selectAll.prop('checked', !unchecked && checked);
      this.$selectAll.uniform({
        radioClass: "choice"
      });
    },

    renderTableBody: function() {
      this.$tableBody.empty();

      if (this.businesses.length) {
        this.businesses.each((business) => {
          this.addOne(business);
        });
      } else {
        this.$tableBody.append('<tr><td class="h1 text-center text-muted" colspan="' + this.$tableHead.find('tr > th').length + '"><i class="icon-info"></i> Không có dữ liệu tương ứng</td></tr>');
      }
    },

    getFilterQuery: function() {
      var query = {};

      if (this.$queryFilter.find('input').val()) {
        query['q'] = this.$queryFilter.find('input').val();
      }
      if(this.$roleFilter.val()){
        query['role'] = this.$roleFilter.val();
      }

      if (this.$statusFilter.val()) {
        query['status'] = this.$statusFilter.val();
      }

      return query;
    },

    refreshData: function (page) {
      this.businesses.getPage(page || this.currentPage, {
        data: this.getFilterQuery()
      });
    },

    loadNextPage: function (e) {
      e.preventDefault();

      var state = this.businesses.state;

      uri.setQuery({
        'page': state.currentPage + 1
      });
      history.replaceState(null, null, uri.toString());

      this.businesses.getNextPage({
        data: this.getFilterQuery()
      });
    },

    loadPrevPage: function (e) {
      e.preventDefault();

      var state = this.businesses.state;

      uri.setQuery({
        'page': state.currentPage - 1
      });
      history.replaceState(null, null, uri.toString());

      this.businesses.getPreviousPage({
        data: this.getFilterQuery()
      });
    },

    showLoadingBar: function () {
      this.$loadingBar
        .removeClass('is-completed')
        .addClass('is-loading');
    },

    hideLoadingBar: function () {
      this.$loadingBar
        .removeClass('is-loading')
        .addClass('is-completed');
    },

    addOne: function (business) {
      var view = new BusinessView({
        id: 'business-' + business.get('id'),
        model: business
      });

      this.$tableBody.append(view.render().el);
    },

    toggleSelectAll: function () {
      var checked = this.$selectAll.prop('checked');

      this.businesses.each(function (business) {
        business.set('checked', checked);
      });
    },

    selectAll: function (e) {
      e.preventDefault();

      this.businesses.each(function (business) {
        business.set('checked', true);
      });
    },

    selectNone: function (e) {
      e.preventDefault();

      this.businesses.each(function (business) {
        business.set('checked', false);
      });
    },

    selectPendingActivation: function (e) {
      e.preventDefault();

      this.businesses.pendingActivation().forEach(function (business) {
        business.set('checked', true);
      });
    },

    selectActivated: function (e) {
      e.preventDefault();

      this.businesses.activated().forEach(function (business) {
        business.set('checked', true);
      });
    },

    selectDeactivated: function (e) {
      e.preventDefault();

      this.businesses.deactivated().forEach(function (business) {
        business.set('checked', true);
      });
    },

    toggleColumnList: function () {
      var that = this;

      this.columns.each(function (column) {
        var id = column.get('index') + 1;

        that.$tableHead.find('th:nth-child(' + id + ')').toggle(column.get('show'));
        that.$tableBody.find('td:nth-child(' + id + ')').toggle(column.get('show'));
      });
    },

    changeQueryFilter: function (e) {
      e.preventDefault();

      var currentQuery = $(e.target).find('input').val();

      if (currentQuery != this.lastQueryFilterValue) {
        this.lastQueryFilterValue = currentQuery;
        uri.setQuery({
          'q': currentQuery,
          'page': 1
        });
        history.replaceState(null, null, uri.toString());
        this.refreshData();
      }
    },
    changeRoleFilter : function(e){
      uri.setQuery({
        'role': $(e.target).val(),
        'page': 1
      });
      history.replaceState(null, null, uri.toString());

      this.refreshData();
    },
    changeStatusFilter: function (e) {
      // Update url
      uri.setQuery({
        'status': $(e.target).val(),
        'page': 1
      });
      history.replaceState(null, null, uri.toString());

      this.refreshData();
    },

    setSorting: function (e) {
      e.preventDefault();

      var $a = $(e.target);

      // Remove all sortable has active class and add active class to current
      $a.parent().siblings().children('a').removeClass('active');
      $a.addClass('active').toggleClass('desc');

      // Update url
      uri.setQuery({
        'sort_by': $a.parent().data('sort-by'),
        'order': $a.hasClass('desc') ? 'desc' : 'asc'
      });
      history.replaceState(null, null, uri.toString());

      // Set sorting and refresh data
      this.businesses.setSorting($a.parent().data('sort-by'), $a.hasClass('desc') ? 1 : -1);
      this.refreshData();
    }
  });

  var App = new AppView;

  $('.js-batch-form').on('submit', function (e) {
    var $this = $(this),
        ids = [];

    $this.find('button[type="submit"]').prop('disabled', true);

    App.businesses.checked().forEach(function (business) {
      ids.push(business.get('id'));
    });

    var $input = $('<input>').attr({'type': 'hidden', 'name': 'ids'}).val(ids);
    $this.append($input);
  });

  $('#approve-modal, #disapprove-modal, #delete-modal').on('show.bs.modal', function (event) {
    var $btn = $(event.relatedTarget),
        $modal = $(this);

    $modal.find('form').attr('action', $btn.data('action-url'));
    $modal.find('.js-business-name').text($btn.data('name'));
  });

  // Toggle password inputs
  $(document).on('click', 'a#show-password-inputs', function (e) {
    e.preventDefault();

    $('#password-inputs').removeClass('hidden').prev().addClass('hidden');
  });

  $(document).on('click', 'a#hide-password-inputs', function (e) {
    e.preventDefault();

    $('#password-inputs').addClass('hidden').prev().removeClass('hidden');
  });


    $(document).on('change','.select-group',function(){
      var idRole = $(this).val();
      var id = $(this).attr('id');
      $.ajax({
        type:'POST',
        url: '{{ route('Ajax::Staff::Management::business@changeRole') }}',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        data: {
          'id' : id,
          'idRole' : idRole
        },
        success:function(data){
        },
        error: function(data){
          alert('Loi roi aaaaa!')
        }
      });
    });
  </script>
@endpush
