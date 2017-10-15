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
          {{ isset($survey) ? 'Sửa  ' : 'Thêm ' }}
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
                <form method="POST" enctype="multipart/form-data" action="{{ isset($survey) ? route('Staff::Management::survey@update', [$survey->id] ): route('Staff::Management::survey@store') }}">
                  {{ csrf_field() }}
                  @if (isset($survey))
                    <input type="hidden" name="_method" value="PUT">
                  @endif
                          <!----- Upload Image Here ---->
                    <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
                      <div class="display-block">
                        <label class="control-label text-semibold">Image</label>
                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Logo của Doanh nghiệp. Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 2Mb"></i>
                      </div>
                      <div class="media no-margin-top">
                        <div class="media-left">
                          <img src="{{ (isset($survey) and $survey->image) ? get_image_url($survey->image, 'thumb_small') : asset('assets/images/image.png') }}" style="width: 64px; height: 64px;" alt="">
                        </div>
                        <div class="media-body">
                          <input type="file" name="image" class="js-file">
                          <span class="help-block no-margin-bottom">Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 2Mb</span>
                        </div>
                      </div>
                      @if ($errors->has('image'))
                        <div class="help-block">{{ $errors->first('image') }}</div>
                      @endif
                    </div>
                  <!---------- Message------------>
                  <div class="form-group {{ $errors->has('message') ? 'has-error has-feedback' : '' }}">
                    <label for="name" class="control-label text-semibold">Message</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                    <input type="text" id="message" name="message" class="form-control" value="{{ old('message') ?: @$survey->message }}" />
                    @if ($errors->has('message'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('message') }}</div>
                    @endif
                  </div>
                  <!------------------ Link--------------->
                    <div class="form-group {{ $errors->has('link') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Link</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                      <input type="text" id="link" name="link" class="form-control" value="{{ old('link') ?: @$survey->link }}" />
                      @if ($errors->has('link'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('link') }}</div>
                      @endif
                    </div>
                  <!------------------------- Location-------------------->
                    <div class="form-group {{ $errors->has('location') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Location</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                      <input type="text" id="location" name="location" class="form-control" value="{{ old('location') ?: implode(',', @$survey->location ?: []) }}" />
                      @if ($errors->has('location'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('location') }}</div>
                      @endif
                    </div>


                  <!----- Status ---->
                    <div class="panel panel-flat">
                      <div class="table-responsive">
                        <table class="table table-hover">
                          <thead>
                          <tr>
                            <th>Status</th>
                            <th>True</th>
                            <th>False</th>
                          </tr>
                          </thead>
                          <tbody>
                            <tr role="row" id="">

                              <td></td>
                              <td>
                                <div class="radio">
                                  <label class="radio-inline">
                                    <input type="radio" name="status"  class="js-radio" value="1" {{ (isset($survey->status) and $survey->status == 'true') ? ' checked="checked"' : ''  }} >
                                  </label>
                                </div>
                              </td>

                              <td>
                                <div class="radio">
                                  <label class="radio-inline">
                                    <input type="radio" name="status"   class="js-radio" value="0" {{ (isset($survey->status) and $survey->status == false) ? ' checked="checked"' : ''  }}>
                                  </label>
                                </div>
                              </td>

                            </tr>
                          </tbody>
                        </table>


                      </div>

                    </div>
                  <!-------------Put_first--------------->
                    <div class="panel panel-flat">
                      <div class="table-responsive">
                        <table class="table table-hover">
                          <thead>
                          <tr>
                            <th>Put_first</th>
                            <th>
                    <input type="text" id="put_first" name="put_first" class="form-control" value="{{ old('put_first') ?: @$survey->put_first }}" /></th>
                          </tr>
                          </thead>
                        </table>


                      </div>

                    </div>




                    <div class="text-right">
                      <button type="submit" class="btn btn-primary">{{ isset($survey) ? 'Cập nhật' : 'Thêm mới' }}</button>
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
@endpush

@push('scripts_foot')
<script>
  $(document).ready(function () {
    // Basic
    $(".js-select").select2();

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
