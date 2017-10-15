@extends('_layouts/staff')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Thống kê lịch sử tiền</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                    {{--<a href="{{ route('Staff::Management::collaborator@add') }}" class="btn btn-link"><i class="icon-add"></i> Thêm Cộng tác viên</a>--}}
                    {{--<a href="#"  class="btn btn-link btn-delete"><i class="icon-trash"></i> Xoá</a>--}}
                </div>
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
                    <div class="col-md-2">
                        <h5>Lượt tương tác </h5>
                    </div>
                    <div class="col-md-9">
                        <button id="date-range" type="button" class="btn btn-default">
                            <i class="icon-calendar position-left"></i>
                            <span></span>
                            <b class="caret"></b>
                        </button>
                    </div>

                </div>
                <form role="form">
                    <input type="hidden" value="{{Request::get('date_range')}}" name="date_range" id="date_range">

                    <div class="form-group ">

                        <input class="form-control" type="text" name="name" value="{{Request::get('name')}}" placeholder="Search tên " />
                        <span class="input-group-btn"></span>

                    </div>


                    <div class="form-group">
                        <label for="">Group</label>
                        <select name="group" class="form-control ">
                            <option value="0">Tất cả</option>
                            @foreach ($groups as $group)
                                <option  @if(Request::get('group') == $group->group_id) selected @endif value="{{ $group->group_id }}">{{ $group->group_id }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success btn-xs">Search</button>

                </form>






                @if (session('success'))
                    <div class="alert bg-success alert-styled-left">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="panel panel-flat">
                    <table class="table table-hover">
                        <thead>
                        <tr>

                            <th>Tên</th>
                            <th>Số tiền</th>
                            <th>Group</th>
                        </tr>
                        </thead>
                        <tbody>

                            @foreach ($histories as $index => $history)

                                <tr role="row" id="collaborator-">
                                    <td>{{ @$history->collaborator->name}}</td>
                                    <td>{{$history->money}}</td>
                                    <td>{{$history->group_id}}</td>
                                </tr>
                        @endforeach
                        </tbody>

                    </table>

                </div>
                {{--<div class="row" style="text-align: right">--}}
                    {{--{!! $histories->appends(Request::all())->links() !!}--}}
                {{--</div>--}}
            </div>
            <!-- /main content -->
        </div>
        <!-- /page content -->
    </div>
    <!-- /page container -->

    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="delete-modal-label">Xoá Sản phẩm</h4>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xoá Sản phẩm <strong class="text-danger js-collaborator-name"></strong> khỏi hệ thống của iCheck?
                </div>
                <div class="modal-footer">
                    <form method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
                        <button type="submit" class="btn btn-danger">Xác nhận</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="withdraw-money-modal" tabindex="-1" role="dialog" aria-labelledby="withdraw-money-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="withdraw-money-modal-label">Rút tiền của Cộng tác viên</h4>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            Bạn có muốn rút sạch tiền của <strong class="text-danger js-collaborator-name"></strong>?
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PUT">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
                        <button type="submit" class="btn btn-danger">Xác nhận</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
//            uri.setQuery({
//                'date_range': start.format('X') + '_' + end.format('X')
//            });
                $('#date_range').val(start.format('X') + '_' + end.format('X'));
//            window.location.href = uri.toString();
        }
    }

    changeHistoryRange(moment({{ $startDate->getTimestamp() }} * 1000), moment({{ $endDate->getTimestamp() }} * 1000), false);

    $dateRange.daterangepicker({
        opens: "right",
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



    $(".js-help-icon").popover({
        html: true,
        trigger: "hover",
        delay: { "hide": 1000 }
    });
    $('.btn-delete').click(function(){
        if(confirm('Bạn có chắc chắn muốn xóa CTV?')){
            $('#main-form').submit();
        };
    });
    $('#select-all').on('click', function () {
        $('.s').prop('checked', true);
    });

    $('#delete-modal').on('show.bs.modal', function (event) {
        var $btn = $(event.relatedTarget),
                $modal = $(this);

        $modal.find('form').attr('action', $btn.data('delete-url'));
        $modal.find('.js-collaborator-name').text($btn.data('name'));
    });

    $('#withdraw-money-modal').on('show.bs.modal', function (event) {
        var $btn = $(event.relatedTarget),
                $modal = $(this);

        $modal.find('form').attr('action', $btn.data('withdraw-money-url'));
        $modal.find('.js-collaborator-name').text($btn.data('name'));
    });

    //  $(".js-checkbox").uniform({ radioClass: "choice" });
</script>
@endpush



