@extends('_layouts/staff')

@section('content')
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>
          <a href="{{ route('Staff::Management::business@index') }}" class="btn btn-link">
            <i class="icon-arrow-left8"></i>
          </a>
          Thêm mới Notification
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
          <div class="col-md-offset-3 col-md-6">
            @if (session('success'))
              <div class="alert bg-success alert-styled-left">
                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                {{ session('success') }}
              </div>
            @endif
            <div class="panel panel-flat">
              <div class="panel-body">
                <form method="POST" enctype="multipart/form-data" action="http://bus.runtime.mobi/staff@kimochi/operation/create">
                  {{ csrf_field() }}
                  <div class="form-group">
                    <label for="name" class="control-label text-semibold">Tên Notification</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                    <input type="text" id="name" name="name" class="form-control" value="" />
                    @if ($errors->has('name'))
                      <div class="help-block">{{ $errors->first('name') }}</div>
                    @endif
                  </div>
                  <div class="form-group ">
                    <label for="contact-info" class="control-label text-semibold">Nội dung</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Nội dung Notification" data-original-title="" title=""></i>
                    <textarea id="contact-info" name="content" rows="4" cols="4" class="form-control"></textarea>
                    @if ($errors->has('content'))
                      <div class="help-block">{{ $errors->first('content') }}</div>
                    @endif
                  </div>
                  <div class="form-group ">
                    <div class="display-block">
                      <label class="control-label text-semibold">Hình ảnh/Video</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Logo của Doanh nghiệp. Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 2Mb" data-original-title="" title=""></i>
                    </div>
                    <div class="media no-margin-top">
                      <div class="media-left">
                        <img src="http://bus.runtime.mobi/assets/images/image.png" style="width: 64px; height: 64px;" alt="">
                      </div>
                      <div class="media-body">
                        <div class="uploader">
                            <input type="file" name="image_video" class="js-file">
                            <span class="filename" style="-webkit-user-select: none;">No file selected</span><span class="action btn btn-default" style="-webkit-user-select: none;">Choose File</span></div>
                        <span class="help-block no-margin-bottom">Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 2Mb</span>
                        @if ($errors->has('image_video'))
                            <div class="help-block">{{ $errors->first('image_video') }}</div>
                        @endif
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                        <select class="form-control" id="status-filter" name="obj">
                          <option value="">Lựa chọn đối tượng</option>
                          <option value="1">Notification</option>
                          <option value="0">Pop Up</option>
                      </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-control" id="status-filter" name="status">
                          <option value="">Trạng thái</option>
                          <option value="1">Kích hoạt</option>
                          <option value="0">Ngưng kích hoạt</option>
                      </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-control" id="status-filter" name="cate">
                          <option value="">Hình thức</option>
                          <option value="1">Video</option>
                          <option value="0">Hình ảnh</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group ">
                      <input id="date-input" type="hidden" name="date_range" value="{{ Request::input('date_range') }}">
                      <div style="margin-top: 20px">
                        <label for="name" class="control-label text-semibold">Chọn ngày để hiển thị:</label>
                        <div class="col-md-12">
                          <button id="date-range" type="button" class="btn btn-default">
                            <i class="icon-calendar position-left"></i>
                            <span></span>
                            <b class="caret"></b>
                          </button>
                        </div>
                    </div>
                  </div>
                  <div class="text-right clearfix" style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">{{ isset($notification) ? 'Cập nhật' : 'Thêm mới' }}</button>
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
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/URI.js/1.18.1/URI.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/4.2.5/highcharts.js"></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/daterangepicker.js') }}"></script>
@endpush

@push('scripts_foot')
  <script>
  $(document).ready(function () {

    Highcharts.setOptions({
      global : {
        useUTC : false
      }
    });

    // Initialize with options
    $(".js-select").select2({
      dropdownCssClass: 'border-primary',
      containerCssClass: 'border-primary text-primary-700',
      tags: true
    });

    var uri = URI(window.location.href);
    var $dateRange = $('#date-range');

    function changeHistoryRange(start, end, newRange) {
      $dateRange.find('> span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
      $('#date-input').attr('value',start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
      if (newRange !== false) {
        uri.setQuery({
          'date_range': start.format('X') + '_' + end.format('X')
        });
      }
    }

    changeHistoryRange(moment({{ $startDate->getTimestamp() }} * 1000), moment({{ $endDate->getTimestamp() }} * 1000), false);

    $dateRange.daterangepicker({
      opens: "left",
      locale: {
        format: "DD/MM/YYYY",
      },
      startDate: moment({{ $startDate->getTimestamp() }} * 1000),
      endDate: moment({{ $endDate->getTimestamp() }} * 1000),
      ranges: {

      }
    }, changeHistoryRange);
    });
  </script>
@endpush
