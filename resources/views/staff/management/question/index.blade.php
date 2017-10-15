@extends('_layouts/staff')

@section('content')
    <style>
        .properties-block label {
            word-wrap: break-word;
        }

        .col-md-6 label {
            padding-top: 8px;
            padding-bottom: 8px;

        }
        #answer-question{
            padding-top: 20px;
        }
    </style>
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Câu hỏi của doanh nghiệp</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                    {{--<a href="{{route('Business::question@add')}}">--}}
                    {{--<button type="button" class="btn btn-primary" id="select-all">Gửi yêu cầu</button>--}}
                    {{--</a>--}}

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
                        <div class="col-md-4">
                            <div class="form-group">
                                    <input class="form-control" type="text" name="title" placeholder="Search by title" value="{{ Request::input('title') }}"/>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <select class="form-control" name="status" id="status-filter">
                                    <option value="">Tất cả</option>
                                    @foreach(App\Models\Enterprise\DNQuestion::$statusTexts as $key => $value)
                                        <option value="{{$key}}"
                                                @if(Request::has('status') && Request::get('status') == $key) selected @endif>{{$value}}</option>
                                        @endforeach

                                </select>
                            </div>
                        </div>

                        <span class="input-group-btn">
                        <button type="submit" class="btn btn-success btn-xs" data-toggle="modal"
                                data-target="#edit-pro">Search</button>

                        </span>
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

                <form id="main-form" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="reason" id="reasonall-form">
                    <div class="panel panel-flat">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th>Mã</th>
                                <th>Tiêu đề</th>
                                <th>Gửi đến</th>
                                <th>Dịch vụ</th>
                                <th>Đính kèm</th>
                                <th>Doanh nghiệp</th>
                                <th>Trạng thái</th>
                                <th>Thời gian</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach ($questions as $index => $question)
                                <tr role="row" id="product-{{ $question->id }}">
                                    <td><a href="" class="code" data-code="{{$question->code}}">#{{$question->code}}</a></td>
                                    <td>{{$question->title}}</td>
                                    <td>{{\App\Models\Enterprise\DNQuestion::$rooms[$question->room]}}</td>
                                    <td>{{\App\Models\Enterprise\DNQuestion::$services[$question->service]}}</td>
                                    <td>
                                        @if($question->attachments)
                                        <a href="{{route('Staff::Management::question@getFile',['id' => $question->id])}}" target="_blank">File</a>
                                    @endif
                                    </td>
                                    <td>{{@$question->business->name}}</td>
                                    <td>{{\App\Models\Enterprise\DNQuestion::$statusTexts[$question->status]}}</td>
                                    <td>{{date_format($question->created_at,'H:i:s d/m/Y')}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                    <div class="row" style="text-align: right">
                        {!! $questions->appends(Request::all())->links() !!}
                        <div style="clear: both"></div>
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
        $(document).ready(function(){
            // get answer
            $('.code').click(function(e){
                var code = $(this).attr('data-code');
                e.preventDefault();
                var url = "{{route('Staff::Management::question@getAnswerQuestion')}}";
                if(code) {
                    $.ajax({
                    type: "POST",
                    url: url,
                    headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                    },
                    data: {
                        code : code
                    },
                    dataType: 'html',
                    success: function (data) {
                        $('#answer-question').html(data);
                    },
                    error: function () {

                    }
                    });
                }

            });

            $(document).on('click','#button-send',function(){
                var content = $('#content-send').val();
                var status = $('#status_rep_answer').is(":checked");
                if(status){
                    status = 1;
                }else{
                    status = 0;
                }
                var id = $(this).attr('data-id');
                   if(content){
                       var url = "{{route('Staff::Management::question@addAnswerQuestion')}}";
                       $.ajax({
                           type: "POST",
                           url: url,
                           headers: {
                               'X-CSRF-Token': "{{ csrf_token() }}"
                           },
                           data: {
                               content : content,
                               id : id,
                               status : status
                           },
                           dataType: 'html',
                           success: function (data) {
                             $('#list-answer').append(data);
                               $('#content-send').val('');
                           },
                           error: function () {

                           }
                       });
                   }else{
                       alert('Vui lòng nhập nội dung!!');
                   }
            });
        });

</script>
@endpush



