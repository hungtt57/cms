@extends('_layouts/default')
@push('styles_head')
<link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css">
<style>

    .dataTable thead .sorting:after, .dataTable thead .sorting_asc:after {
       display:none;
    }
    .dataTable thead .sorting_desc:after {
        display: none;
    }
    .dataTable thead .sorting:before {
       display: none;
    }
    thead tr th{
        border: 1px solid #ddd !important;
    }
</style>

@endpush

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
                                {{--<div class="form-group">--}}
                                {{--<div class="input-group">--}}
                                {{--<input class="form-control" type="text" value="" name="search"--}}
                                {{--placeholder="Search"/>--}}
                                {{--<span class="input-group-btn">--}}
                                {{--<button type="submit" class="btn btn-success btn-xs" data-toggle="modal" data-target="#edit-pro">Search</button>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                            </div>

                        </form>
                        <!-- End of Search Form -->

                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-flat">
                            <div class="panel-body">

                                    <div class="panel panel-flat">
                                        <table id="location-data" class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>City</th>
                                                <th>show</th>
                                                <th>scan</th>
                                                <th>like</th>
                                                <th>comment</th>
                                            </tr>
                                            </thead>
                                            <tbody>


                                            </tbody>


                                        </table>
                                    </div>
                            </div>
                        </div>
                    </div>


                </div>
                {{--@if($info_locations)--}}
                    {{--<div class="row" style="text-align: right">--}}
                        {{--{!!$info_locations->appends(Request::input())->render()!!}--}}
                    {{--</div>--}}
                {{--@endif--}}

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

<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/daterangepicker.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>

{{--<link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css">--}}
@endpush


@push('scripts_foot')
<script>

    $(function () {
        $('#location-data').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": '{{route('Business::statistical@getLocationData',['date_range' => $startDate->getTimestamp().'_'.$endDate->getTimestamp()])}}'
            },
            columns: [
                {data: 'location', name: 'location'},
                {data: 'show', name: 'show'},
                {data: 'scan', name: 'scan'},
                {data: 'like', name: 'like'},
                {data: 'comment', name: 'comment'}

            ]
        });
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

    changeHistoryRange(moment({{ $startDate->getTimestamp() }} * 1000), moment({{ $endDate->getTimestamp() }} * 1000
    ),
    false
    )
    ;

    $dateRange.daterangepicker({
        opens: "left",
        locale: {
            format: "DD/MM/YYYY",
        },
        startDate: moment({{ $startDate->getTimestamp() }} * 1000),
            endDate
    :
    moment({{ $endDate->getTimestamp() }} * 1000
    ),
    ranges: {
        'Hôm nay'
    :
        [moment(), moment()],
                'Hôm qua'
    :
        [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '7 ngày trước'
    :
        [moment().subtract(6, 'days'), moment()],
                '30 ngày trước'
    :
        [moment().subtract(29, 'days'), moment()],
                'Tháng này'
    :
        [moment().startOf('month'), moment().endOf('month')],
                'Tháng trước'
    :
        [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
    },
    changeHistoryRange
    )
    ;


    function xoaCat() {
        var conf = confirm("Bạn chắc chắn muốn xoá?");
        return conf;
    }


</script>


@endpush
