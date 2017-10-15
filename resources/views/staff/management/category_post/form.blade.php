@extends('_layouts/staff')

@section('content')
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>
          <a href="{{ route('Staff::Management::categoryPost@index') }}" class="btn btn-link">
            <i class="icon-arrow-left8"></i>
          </a>
          {{ isset($cat) ? 'Sửa Category Post' : 'Thêm Category Post' }}
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
        <div class="row">
          <div class="col-md-offset-2 col-md-8">
            @if (session('success'))
              <div class="alert bg-success alert-styled-left">
                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                {{ session('success') }}
              </div>
            @endif
            <div class="panel panel-flat">
              <div class="panel-body">
                  <form method="POST" action="{{ isset($cat) ? route('Staff::Management::categoryPost@update', [$cat->id] ): route('Staff::Management::categoryPost@store') }}">
                  {{ csrf_field() }}
                   @if (isset($cat))
                    <input type="hidden" name="_method" value="PUT">
                    @endif
                            <!---------- Name------------>
                      <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
                        <label for="name" class="control-label text-semibold">Name</label>
                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') ?: @$cat->name }}" />
                        @if ($errors->has('name'))
                          <div class="form-control-feedback">
                            <i class="icon-notification2"></i>
                          </div>
                          <div class="help-block">{{ $errors->first('name') }}</div>
                        @endif
                      </div>

                    <!-------------------Description-------------------->
                    <div class="form-group {{ $errors->has('description') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Description</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                      <textarea name="description" class="form-control" id="" cols="30" rows="10">{{ old('description') ?: @$cat->description }}</textarea>
                      @if ($errors->has('description'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('description') }}</div>
                      @endif
                    </div>
                      <!-------------------Setting-------------------->
                      <div class="form-group {{ $errors->has('settings') ? 'has-error has-feedback' : '' }}">
                          <label for="name" class="control-label text-semibold">Settings</label>
                          <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                          <textarea name="settings" class="form-control" id="" cols="30" rows="10">{{ old('settings') ?: @$cat->settings }}</textarea>
                          @if ($errors->has('settings'))
                              <div class="form-control-feedback">
                                  <i class="icon-notification2"></i>
                              </div>
                              <div class="help-block">{{ $errors->first('settings') }}</div>
                          @endif
                      </div>
                      <!-------------------Setting-------------------->
                      <div class="form-group {{ $errors->has('keywords') ? 'has-error has-feedback' : '' }}">
                          <label for="name" class="control-label text-semibold">Settings</label>
                          <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                          <textarea name="keywords" class="form-control" id="" cols="30" rows="10">{{ old('keywords') ?: @$cat->keywords }}</textarea>
                          @if ($errors->has('keywords'))
                              <div class="form-control-feedback">
                                  <i class="icon-notification2"></i>
                              </div>
                              <div class="help-block">{{ $errors->first('keywords') }}</div>
                          @endif
                      </div>




                      <div class="text-right">
                      <button type="submit" class="btn btn-primary">{{ isset($cat) ? 'Cập nhật' : 'Thêm mới' }}</button>
                    </div>
                  </form>
              </div>
            </div>
          </div>
        </div>
      </div>
        </form>
      <!-- /main content -->
    </div>
    <!-- /page content -->
  </div>
  <!-- /page container -->
@endsection

@push('js_files_foot')
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
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
    $(".js-categories-select").select2({
      templateResult: function (item) {
        if (!item.id) {
          return item.text;
        }

        var originalOption = item.element,
                prefix = "----------".repeat(parseInt($(item.element).data('level'))),
                item =  (prefix ? prefix + '| ' : '') + item.text;

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

  });
</script>
@endpush
