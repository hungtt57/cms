@extends('_layouts/staff')

@section('content')

    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">

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
                <h5>Lượt tương tác </h5>
                <div class="row mb-20">
                    <div class="col-md-12">
                        <button id="date-range" type="button" class="btn btn-default">
                            <i class="icon-calendar position-left"></i>
                            <span></span>
                            <b class="caret"></b>
                        </button>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">

                        <!-- Search Form -->
                        <form role="form">

                            <!-- Search Field -->
                            <div class="row">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input class="form-control" type="text" value="" name="search"
                                               placeholder="Search"/>
                                        <span class="input-group-btn">
                      <button type="submit" class="btn btn-success btn-xs" data-toggle="modal" data-target="#edit-pro">Search</button>
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </form>
                        <!-- End of Search Form -->

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <div id="chart-per-day"></div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <div class="panel panel-flat">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>Vendor name</th>
                                            <th>show</th>
                                            <th>scan</th>
                                            <th>like</th>
                                            <th>comment</th>
                                            <th>click</th>
                                            <th>chat</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($vendors as $vendor)
                                            <tr>
                                                <td><a href="{{route('Staff::reportDashboard@vendorDetail',['id' => $vendor->id])}}">{{$vendor->name}}({{$vendor->id}})</a></td>
                                                <td>{{(isset($vendor_info[$vendor->id]['pro_show']) ? $vendor_info[$vendor->id]['pro_show'] : 0)}}</td>
                                                <td>{{(isset($vendor_info[$vendor->id]['pro_scan']) ? $vendor_info[$vendor->id]['pro_scan'] : 0)}}</td>
                                                <td>{{(isset($vendor_info[$vendor->id]['pro_like']) ? $vendor_info[$vendor->id]['pro_like'] : 0)}}</td>
                                                <td>{{(isset($vendor_info[$vendor->id]['pro_comment']) ? $vendor_info[$vendor->id]['pro_comment'] : 0)}}</td>
                                                <td>{{(isset($vendor_info[$vendor->id]['pro_click']) ? $vendor_info[$vendor->id]['pro_click'] : 0)}}</td>
                                                <td>{{(isset($vendor_info[$vendor->id]['pro_chat']) ? $vendor_info[$vendor->id]['pro_chat'] : 0)}}</td>
                                            </tr>
                                        @endforeach

                                        </tbody>


                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="float:right;">{!!$vendors->appends(Request::all())->render()!!}</div>
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



    $(document).ready(function () {
        var now = moment();

        Highcharts.setOptions({
            global : {
                useUTC : false
            }
        });



        var chart = new Highcharts.Chart({
            chart: {
                type: 'column',
                renderTo : 'chart-per-day'
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis:{
                type: 'datetime',
                minTickInterval: 24 * 3600 * 1000
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
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
            series: [
                {
                    name: 'Total Event',
                    data: {{json_encode($chartData)}}
                },
            ]
        });


    });
</script>


@endpush
