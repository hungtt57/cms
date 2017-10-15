@extends('_layouts/staff')

@section('content')
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>
          <a href="{{ route('Business::product@index') }}" class="btn btn-link">
            <i class="icon-arrow-left8"></i>
          </a>
          Thống kê user hoạt động
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
        <div class="row mb-20">
          <div class="col-md-12">
            <button id="date-range" type="button" class="btn btn-default">
              <i class="icon-calendar position-left"></i>
              <span></span>
              <b class="caret"></b>
            </button>
          </div>
        </div>
        <form class="mb-20">
          @if (Request::has('date_range'))
          <input type="hidden" name="date_range" value="{{ Request::input('date_range') }}">
          @endif
          <div class="row">
            <div class="col-md-12">
              <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
            </div>
          </div>
        </form>
        <div class="panel panel-flat">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Tên FB</th>
                <th>Fb Id</th>
                <th>Email</th>
                <th>Sđt</th>
                
                <th>Khu Vực</th>
                <th>Số người theo dõi</th>
                <th>Số bài viết</th>
                <th>Hoạt động</th>
                
                <th>Time</th>
                <th>Time Average</th>
              </tr>
            </thead>
            <tbody>
              
            </tbody>
          </table>
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

      if (newRange !== false) {
        uri.setQuery({
          'date_range': start.format('X') + '_' + end.format('X')
        });

        window.location.href = uri.toString();
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
         'Hôm nay': [moment(), moment()],
         'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
         '7 ngày trước': [moment().subtract(6, 'days'), moment()],
         '30 ngày trước': [moment().subtract(29, 'days'), moment()],
         'Tháng này': [moment().startOf('month'), moment().endOf('month')],
         'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      }
    }, changeHistoryRange);
    });
  </script>
@endpush
