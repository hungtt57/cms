@extends('_layouts/staff')

@section('content')
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>
          <a href="{{ route('Staff::Management::category@index') }}" class="btn btn-link">
            <i class="icon-arrow-left8"></i>
          </a>
          {{ isset($cat) ? 'Sửa Category ' : 'Thêm Category' }}
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
                  <form method="POST" action="{{ isset($cat) ? route('Staff::Management::category@update', [$cat->id] ): route('Staff::Management::category@store') }}">
                  {{ csrf_field() }}
                   @if (isset($cat))
                    <input type="hidden" name="_method" value="PUT">
                    @endif
                            <!---------- Name------------>
                      <div class="form-group {{ $errors->has('name_category') ? 'has-error has-feedback' : '' }}">
                        <label for="name" class="control-label text-semibold">Name</label>
                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                        <input type="text" id="name_category" name="name_category" class="form-control" value="{{ old('name_category') ?: @$cat->name }}" />
                        @if ($errors->has('name'))
                          <div class="form-control-feedback">
                            <i class="icon-notification2"></i>
                          </div>
                          <div class="help-block">{{ $errors->first('name_category') }}</div>
                        @endif
                      </div>

                    <!--------------------------------------->
                      <div class="form-group {{ $errors->has('id_parent') ? 'has-error has-feedback' : '' }}">
                        <label for="country" class="control-label text-semibold">Select Parent</label>
                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Quốc gia"></i>
                        <select id="id_parent" name="id_parent" class="js-select">
                          @foreach ($category as $row)
                            <option value="{{ $row->id }}" {{ ((old('id_parent') and old('id_parent') == $row->id) or (isset($cat) and $cat->parent_id == $row->id)) ? ' selected="selected"' : '' }}>{{ str_repeat('------------------', $row->level) }}
                              {{$row->name}}</option>
                          @endforeach
                        </select>
                        @if ($errors->has('id_parent'))
                          <div class="help-block">{{ $errors->first('id_parent') }}</div>
                        @endif
                      </div>

                    <div class="form-group">
                      <label for="country" class="control-label text-semibold">Chọn thuộc tính</label>
                      <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                         data-content="Thuộc tính"></i>
                      <select id="country" name="attrs[]" multiple="multiple"
                              class="select-border-color border-warning js-categories-select">
                        @foreach ($attrs as $attr)
                          <option value="{{ $attr->id }}"
                                  @if(isset($cat) and in_array($attr->id,$attributes))
                                    selected
                                    @endif
                                >{{ $attr->title}}</option>
                        @endforeach
                      </select>
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
