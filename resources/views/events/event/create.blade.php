@extends('_layouts/staff')

@push('styles_head')
    <style>
        #formUpdateEvent {
            width: 640px;
            margin: 0 auto;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2 class="text-center">
                    <a href="/events" class="btn btn-link">
                        <i class="icon-arrow-left8"></i>
                    </a>
                    Tạo sự kiện mới
                </h2>
            </div>
        </div>
    </div>
    <div class="page-container">
        <div class="page-content">
            <div class="content-wrapper">
                <div class="panel" id="formUpdateEvent" >
                    <div class="panel-body">
                        <form class="form-horizontal" action="/events" method="post" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <label for="eventName" class="col-sm-4 control-label">Tên sự kiện</label>
                                <div class="col-sm-8">
                                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="eventName" placeholder="Sư kiện checker việt nam 2016">
                                    @if ($errors->has('name'))
                                        <span class="help-block">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                <label for="eventDescription" class="col-sm-4 control-label">Mô tả sự kiện</label>
                                <div class="col-sm-8">
                                    <textarea name="description" id="eventDescription" class="form-control" rows="6" placeholder="Với sự tham gia của các checker hàng đầu việt nam">{{ old('description') }}</textarea>
                                    @if ($errors->has('description'))
                                        <span class="help-block">{{ $errors->first('description') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('startTime') ? 'has-error' : '' }}">
                                <label for="eventStartTime" class="col-sm-4 control-label">Thời gian</label>
                                <div class="col-sm-4">
                                    <input type="datetime-local" name="startTime" value="{{ old('startTime') }}" id="eventStartTime" class="form-control" >
                                    @if ($errors->has('startTime'))
                                        <span class="help-block">{{ $errors->first('startTime') }}</span>
                                    @endif
                                </div>
                                <div class="col-sm-4 {{ $errors->has('endTime') ? 'has-error' : '' }}">
                                    <input type="datetime-local" name="endTime" value="{{ old('endTime') }}" id="eventEndTime" class="form-control">
                                    @if ($errors->has('endTime'))
                                        <span class="help-block">{{ $errors->first('endTime') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
                                <label for="eventImage" class="col-sm-4 control-label">Ảnh sự kiện</label>
                                <div class="col-sm-8">
                                    <input type="file" name="image" id="eventImage" class="form-control">
                                    @if ($errors->has('image'))
                                        <span class="help-block">{{ $errors->first('image') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <label for="" class="control-label">Đổi quà</label>
                                </div>
                                <div class="col-sm-8">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="giftExchange" id="eventGiftExchangeTrue" value="1" {{ old('giftExchange') == 1 ? 'checked' : '' }}> Có
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="giftExchange" id="eventGiftExchangeFalse" value="0" {{ old('giftExchange') == 1 ? '' : 'checked' }}> Không
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary btn-sm">Cập nhật</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js_files_foot')
    <script src="//cdn.ckeditor.com/4.5.10/full/ckeditor.js"></script>
    <script>
        function setTimeDefault() {
            var date = new Date();
            var year = date.getFullYear();
            var month = date.getMonth();

            if (month < 10) {
                month = '0' + month;
            }

            var day = date.getDate();

            if (day < 10) {
                day = '0' + day;
            }

            var hour = date.getHours();

            if (hour < 10) {
                hour = '0' + hour;
            }

            var minute = date.getMinutes();

            if (minute < 10) {
                minute = '0' + minute;
            }

            var date = year + '-' + month + '-' + day + 'T' + hour + ':' + minute;

            document.getElementById('eventStartTime').value = date;
            document.getElementById('eventEndTime').value = date;
        }

        $(document).ready(function () {
            setTimeDefault();
        });
    </script>
@endpush
