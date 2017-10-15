@extends('_layouts/default')

@section('content')
    <style>
        .properties-block label {
            word-wrap: break-word;
        }

        .col-md-6 label {
            padding-top: 8px;
            padding-bottom: 8px;

        }

    </style>
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>HighLight</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                    <a href="{{route('Business::message@add')}}">
                        <button type="button" class="btn btn-primary" id="select-all">HighLight mới</button>
                    </a>

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

                <!-- Search Form -->
                <form role="form">

                    <!-- Search Field -->
                    {{--<div class="row">--}}
                        {{--<div class="form-group">--}}
                            {{--<div class="input-group">--}}
                                {{--<input class="form-control" type="text" name="content" placeholder="Nhập nội dung"--}}
                                       {{--value="{{ Request::input('content') }}"/>--}}
                                {{--<span class="input-group-btn">--}}
                            {{--<button type="submit" class="btn btn-success btn-xs" data-toggle="modal"--}}
                                    {{--data-target="#edit-pro">Search</button>--}}

                            {{--</span>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                </form>
                <!-- End of Search Form -->


                <form id="main-form" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="reason" id="reasonall-form">
                    <div class="panel panel-flat">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th>Tiêu đề</th>
                                <th>Icon</th>
                                <th>Thời gian tạo</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach ($highLights as $index => $highLight)
                                <tr role="row" id="product-{{ $highLight->id }}">
                                    <td>{{$highLight->title}}</td>
                                    <td>
                                        <img src="{{$highLight->icon}}" width="100" height="100" alt="">
                                    </td>
                                    <td>{{date_format($highLight->created_at,'H:i:s d/m/Y')}}</td>
                                    <td>
                                        <a href="{{route('Business::highlight@edit',['id' => $highLight->id])}}"  class="btn btn-info btn-xs">Sửa</a>
                                        {{--<a href="{{route('Business::message@delete',['id' => $message->id])}}" onclick="return xoa()" class="btn btn-danger btn-xs">Delete</a>--}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="row" style="text-align: right">
                            {!! $highLights->appends(Request::all())->links() !!}
                            <div style="clear: both"></div>
                        </div>
                    </div>
                </form>
                <div id="answer-question">

                </div>
            </div>
            <!-- /main content -->
        </div>
        <!-- /page content -->
    </div>
    <!-- /page container -->




@endsection

@push('js_files_foot')
<script type="text/javascript"
        src="{{ asset('https://cdn.jsdelivr.net/jsbarcode/3.3.14/JsBarcode.all.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
@endpush

@push('scripts_foot')
<script>
    function xoa(){
        if(confirm('Bạn có chắc chắn muốn xóa')){
            return true;
        }
        return false;

    }
    $(document).ready(function () {
        // get answer

    });

</script>
@endpush



