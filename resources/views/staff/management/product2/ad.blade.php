@extends('_layouts/staff')

@section('content')
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>
          <a href="" class="btn btn-link">
            <i class="icon-arrow-left8"></i>
          </a>
          Thêm A D theo GTIN
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
                <form method="POST" enctype="multipart/form-data" action="{{ route('Staff::Management::product2@ad') }}">
                  {{ csrf_field() }}
                  <div class="form-group {{ $errors->has('gtin') ? 'has-error has-feedback' : '' }}">
                    <label for="gtin" class="control-label text-semibold">GTIN</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                    <textarea class="form-control" name="gtin" id="gtin"></textarea>
                    @if ($errors->has('gtin'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('gtin') }}</div>
                    @endif
                  </div>
                    @if (isset($agencies))
                    <div class="form-group">
                      <label for="vendor" class="control-label text-semibold">Điểm bán</label>
                      <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên Nhà sản xuất hoặc Nhà phân phối sản phẩm"></i>

                      <div class="multi-select-full">
                        <select id="a-multiselect" name="agencies_selected[]" class="multiselect" multiple="multiple">
                          @foreach ($agencies as $agency)
                          <option value="{{ $agency->id }}" >{{ $agency->name }}</option>
                          @endforeach
                        </select>
                      </div>
                      <table class="table">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Điểm bán</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach ($agencies as $agency)
                      <tr id="a-{{ $agency->id }}" class="hidden">
                        <td><input type="checkbox" name="agencies[{{ $agency->id }}][enabled]" {{ isset($agenciesData[$agency->id]) ? ' checked="checked"' : '' }} value="1" /></td>
                        <td>{{ $agency->name }}</td>
                      </tr>
                      @endforeach
                      </tbody>
                      </table>
                    </div>
                    @endif

                    @if (isset($distributors))
                    <div class="form-group">
                      <label for="vendor" class="control-label text-semibold">Nhà phân phối</label>
                      <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên Nhà sản xuất hoặc Nhà phân phối sản phẩm"></i>
                      <div class="multi-select-full">
                        <select id="d-multiselect" name="distributors_selected[]" class="multiselect" multiple="multiple">
                          @foreach ($distributors as $distributor)
                          <option value="{{ $distributor->id }}" {{ isset($distributorsData[$distributor->id]) ? ' selected="selected"' : '' }} >{{ $distributor->name }}</option>
                          @endforeach
                        </select>
                      </div>
                      <table class="table">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Tên</th>
                          <th>Quốc gia</th>
                          <th>Độc quyền?</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach ($distributors as $distributor)
                      <tr id="d-{{ $distributor->id }}" class="hidden">
                        <td><input type="checkbox" name="distributors[{{ $distributor->id }}][enabled]" {{ isset($distributorsData[$distributor->id]) ? ' checked="checked"' : '' }} value="1" /></td>
                        <td>{{ $distributor->name }}</td>
                        <td>{{ @$distributor->country->name }}</td>
                        <td><input type="checkbox" name="distributors[{{ $distributor->id }}][is_monopoly]" {{ (isset($distributorsData[$distributor->id]) and $distributorsData[$distributor->id]['is_monopoly'] == 1) ? ' checked="checked"' : '' }} value="1" /></td>
                      </tr>
                      @endforeach
                      </tbody>
                      </table>
                    </div>
                    @endif


                    <div class="text-right">
                      <button type="submit" class="btn btn-primary">{{ isset($vendor) ? 'Cập nhật' : 'Thêm mới' }}</button>
                    </div>
                  </form>
              </div>
            </div>
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
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
@endpush

@push('scripts_foot')
<script>
  $(document).ready(function () {
    // Basic
    $(".js-select").select2();

    $('#a-multiselect').multiselect({
      enableCaseInsensitiveFiltering: true,
      enableFiltering: true,
        onChange: function(a, b) {
          var id = '#a-' + $(a).val();

          $(id).toggleClass('hidden', !b);
            $.uniform.update();
        }
    });

    $('#d-multiselect').multiselect({
      enableCaseInsensitiveFiltering: true,
      enableFiltering: true,
        onChange: function(d, b) {
          var id = '#d-' + $(d).val();

          $(id).toggleClass('hidden', !b);
            $.uniform.update();
        }
    });

    //
    // Select with icons
    //

    // Format icon
    function iconFormat(icon) {
      var originalOption = icon.element;
      if (!icon.id) { return icon.text; }
      var $icon = "<i class='icon-" + $(icon.element).data('icon') + "'></i>" + icon.text;

      return $icon;
    }

    // Initialize with options
    $(".select-icons").select2({
      templateResult: iconFormat,
      minimumResultsForSearch: Infinity,
      templateSelection: iconFormat,
      escapeMarkup: function(m) { return m; }
    });



    // Styled form components
    // ------------------------------

    // Checkboxes, radios
    $(".js-radio, .js-checkbox").uniform({ radioClass: "choice" });

    // File input
    $(".js-file").uniform({
      fileButtonClass: "action btn btn-default"
    });

    $(".js-tooltip, .js-help-icon").popover({
      container: "body",
      html: true,
      trigger: "hover",
      delay: { "hide": 1000 }
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

    @if ($errors->has('password'))
    $('a#show-password-inputs').trigger('click');
    @endif

  });
</script>
@endpush
