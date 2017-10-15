@extends('_layouts/default')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>
                    <a href="{{ route('Business::question@index') }}" class="btn btn-link">
                        <i class="icon-arrow-left8"></i>
                    </a>
                    {{ isset($question) ? 'Sửa Câu hỏi ' . $question->title : 'Tạo câu hỏi cho icheck' }}
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
                    <div class="col-md-offset-4 col-md-4">
                        @if (session('success'))
                            <div class="alert bg-success alert-styled-left">
                                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                {{ session('success') }}
                            </div>
                        @endif
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <form method="POST" enctype="multipart/form-data" action="{{ isset($question) ? route('Business::question@update', [$question->id]) : route('Business::question@store') }}">
                                    {{ csrf_field() }}
                                    @if (isset($question))
                                        <input type="hidden" name="_method" value="PUT">

                                    @endif

                                    <div class="form-group">
                                        <label for="address" class="control-label text-semibold">Tiêu đề</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Tiêu đề"></i>
                                        <input type="text" id="title" name="title" maxlength="255" class="form-control"
                                               value="@if(isset($question)){{$question->title}}@else {{old('title')}} @endif"/>
                                    </div>

                                    <div class="form-group {{ $errors->has('room') ? 'has-error has-feedback' : '' }}">
                                        <label for="contact-info" class="control-label text-semibold">Phòng ban</label>
                                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Chọn phòng ban "></i>
                                        <select name="room" class="js-select">
                                            @foreach (App\Models\Enterprise\DNQuestion::$rooms as $key => $value)
                                                <option value="{{$key}}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('room'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('room') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('service') ? 'has-error has-feedback' : '' }}">
                                        <label for="contact-info" class="control-label text-semibold">Dịch vụ</label>
                                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Chọn dịch vụ "></i>
                                        <select name="service" class="js-select">
                                            @foreach (App\Models\Enterprise\DNQuestion::$services as $key => $value)
                                                <option value="{{$key}}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('service'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('service') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('content') ? 'has-error has-feedback' : '' }}">
                                        <label for="contact-info" class="control-label text-semibold">Nội dung</label>
                                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Nhập nội dung "></i>
                                        <textarea rows="5" cols="5" class="form-control" name="content" placeholder="Nhập nội dung"></textarea>
                                        @if ($errors->has('content'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('content') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('attachments') ? 'has-error has-feedback' : '' }}">
                                        <label for="contact-info" class="control-label text-semibold">File đính kèm</label>
                                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Chọn file đính kèm"></i>
                                        <input type="file" name="attachments" class="js-file">
                                        @if ($errors->has('attachments'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('attachments') }}</div>
                                        @endif
                                    </div>



                                    <div class="text-right">
                                        <button type="reset" class="btn btn-info">Làm mới</button>
                                        <button type="submit" class="btn btn-primary">{{ isset($question) ? 'Cập nhật' : 'Gửi' }}</button>
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
@endpush

@push('scripts_foot')
<script>
    $(document).ready(function () {
        $('.js-select').select2();
    });
</script>
@endpush
