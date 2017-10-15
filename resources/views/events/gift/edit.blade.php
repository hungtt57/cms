@extends('_layouts/staff')

@push('styles_head')
    <style>
        #formUpdateGift {
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
                    <a href="{{ route('events.gifts.list', $gift->event->id) }}" class="btn btn-link">
                        <i class="icon-arrow-left8"></i>
                    </a>
                    Cập nhật quà tặng cho sự kiện
                </h2>
            </div>
        </div>
    </div>
    <div class="page-container">
        <div class="page-content">
            <div class="content-wrapper">
                <div class="panel" id="formUpdateGift" >
                    <div class="panel-body">
                        <form class="form-horizontal" action="{{ route('gifts.update', ['event' => $gift->id]) }}" method="post" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            {!! method_field('PUT') !!}
                            <input type="hidden" name="event" value="{{ $gift->event->id }}">
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <label for="eventName" class="col-sm-4 control-label">Tên quà tặng</label>
                                <div class="col-sm-8">
                                    <input type="text" name="name" value="{{ old('name') ? old('name') : $gift->name }}" class="form-control" id="eventName" placeholder="20 % cổ phần facebook">
                                    @if ($errors->has('name'))
                                        <span class="help-block">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                <label for="eventDescription" class="col-sm-4 control-label">Mô tả quà tặng</label>
                                <div class="col-sm-8">
                                    <textarea name="description" id="eventDescription" class="form-control" rows="6" placeholder="Và được giao thông vs 3 rách ô ba ma">{{ old('description') ? old('description') : $gift->description }}</textarea>
                                    @if ($errors->has('description'))
                                        <span class="help-block">{{ $errors->first('description') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-8 col-sm-offset-4">
                                    <select name="type" class="form-control">
                                        @foreach($types as $key => $type)
                                            <option value="{{ $key }}" {{ $key == $gift->type ? 'selected' : '' }}>{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-8">
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
