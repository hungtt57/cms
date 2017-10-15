@extends('_layouts/staff')

@section('content')
    <style>
        .form-row{
            margin-bottom: 20px;
        }
        label{
            padding: 8px;
        }
        .body-top50{
            overflow-x: hidden;
            height: 500px;
        }
    </style>
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h5>Lịch sử trả điểm: </h5>
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
                                <label class="control-label cursor-pointer" for="clickable-price">Source</label>
                            </div>

                            <div class="col-xs-4">
                                <input type="text" name="source" value="{{Request::input('source')}}" class="form-control" id="clickable-price" placeholder="Nhập source">
                            </div>
                            <button type="submit" class="btn btn-success btn-xs" >Search</button>
                        </div>

                    </div>



                </form>
                <!-- End of Search Form -->


                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <div class="panel panel-flat">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>Ngày</th>
                                            <th>Hành động</th>
                                            <th>Điểm</th>
                                            <th>Source</th>
                                            <th>Object_type</th>
                                            <th>Object_id</th>

                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($histories  as $history)
                                            <tr>
                                                <td>{{$history->createdAt}}</td>
                                                <td>{{$history->action}}</td>
                                                <td>{{floatval($history->point)}}</td>
                                                <td>{{$history->source}}</td>
                                                <td>{{$history->object_type}}</td>
                                                <td>{{$history->object_id}}</td>
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
                    {!! $histories->appends(Request::all())->links() !!}
                </div>

                {{--<div id="modal_theme_warning" class="modal fade">--}}
                    {{--<div class="modal-dialog">--}}
                        {{--<div class="modal-content">--}}
                            {{--<div class="modal-header bg-warning">--}}
                                {{--<button type="button" class="close" data-dismiss="modal">&times;</button>--}}
                                {{--<h6 class="modal-title">Top 50 User Điểm cao nhất</h6>--}}
                            {{--</div>--}}

                            {{--<div class="modal-body body-top50">--}}
                                {{--<table class="table table-hover">--}}
                                    {{--<thead>--}}
                                    {{--<tr>--}}
                                        {{--<th>STT</th>--}}
                                        {{--<th>Icheck_id</th>--}}
                                        {{--<th>Tên</th>--}}
                                        {{--<th>Số điểm đạt được</th>--}}

                                    {{--</tr>--}}
                                    {{--</thead>--}}
                                    {{--<tbody>--}}

                                    {{--@foreach($top50  as $key => $top)--}}
                                        {{--<tr>--}}
                                            {{--<td>{{$key+1}}</td>--}}
                                            {{--<td>{{$top->icheck_id}}</td>--}}
                                            {{--<td>{{$top->account->name}}</td>--}}
                                            {{--<td>{{floatval($top->point)}}</td>--}}
                                        {{--</tr>--}}
                                    {{--@endforeach--}}

                                    {{--</tbody>--}}


                                {{--</table>--}}
                            {{--</div>--}}

                            {{--<div class="modal-footer">--}}
                                {{--<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>--}}
                                {{--<button type="button" class="btn btn-warning">Save changes</button>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
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
        $('#top50').click(function(e){
            e.preventDefault();
            $('#modal_theme_warning').modal('show');

        });
    });

</script>


@endpush
