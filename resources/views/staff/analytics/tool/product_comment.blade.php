@extends('_layouts/staff')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>
                    Thống kê số lượng
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
                {{--<form class="mb-20">--}}
                {{--@if (Request::has('date_range'))--}}
                {{--<input type="hidden" name="date_range" value="{{ Request::input('date_range') }}">--}}
                {{--@endif--}}
                {{--<div class="row">--}}
                {{--<div class="col-md-6">--}}
                {{--<div class="form-group">--}}
                {{--<label>Lọc theo GLN</label>--}}
                {{--<select data-placeholder="Lọc theo Mã địa điểm toàn cầu" name="gln[]" multiple="multiple" class="select-border-color border-warning js-select">--}}
                {{--@foreach ($selectedGln as $number)--}}
                {{--<option value="{{ $number }}" selected="selected">{{ $number }}</option>--}}
                {{--@endforeach--}}
                {{--</select>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-md-6">--}}
                {{--<div class="form-group">--}}
                {{--<label>Lọc theo GTIN</label>--}}
                {{--<select data-placeholder="Lọc theo Sản phẩm" name="gtin[]" multiple="multiple" class="select-border-color border-warning js-select">--}}
                {{--@foreach ($selectedGtin as $number)--}}
                {{--<option value="{{ $number }}" selected="selected">{{ $number }}</option>--}}
                {{--@endforeach--}}
                {{--</select>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-md-12">--}}
                {{--<button type="submit" class="btn btn-primary">Lọc</button>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</form>--}}
                <div class="panel panel-flat panel-body">
                    <div id="chart_container"></div>
                </div>
                <div class="panel panel-flat">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Thời gian</th>
                            <th>Chia sẻ</th>
                            <th>Bình Luận chất lượng</th>
                            <th>Đóng góp</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($tableData as $key => $value)

                            <tr role="row">
                                <td>{{$key}}</td>
                                <td>{{$value['share']}}</td>
                                <td>{{$value['comment']}}</td>
                                <td>{{$value['contribute']}}</td>
                            </tr>
                        @endforeach
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


        $('#chart_container').highcharts({
            chart: {
                type: 'spline'
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                type: 'datetime',
                dateTimeLabelFormats: { // don't display the dummy year
                    month: '%e. %b',
                    year: '%b'
                },
                title: {
                    text: 'Date'
                }
            },
            yAxis: {
                title: {
                    text: 'Lượt'
                },
                stackLabels: {
                    enabled: false
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><strong>{point.y}</strong> lần</td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: false
                    }
                }
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Comment Chất lượng',
                data: {{json_encode($chartData['comment'])}}
            },
                {
                    name: 'Chia sẻ',
                    data: {{json_encode($chartData['share'])}}
                },
                {
                    name: 'Đóng góp sản phẩm',
                    data: {{json_encode($chartData['contribute'])}}
                }
            ]
        });


    });
</script>
@endpush
