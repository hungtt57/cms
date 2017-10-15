@extends('_layouts/default')

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

                <div class="panel panel-flat panel-body">
                    <div id="chart_container"></div>
                </div>
                <div class="panel panel-flat">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Thời gian</th>
                            <th>Số lần được hiện ra</th>
                            <th>Số lần được click</th>
                            <th>Số lượt scan</th>
                            <th>Số lượt like</th>
                            <th>Số lượt comment</th>
                            <th>Số lượt vote good</th>
                            <th>Số lượt vote normal</th>
                            <th>Số lượt vote bad</th>
                            <th>Số lượt review</th>
                            <th>Số lượt share icheck</th>
                            <th>Số lượt share facebook</th>
                            <th>Số lượt chat</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($dates as $key => $date)
                            <tr role="row">
                                <td>{{date('m-d-Y',$key)}}</td>
                                <td>{{(isset($date['pro_show']) ? $date['pro_show'] : 0)}}</td>
                                <td>{{(isset($date['pro_click']) ? $date['pro_click'] : 0)}}</td>
                                <td>{{(isset($date['pro_scan']) ? $date['pro_scan'] : 0)}}</td>
                                <td>{{(isset($date['pro_like']) ? $date['pro_like'] : 0)}}</td>
                                <td>{{(isset($date['pro_comment']) ? $date['pro_comment'] : 0)}}</td>
                                <td>{{(isset($date['pro_vote_good']) ? $date['pro_vote_good'] : 0)}}</td>
                                <td>{{(isset($date['pro_vote_normal']) ? $date['pro_vote_normal'] : 0)}}</td>
                                <td>{{(isset($date['pro_vote_bad']) ? $date['pro_vote_bad'] : 0)}}</td>
                                <td>{{(isset($date['pro_review']) ? $date['pro_review'] : 0)}}</td>
                                <td>{{(isset($date['pro_share_icheck']) ? $date['pro_share_icheck'] : 0)}}</td>
                                <td>{{(isset($date['pro_share_facebook']) ? $date['pro_share_facebook'] : 0)}}</td>
                                <td>{{(isset($date['pro_chat']) ? $date['pro_chat'] : 0)}}</td>
                            </tr>

                        @endforeach


                        </tbody>
                    </table>
                </div>
                <div style="float:right;">{!!$dates->appends(Request::all())->render()!!}</div>
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
            series: [
                    {
                name: 'Số lần được hiện ra',
                data: {{json_encode($pro_show)}}
            },
                {
                    name: 'Số lần được click',
                    data: {{json_encode($pro_click)}}
                },
                {
                    name: 'Số lượt scan',
                    data: {{json_encode($pro_scan)}}
                },
                {
                    name: 'Số lượt like',
                    data: {{json_encode($pro_like)}}
                },
                {
                    name: 'Số lượt comment',
                    data: {{json_encode($pro_comment)}}
                },
                {
                    name: 'Số lượt vote good',
                    data: {{json_encode($pro_vote_good)}}
                },
                {
                    name: 'Số lượt vote normal',
                    data: {{json_encode($pro_vote_normal)}}
                }, {
                    name: 'Số lượt vote bad',
                    data: {{json_encode($pro_vote_bad)}}
                }, {
                    name: 'Số lượt review',
                    data: {{json_encode($pro_review)}}
                },
                {
                    name: 'Số lượt share icheck',
                    data: {{json_encode($pro_share_icheck)}}
                }, {
                    name: 'Số lượt share facebook',
                    data: {{json_encode($pro_share_facebook)}}
                },
                {
                    name: 'Số lượt chat',
                    data: {{json_encode($pro_chat)}}
                },

            ]
        });


    });
</script>
@endpush
