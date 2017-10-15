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
                <h2>Tin nhắn</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                    <a href="{{route('Business::message@add')}}">
                        <button type="button" class="btn btn-primary" id="select-all">Tin nhắn mới</button>
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
                    <div class="row">
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control" type="text" name="content" placeholder="Nhập nội dung"
                                       value="{{ Request::input('content') }}"/>
                                                <span class="input-group-btn">
                            <button type="submit" class="btn btn-success btn-xs" data-toggle="modal"
                                    data-target="#edit-pro">Search</button>

                            </span>
                            </div>
                        </div>
                    </div>

                </form>
                <!-- End of Search Form -->

                @if (session('success'))
                    <div class="alert bg-success alert-styled-left">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        {{ session('success') }}
                    </div>
                @endif
                <div class="row">


                </div>
                <form id="main-form" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="reason" id="reasonall-form">
                    <div class="panel panel-flat">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th>Tin nhắn</th>
                                <th>Trạng thái</th>
                                <th>Thời gian</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach ($messages as $index => $message)
                                <tr role="row" id="product-{{ $message->id }}">
                                    <td>{{$message->content}}</td>
                                    <td>{{\App\Models\Enterprise\DNMessage::$statusTexts[$message->status]}}</td>
                                    <td>{{date_format($message->created_at,'H:i:s d/m/Y')}}</td>
                                    <td>
                                        <a href="{{route('Business::message@delete',['id' => $message->id])}}" onclick="return xoa()" class="btn btn-danger btn-xs">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="row" style="text-align: right">
                            {!! $messages->appends(Request::all())->links() !!}
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



