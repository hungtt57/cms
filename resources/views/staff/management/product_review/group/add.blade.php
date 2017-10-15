@extends('_layouts/staff')

@section('page_title', 'Thêm nhóm')

@section('content')

  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>
          Thêm nhóm
        </h2>
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
        <div class="panel panel-flat">
          <div class="panel-body">
            <form method="POST" action="{{ route('Staff::Management::productReview@group@store') }}">
              {{ csrf_field() }}
              <div class="form-group">
                <label for="groups" class="control-label text-semibold">Nhóm muốn thêm</label>
                <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Danh mục"></i>
                <select id="groups" name="groups[]" multiple="multiple" class="select-border-color border-warning js-groups-select">
                  @foreach ($groups as $group)
                  <option value="{{ $group->id }}" data-icon="{{ $group->icon }}">{{ $group->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group {{ $errors->has('max_review') ? 'has-error has-feedback' : '' }}">
                <label for="max-review" class="control-label text-semibold">Số đánh giá tối đa</label>
                <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của sản phẩm"></i>
                <input type="text" id="max-review" name="max_review" class="form-control" value="{{ @$product->max_review }}" />
                @if ($errors->has('name'))
                  <div class="form-control-feedback">
                    <i class="icon-notification2"></i>
                  </div>
                  <div class="help-block">{{ $errors->first('max_review') }}</div>
                @endif
              </div>

              <div class="text-right">
                <button type="submit" class="btn btn-primary">Thêm mới</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- /main content -->
    </div>
    <!-- /page content -->
  </div>
  <!-- /page container -->
@endsection

@push('js_files_foot')
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
@endpush

@push('scripts_foot')
  <script>
  $(document).ready(function () {
    $(".js-help-icon").popover({
      html: true,
      trigger: "hover",
      delay: { "hide": 1000 }
    });

    // Initialize with options
    $(".js-groups-select").select2({
      templateResult: function (item) {
        if (!item.id) {
          return item.text;
        }

        var originalOption = item.element,
            item =  ($(item.element).data('level') ? '<img src="' + $(item.element).data('icon') + '" />' : '') + item.text;

        return item;
      },
      templateSelection: function (item) {
        return item.text;
      },
      escapeMarkup: function (m) {
        return m;
      },
      dropdownCssClass: 'border-primary',
      containerCssClass: 'border-primary text-primary-700'
    });

    // Initialize with options
    $(".js-select").select2();

    // Checkboxes, radios
    $(".js-radio").uniform({ radioClass: "choice" });

    // File input
    $(".js-file").uniform({
        fileButtonClass: "action btn btn-default"
    });

  $(document).on('submit', 'form', function () {
    $('button[type="submit"]').prop('disabled', true);
  });

  });
  </script>
@endpush

