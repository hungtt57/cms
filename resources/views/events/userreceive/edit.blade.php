@extends('_layouts/staff')

@push('styles_head')
    <style>
        #formUpdateMission {
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
                    <a href="" class="btn btn-link">
                        <i class="icon-arrow-left8"></i>
                    </a>
                    Cập nhật người nhận quà
                </h2>
            </div>
        </div>
    </div>
    <div class="page-container">
        <div class="page-content">
            <div class="content-wrapper">
                <div class="panel" id="formUpdateMission" >
                    <div class="panel-body">
                        <form class="form-horizontal" action="{{ route('events.userreceivinggift.update', $userReceive->id) }}" method="post" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            {!! method_field('PUT') !!}
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <label for="userReceiveName" class="col-sm-4 control-label">Name</label>
                                <div class="col-sm-8">
                                    <input type="text" name="name" value="{{ old('name') ? old('name') : $userReceive->name }}" class="form-control" id="userReceiveName" placeholder="Vu Duc Dung">
                                    @if ($errors->has('name'))
                                        <span class="help-block">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                                <label for="userReceivePhone" class="col-sm-4 control-label">Phone</label>
                                <div class="col-sm-8">
                                    <input type="text" name="phone" value="{{ old('phone') ? old('phone') : $userReceive->phone }}" class="form-control" id="userReceivePhone" placeholder="0986676766">
                                    @if ($errors->has('phone'))
                                        <span class="help-block">{{ $errors->first('phone') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                <label for="userReceiveEmail" class="col-sm-4 control-label">Email</label>
                                <div class="col-sm-8">
                                    <input type="email" name="email" value="{{ old('email') ? old('email') : $userReceive->email }}" class="form-control" id="userReceiveEmail" placeholder="abc@icheck.vn">
                                    @if ($errors->has('email'))
                                        <span class="help-block">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                                <label for="userReceiveAddress" class="col-sm-4 control-label">Address</label>
                                <div class="col-sm-8">
                                    <input type="text" name="address" value="{{ old('address') ? old('address') : $userReceive->address }}" class="form-control" id="userReceiveAddress" placeholder="So 69, duong 96, Thanh xuan, Ha noi">
                                    @if ($errors->has('address'))
                                        <span class="help-block">{{ $errors->first('address') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                                <label for="userReceiveStatus" class="col-sm-4 control-label">Address</label>
                                <div class="col-sm-8">
                                    <select name="status" class="form-control" id="userReceiveStatus">
                                        <option value="1" {{ $userReceive->status ? 'selected' : '' }}>Received</option>
                                        <option value="0" {{ $userReceive->status ? '' : 'selected' }}>Not received</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
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
    <script>
        $(document).ready(function () {
        });
    </script>
@endpush