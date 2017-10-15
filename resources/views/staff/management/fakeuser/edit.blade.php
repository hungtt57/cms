@extends('_layouts/staff')

@push('styles_head')
<style>
    #addVirtualUser {
        padding: 16px;
    }
    .btn {
        padding: 4px 16px;
    }
</style>
@endpush

@section('content')
    <section id="addVirtualUser">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form method="POST" enctype="multipart/form-data" action="{{ route('Staff::Management::fake@update', $user['id']) }}">
                                {{ csrf_field() }}
                                {{ method_field('put') }}
                                <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
                                    <label for="name" class="control-label text-semibold">Tên</label>
                                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') ?: $user['name'] }}" />
                                    @if ($errors->has('name'))
                                        <div class="form-control-feedback">
                                            <i class="icon-notification2"></i>
                                        </div>
                                        <div class="help-block">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>
                                <!----- Upload Image Here ---->
                                <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
                                    <div class="display-block">
                                        <label class="control-label text-semibold">Logo</label>
                                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Logo của Doanh nghiệp. Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 2Mb"></i>
                                    </div>
                                    <div class="media no-margin-top">
                                        <div class="media-left">
                                            <img src="{{ ($user['avatar']) ? get_image_url($user['avatar'], 'thumb_small') : asset('assets/images/image.png') }}" style="width: 64px; height: 64px;" alt="">
                                        </div>
                                        <div class="media-body">
                                            <input type="file" name="image" class="js-file">
                                            <span class="help-block no-margin-bottom">Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 2Mb</span>
                                        </div>
                                    </div>
                                    @if ($errors->has('image'))
                                        <div class="help-block">{{ $errors->first('image') }}</div>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <a href="{{ route('Staff::Management::fake@index') }}" class="btn btn-default">Hủy</a>
                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js_files_foot')
@endpush

@push('scripts_foot')
@endpush
