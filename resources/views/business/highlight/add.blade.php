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
                    <a href="{{ route('Business::highlight@index') }}" class="btn btn-link">
                        <i class="icon-arrow-left8"></i>
                    </a>
                    Tạo HighLight
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
                                <form method="POST" enctype="multipart/form-data" action="{{ isset($highlight) ? route('Business::highlight@update', [$highlight->id]) : route('Business::highlight@store') }}">
                                    {{ csrf_field() }}
                                    @if (isset($highlight))
                                        <input type="hidden" name="_method" value="PUT">

                                    @endif

                                    <div class="form-group {{ $errors->has('title') ? 'has-error has-feedback' : '' }}">
                                        <label for="name" class="control-label text-semibold">Tiêu đề</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Tên của sản phẩm"></i>
                                        <input type="text" id="title" name="title" class="form-control"
                                               value="@if(isset($highlight)){{$highlight->title}} @else {{old('title')}} @endif"/>
                                        @if ($errors->has('title'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('title') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('icon') ? 'has-error' : '' }}">
                                        <div class="display-block">
                                            <label class="control-label text-semibold">Icon</label>
                                            <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Icon của hightlight. Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 2Mb"></i>
                                        </div>
                                        <div class="media no-margin-top">
                                            <div class="media-left">
                                                <img src="{{ (isset($highlight) and $highlight->icon) ? $highlight->icon :  asset('assets/images/no-image.png') }}" style="width: 64px; height: 64px;" alt="">
                                            </div>
                                            <div class="media-body">
                                                <input type="file" name="icon" class="js-file">
                                                <span class="help-block no-margin-bottom">Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 2Mb</span>
                                            </div>
                                        </div>
                                        @if ($errors->has('icon'))
                                            <div class="help-block">{{ $errors->first('icon') }}</div>
                                        @endif
                                    </div>

                                    <div class="text-right">
                                        {{--<button type="reset" class="btn btn-info">Làm mới</button>--}}
                                        <button type="submit" class="btn btn-primary">{{ isset($notification) ? 'Cập nhật' : 'Thêm' }}</button>
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


    <div class="modal fade bs-modal-lg" id="icon-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="delete-modal-label">Chọn icon có sẵn</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <form method="POST" id="form-delete" action="{{route('Staff::Management::businessDistributor@deleteDistributor', [Request::get('business_id')])}}">
                        {{ csrf_field() }}


                        {{--<input type="hidden" class="" name="business_id" value="{{Request::get('business_id')}}">--}}
                        {{--<input type="hidden" name="product_id" class="product_id">--}}
                        {{--<input type="hidden" name="_method" value="POST">--}}
                        {{--<button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>--}}
                        {{--<button type="submit" class="btn btn-danger" id="submit-delete">Xác nhận</button>--}}
                    </form>
                </div>
            </div>
        </div>
    </div>





@endsection

@push('js_files_foot')
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/anytime.min.js') }}"></script>
@endpush

@push('scripts_foot')
<script>
    $(document).ready(function () {
        $(".js-file").uniform({
            fileButtonClass: "action btn btn-default"
        });

        $('.js-select').select2();
//        if($('#type_send').val() == 2){
//            $('#div_time_send').show();
//        }
//        $('#type_send').change(function(){
//            var val = $(this).val();
//            if(val == 2){
//                $('#div_time_send').show();
//            }else{
//                $('#div_time_send').hide();
//            }
//        });
//        $("#time_send").AnyTime_picker({
//            format: "%H:%i %d-%m-%Y"
//        });
        $(".styled").uniform({
            radioClass: 'choice'
        });
    });
</script>
@endpush
