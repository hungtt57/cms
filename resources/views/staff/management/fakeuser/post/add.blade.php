@extends('_layouts/staff')

@push('styles_head')
<style>
    #addPostOfVirtualUser {
        padding: 16px;
    }
    .btn {
        padding: 4px 16px;
    }
</style>
@endpush

@section('content')
    <section id="addPostOfVirtualUser">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            {{ session('message') }}
                            <form method="POST" enctype="multipart/form-data" action="{{ route('Staff::Management::virtualUser@post.store', ['user' => $id]) }}">
                                {{ csrf_field() }}
                                <div class="form-group {{ $errors->has('title') ? 'has-error has-feedback' : '' }}">
                                    <label for="name" class="control-label text-semibold">Title</label>
                                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" />
                                    @if ($errors->has('title'))
                                        <div class="help-block">{{ $errors->first('title') }}</div>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('description') ? 'has-error has-feedback' : '' }}">
                                    <label for="name" class="control-label text-semibold">Description</label>
                                    <input type="text" id="description" name="description" class="form-control" value="{{ old('description') }}" />
                                    @if ($errors->has('description'))
                                        <div class="help-block">{{ $errors->first('description') }}</div>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('content') ? 'has-error has-feedback' : '' }}">
                                    <label for="contact-info" class="control-label text-semibold">Content</label>
                                    <textarea id="editor1" name="content" rows="5" cols="5" class="form-control">{{ old('content') }}</textarea>
                                    @if ($errors->has('content'))
                                        <div class="help-block">{{ $errors->first('content') }}</div>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
                                    <div class="display-block">
                                        <label class="control-label text-semibold">Image</label>
                                    </div>
                                    <div class="media no-margin-top">
                                        <div class="media-left">
                                            <img src="{{ asset('assets/images/image.png') }}" style="width: 64px; height: 64px;" alt="">
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
                                    <a class="btn btn-default" href="{{ route('Staff::Management::virtualUser@post.list', ['user' => $id]) }}">Hủy</a>
                                    <button type="submit" class="btn btn-primary">Thêm mới</button>
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
