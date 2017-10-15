@extends('_layouts/default')

@section('content')
    <style>
        #input-barcode{
            padding-top: 0px !important;
            padding-bottom: 0px !important;
            padding-left: 5px;
            margin-top: -10px;
            border-width: 1px;
            border-color:#999;
        }
        #input-barcode:focus{
            border:none;
        }
    </style>
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>
                    <a href="{{ route('Business::notificationUser@index') }}" class="btn btn-link">
                        <i class="icon-arrow-left8"></i>
                    </a>
                    {{ isset($notification) ? 'Sửa thông báo ' . $notification->title : 'Tạo thông báo' }}
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
                <div class="row">
                    <div class="col-md-offset-3 col-md-6">
                       @include('_partials.flashmessage')
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <form method="POST" enctype="multipart/form-data" action="{{ isset($notification) ? route('Business::notificationUser@update', [$notification->id]) : route('Business::notificationUser@store') }}">
                                    {{ csrf_field() }}
                                    @if (isset($notification))
                                        <input type="hidden" name="_method" value="PUT">

                                    @endif

                                    <div class="form-group {{ $errors->has('content') ? 'has-error has-feedback' : '' }}">
                                        <label for="address" class="control-label text-semibold">Tin nhắn</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Tin nhắn"></i>
                                        <input type="text" id="content" name="content" maxlength="255" class="form-control"
                                               value="@if(isset($notification)){{$notification->content}}@else{{old('content')}}@endif"
                                        placeholder="Nhập nội dung"/>
                                        @if ($errors->has('content'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('content') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('type_object_to') ? 'has-error has-feedback' : '' }}">
                                        <label for="contact-info" class="control-label text-semibold">Loại đích đến</label>
                                        <i class="icon-notification4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Chọn loại đích đến"></i>
                                        <select name="type_object_to" class="js-select">
                                            @foreach (App\Models\Enterprise\DNNotificationUser::$typeObjectTo as $key => $value)
                                                <option value="{{$key}}" @if(isset($notification)and $notification->type_object_to == $key)selected @elseif(old('type_object_to') == $key) selected @endif>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('type_object_to'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('type_object_to') }}</div>
                                        @endif
                                    </div>
                                    <div class="form-group {{ $errors->has('object_to') ? 'has-error has-feedback' : '' }}">
                                        <label for="address" class="control-label text-semibold">Đích đến</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Đích đến"></i>
                                        <input type="text" name="object_to" maxlength="255" class="form-control"
                                               value="@if(isset($notification)){{$notification->object_to}}@else{{old('object_to')}}@endif"
                                               placeholder="Nhập đích đến"/>
                                        @if ($errors->has('object_to'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('object_to') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('type_send') ? 'has-error has-feedback' : '' }}">
                                        <label for="contact-info" class="control-label text-semibold">Loại gửi</label>
                                        <i class="icon-notification4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Chọn dịch vụ "></i>
                                        <select name="type_send" id="type_send" class="js-select">
                                            @foreach (App\Models\Enterprise\DNNotificationUser::$typeSend as $key => $value)
                                                <option value="{{$key}}" @if(isset($notification)and $notification->type_send == $key)selected @elseif(old('type_send') == $key) selected @endif>{{ $value }}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('type_send'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('type_send') }}</div>
                                        @endif
                                    </div>
                                    <div  id='div_time_send' style="display: none" class="form-group {{ $errors->has('time_send') ? 'has-error has-feedback' : '' }}">
                                        <label for="contact-info" class="control-label text-semibold">Chọn thời gian gửi</label>
                                        <i class="icon-notification4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Chọn dịch vụ "></i>
                                        @php $now = Carbon\Carbon::now()->format('H:i d-m-Y');
                                        @endphp
                                        <input type="text" class="form-control"  id="time_send" name="time_send"
                                               @if(isset($notification) and $notification->type_send == 2)
                                               value="{{$notification->time_send}}"
                                               @elseif(old('time_send'))
                                               value="{{old('time_send')}}"
                                                       @else
                                               value="{{$now}}"
                                                       @endif

                                        >

                                    </div>

                                    <div class="form-group {{ $errors->has('object_receive') ? 'has-error has-feedback' : '' }}">
                                        <label for="contact-info" class="control-label text-semibold">Đối tượng nhận</label>
                                        <i class="icon-notification4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Nhập đối tượng nhận"></i>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" class="styled" value="1" @if(isset($notification) and $notification->comment_product == 1) checked @elseif(old('comment_product') == 1) checked @endif name="comment_product">
                                                Comment sản phẩm
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" class="styled" value="1" @if(isset($notification) and $notification->like_product == 1) checked @elseif(old('like_product') == 1) checked @endif name="like_product">
                                                Like sản phẩm
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" class="styled" value="1" @if(isset($notification) and $notification->scan_product == 1) checked @elseif(old('scan_product') == 1) checked @endif name="scan_product">
                                                Scan sản phẩm
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="check_product" value="1" class="styled"  @if(isset($notification) and $notification->check_product == 1) checked @elseif(old('check_product') != 2)checked @endif>
                                                Toàn bộ sản phẩm
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <div style="width: 30%;float:left">
                                                <label>
                                                    <input type="radio" name="check_product" value="2" class="styled" @if(isset($notification) and $notification->check_product == 2) checked @elseif(old('check_product') == 2)checked @endif>
                                                    Danh sách sản phẩm
                                                </label>

                                            </div>
                                            <div style="width: 60%;float:left">
                                                <div class="form-group {{ $errors->has('list_barcode') ? 'has-error has-feedback' : '' }}">
                                                <input type="text" class="form-control" name="list_barcode" id="input-barcode"
                                                       @if(isset($notification) and $notification->check_product == 2)
                                                       value="{{$notification->list_barcode}}"
                                                               @else
                                                       value="{{old('list_barcode')}}"
                                                               @endif



                                                placeholder="Barcode sản phẩm cách nhau dấu ,">
                                                @if ($errors->has('list_barcode'))
                                                    <div class="form-control-feedback">
                                                        <i class="icon-notification2"></i>
                                                    </div>
                                                    <div class="help-block">{{ $errors->first('list_barcode') }}</div>
                                                @endif
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>



                                        @if ($errors->has('object_receive'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('object_receive') }}</div>
                                        @endif

                                    </div>



                                    <div class="text-right">
                                        <button type="reset" class="btn btn-info">Làm mới</button>
                                        <button type="submit" class="btn btn-primary">{{ isset($notification) ? 'Cập nhật' : 'Gửi' }}</button>
                                    </div>
                                </form>
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
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/anytime.min.js') }}"></script>
@endpush

@push('scripts_foot')
<script>
    $(document).ready(function () {
        $('.js-select').select2();
        if($('#type_send').val() == 2){
            $('#div_time_send').show();
        }
        $('#type_send').change(function(){
           var val = $(this).val();
            if(val == 2){
                $('#div_time_send').show();
            }else{
                $('#div_time_send').hide();
            }
        });
        $("#time_send").AnyTime_picker({
            format: "%H:%i %d-%m-%Y"
        });
        $(".styled").uniform({
            radioClass: 'choice'
        });
    });
</script>
@endpush
