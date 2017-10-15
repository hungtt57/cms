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
                    <a href="" class="btn btn-link">
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
                                <a href=""><i class="icon-plus3" style="font-size: 16px;"></i></a>
                            </div>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sự kiện</th>
                                <th>Tên</th>
                                <th>Mô tả</th>
                                <th>Loại quà</th>
                            </tr>
                        </thead>
                        <tbody>
                            <form id="formDeleteGift" method="post">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                            </form>
                            @foreach($gifts as $gift)
                                <tr>
                                    <td>{{ $gift->event->name }}</td>
                                    <td>{{ $gift->name }}</td>
                                    <td>{{ $gift->description }}</td>
                                    <td>{{ $types[$gift->type] }}</td>
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