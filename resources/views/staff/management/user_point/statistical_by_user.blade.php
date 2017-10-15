@extends('_layouts/staff')

@section('content')
    <style>
        .form-row {
            margin-bottom: 20px;
        }

        label {
            padding: 8px;
        }

        .body-top50 {
            overflow-x: hidden;
            height: 500px;
        }

        .error {
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }

        #point_achieved {
            padding-left: 5px;
        }

        .border-error {
            border: 1px solid red !important;
        }

    </style>
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h5>Thống kê theo user : </h5>
                @if (session('success'))
                    <div class="alert bg-success alert-styled-left">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert bg-danger alert-styled-left">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        {!! session('error')  !!}
                    </div>
                @endif
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

                <!-- Search Form -->
                <form role="form" class="form-row">

                    <!-- Search Field -->
                    <div class="row ">

                        <div class="form-group">
                            <div class="col-xs-1 label-div">
                                <label class="control-label cursor-pointer">Point</label>
                            </div>

                            <div class="col-xs-4">
                                <input type="text" name="point" value="{{Request::input('point')}}" class="form-control"
                                       id="clickable-price" placeholder="Ví dụ < 1000">
                            </div>
                        </div>

                    </div>

                    <div class="row ">
                        <div class="form-group">
                            <div class="col-xs-1 label-div">
                                <label class="control-label cursor-pointer">Icheck_id</label>
                            </div>

                            <div class="col-xs-4">
                                <input type="text" name="icheck_id" value="{{Request::input('icheck_id')}}"
                                       class="form-control" id="clickable-icheck" placeholder="Nhập icheck_id">
                            </div>
                        </div>
                    </div>


                    <div class="row ">
                        <div class="form-group">
                            <div class="col-xs-1 label-div">
                                <label class="control-label cursor-pointer">Name</label>
                            </div>

                            <div class="col-xs-4">
                                <input type="text" name="name" value="{{Request::input('name')}}" class="form-control"
                                       id="clickable-name" placeholder="Nhập name">
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="form-group">
                            <div class="col-xs-1 label-div">
                                <label class="control-label cursor-pointer">Source</label>
                            </div>

                            <div class="col-xs-4">
                                <input type="text" name="source" value="{{Request::input('source')}}"
                                       class="form-control" id="clickable-name" placeholder="Nhập source">
                            </div>
                        </div>
                    </div>

                    <div class="row ">

                        <div class="form-group">

                            <div class="col-xs-2">
                                <button type="submit" class="btn btn-success btn-xs legitRipple">Search<span
                                            class="legitRipple-ripple"></span></button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="col-xs-4">
                    <button id="top50" class="btn btn-warning btn-xs legitRipple">Top 50 User Cao Nhất<span
                                class="legitRipple-ripple"></span></button>
                </div>

            </div>

        </div>

        <!-- End of Search Form -->


        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-flat">
                    <div class="panel-body">
                        <div class="panel panel-flat">
                            <table class="table table-hover" id="table">
                                <thead>
                                <tr>
                                    <th>Icheck_id</th>
                                    <th>Tên</th>
                                    <th>
                                        <a href="#" class="sortable
                                               @if(Request::input('sort_by') == 'point' and Request::input('order') == 'asc') active asc @endif
                                        @if(Request::input('sort_by') == 'point' and Request::input('order') == 'desc') active desc @endif

                                                ">
                                            Số điểm đạt được <i
                                                    class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                                    data-original-title="" title=""></i>
                                            <span class="sort-direction"></span>
                                        </a>

                                    </th>
                                    <th>Source</th>
                                    <th>Số lần cộng điểm</th>
                                    <th>Action</th>

                                </tr>
                                </thead>
                                <tbody>

                                @foreach($users  as $user)
                                    <tr>
                                        <td>
                                            <a href="{{route("Staff::Management::userPoint@historyPoint",[$user->icheck_id])}}">{{$user->icheck_id}}</a>
                                        </td>
                                        <td>{{@$user->account->name}}</td>
                                        <td>
                                            <a class="update" data-id='{{$user->icheck_id}}'
                                               data-point="{{floatval($user->point)}}">{{floatval($user->point)}}<span
                                                        class="legitRipple-ripple"></span></a>
                                        </td>
                                        <td>{{$user->source}}</td>
                                        <td>{{$user->count}}</td>
                                        <td>
                                            <a class="bonus" data-id='{{$user->icheck_id}}'
                                               data-name='{{@$user->account->name}}'
                                               data-point="{{floatval($user->point)}}">Tặng thưởng point</a>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>


                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div class="row" style="text-align: right">
            {!! $users->appends(Request::all())->links() !!}
        </div>

        <div id="modal_theme_warning" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title">Top 50 User Điểm cao nhất</h6>
                    </div>

                    <div class="modal-body body-top50">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>STT</th>
                                <th>Icheck_id</th>
                                <th>Tên</th>
                                <th>Source</th>
                                <th>Số lần cộng điểm</th>
                                <th>Số điểm đạt được</th>


                            </tr>
                            </thead>
                            <tbody>

                            @foreach($top50  as $key => $top)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$top->icheck_id}}</td>
                                    <td>{{@$top->account->name}}</td>
                                    <td>{{$top->source}}</td>
                                    <td>{{$top->count}}</td>
                                    <td>
                                        <a class="update" data-id='{{$top->icheck_id}}'
                                           data-point="{{floatval($top->point)}}">{{floatval($top->point)}}<span
                                                    class="legitRipple-ripple"></span></a>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>


                        </table>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                        {{--<button type="button" class="btn btn-warning">Save changes</button>--}}
                    </div>
                </div>
            </div>
        </div>


        <div id="modal_update" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title">Số điểm có thể đổi thưởng : <span class='point'> </span></h6>
                    </div>
                    <form id="update-form" action="{{route('Staff::Management::userPoint@updatePoint')}}" method="POST">
                        <div class="modal-body">
                            {{ csrf_field() }}
                            <div class="row ">
                                <div class="form-group">
                                    <div class="col-xs-2 label-div">
                                        <label class="control-label cursor-pointer">Điểm</label>
                                    </div>

                                    <div class="col-xs-10">
                                        <input type="hidden" id="update_icheck_id" name="update_icheck_id">
                                        <input type="number" id="point_achieved" name="point_achieved" value=""
                                               class="form-control" placeholder="Nhập số điểm đã quy đổi quà">
                                        <p class="error hide">Vui lòng nhập số điểm nhỏ hơn số điểm hiện có</p>
                                    </div>
                                </div>
                            </div>


                        </div>

                    </form>
                    <div class="modal-footer">
                        <div class="col-md-8">

                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Bỏ qua</button>
                        </div>
                        <div class="col-md-2">
                            <button type="button" id="button-update" class="btn btn-warning">Cập nhật</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal_bonus" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">Tặng điểm thưởng cho : <span class='bonus-name'> </span></h6>
                </div>
                <form id="bonus-form" action="{{route('Staff::Management::userPoint@bonusPoint')}}" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <div class="row ">
                            <div class="form-group">
                                <div class="col-xs-2 label-div">
                                    <label class="control-label cursor-pointer">Điểm</label>
                                </div>

                                <div class="col-xs-10">
                                    <input type="hidden" id="bonus_icheck_id" name="icheck_id">
                                    <input type="number" id="point_bonus" name="bonus_point" min="1"
                                           class="form-control" placeholder="Nhập điểm muốn tặng">
                                </div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="form-group">
                                <div class="col-xs-2 label-div">
                                    <label class="control-label cursor-pointer">Lời nhắn</label>
                                </div>

                                <div class="col-xs-10">
                                    <textarea required name="message" id="bonus-message" class="form-control"
                                              placeholder="Nhập lý do"></textarea>
                                </div>
                            </div>
                        </div>


                    </div>

                </form>
                <div class="modal-footer">
                    <div class="col-md-8">

                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Bỏ qua</button>
                    </div>
                    <div class="col-md-2">
                        <button type="button" id="button-bonus" class="btn btn-warning">Tặng</button>
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
        var point = 0;
        $('#top50').click(function (e) {
            e.preventDefault();
            $('#modal_theme_warning').modal('show');

        });
        $('.update').click(function (e) {
            point = $(this).attr('data-point');
            icheck_id = $(this).attr('data-id');
            $('.point').text(point);
            $('#update_icheck_id').val(icheck_id);
            $('#modal_theme_warning').modal('hide');
            $('#modal_update').modal('show');

        });
        $('.bonus').click(function (e) {
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            $('.bonus-name').text(name);
            $('#bonus_icheck_id').val(id);
            $('#modal_theme_warning').modal('hide');
            $('#modal_bonus').modal('show');

        });
        $('#point_achieved').keyup(function (e) {

            if (parseInt($(this).val()) > point && parseInt($(this).val()) != 0) {
                $(this).addClass('border-error');
                $('.error').addClass('show');
                $('.error').removeClass('hide');
                $('#button-update').removeClass('show');
                $('#button-update').addClass('hide');

            } else {
                $(this).removeClass('border-error');
                $('.error').removeClass('show');
                $('.error').addClass('hide');
                $('#button-update').removeClass('hide');
                $('#button-update').addClass('show');
            }
            if (parseInt($(this).val()) == 0) {
                $('#button-update').removeClass('show');
                $('#button-update').addClass('hide');
            }
        });
        $('#button-bonus').click(function (e) {
            if ($('#point_bonus').val() > 0  && $('#bonus-message').val() != '') {
                if (confirm("Bạn có chắc chắn muốn cập nhật số điểm")) {
                    $('#bonus-form').submit();
                } else {

                }
            } else {
                alert('Vui lòng nhập số điểm > 0 và lý do');
            }


        });
        $('#button-update').click(function (e) {
            if ($('#point_achieved').val() != 0) {
                if (confirm("Bạn có chắc chắn muốn cập nhật số điểm")) {
                    $('#update-form').submit();
                } else {

                }
            } else {
                alert('Vui lòng nhập số điểm khác 0');
            }


        });

        $('.sortable').click(function () {
            var uri = URI(window.location.href);
            var order = 'desc';
            if ($(this).hasClass('desc')) {
                order = 'asc';
            } else {
                order = 'desc';
            }
            uri.setQuery({
                'sort_by': 'point',
                'order': order
            });
            window.location.href = uri.toString();
        });
//        $('#table').DataTable()
    });

</script>


@endpush
