@extends('_layouts/staff')

@push('styles_head')
    <style>
        .table-paging .icon {
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2 class="text-center">
                    <a href="{{ route('events.list') }}" class="btn btn-link">
                        <i class="icon-arrow-left8"></i>
                    </a>
                    Danh sách quà tặng
                </h2>
            </div>
        </div>
    </div>
    <div class="page-container">
        <div class="page-content">
            <div class="content-wrapper">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="message text-primary">{{ session('message') ? session('message') : '' }}</div>
                            </div>
                            <div class="col-sm-6 text-right">
                                <a href="{{ route('events.gifts.create', $event->id) }}"><i class="icon-plus3" style="font-size: 16px;"></i></a>
                            </div>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <td>Ảnh</td>
                                <th>Tên</th>
                                <th>Mô tả</th>
                                <th>Loại quà</th>
                                <th class="text-right">Hoạt động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <form id="formDeleteGift" method="post">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                            </form>
                            @foreach($gifts as $gift)
                                <tr>
                                    <td>
                                        @if ($gift->image)
                                            <img src="{{ get_image_url($gift->image, 'thumb_small') }}" width="48" height="48" alt="{{ $gift->name }}" class="img-rounded">
                                        @endif
                                    </td>
                                    <td>{{ $gift->name }}</td>
                                    <td>{{ $gift->description }}</td>
                                    <td>{{ $types[$gift->type] }}</td>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a id="menuLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="icon-menu7" style="font-size: 24px;"></i>
                                            </a>
                                            <ul class="dropdown-menu pull-right" aria-labelledby="menuLabel">
                                                <li>
                                                    <a href="{{ route('gifts.edit', $gift->id) }}">Sửa</a>
                                                </li>
                                                <li>
                                                    <a href=""><input style="background: transparent;border: none;padding: 0;width:100%;text-align: left" type="submit" value="Xóa" form="formDeleteGift" formaction="{{ route('gifts.delete', $gift->id) }}"></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="panel-body">
                        <div class="table-paging text-right">
                            <span class="page-infor">
                                {{ $gifts->firstItem() ? $gifts->firstItem() : 0 }}-{{ $gifts->lastItem() ? $gifts->lastItem() : 0 }} of {{ $gifts->total() ? $gifts->total() : 0 }}
                            </span>
                            <a href="{{ $gifts->previousPageUrl() }}"><i class="icon icon-arrow-left3" style="margin-left: 32px;"></i></a>
                            <a href="{{ $gifts->nextPageUrl() }}"><i class="icon icon-arrow-right3" style="margin-left: 24px;"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js_files_foot')
@endpush