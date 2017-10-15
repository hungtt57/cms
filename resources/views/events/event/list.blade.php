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
                    Danh sách sự kiện
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
                                <a href="{{ route('events.create') }}"><i class="icon-plus3" style="font-size: 16px;"></i></a>
                            </div>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Ảnh</th>
                                <th>Tên</th>
                                <th>Mô tả</th>
                                <th>Băt đầu</th>
                                <th>Kết thúc</th>
                                <th class="text-center">Đổi quà</th>
                                <th class="text-center">Hoạt động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <form id="formDeleteEvent" method="post">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                            </form>
                            @foreach($events as $k => $event)
                                <tr>
                                    <td>
                                        <img src="{{ get_image_url($event->image, 'thumb_small') }}" width="48" height="48" alt="{{ $event->name }}" class="img-rounded">
                                    </td>
                                    <td>{{ $event->name }}</td>
                                    <td>{{ $event->description }}</td>
                                    <td>{{ $event->startTime->toDateTimeString() }}</td>
                                    <td>{{ $event->endTime->toDateTimeString() }}</td>
                                    <td class="text-center">{{ $event->giftExchange ? 'có' : 'không' }}</td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <a id="menuLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="icon-menu7" style="font-size: 24px;"></i>
                                            </a>
                                            <ul class="dropdown-menu pull-right" aria-labelledby="menuLabel">
                                                <li>
                                                    <a href="{{ route('events.gifts.list', ['event' => $event->id]) }}">Quà tặng</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('events.missions.list', $event->id) }}">Nhiệm vụ</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('events.userreceivinggift.list', $event->id) }}">Danh sách nhận quà</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('events.edit', $event->id) }}">Sửa</a>
                                                </li>
                                                <li>
                                                    <a href=""><input style="background: transparent;border: none;padding: 0;" type="submit" value="Xóa" form="formDeleteEvent" formaction="/events/{{ $event->id }}"></a>
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
                                {{ $events->firstItem() ? $events->firstItem() : 0 }}-{{ $events->lastItem() ? $events->lastItem() : 0 }} of {{ $events->total() ? $events->total() : 0 }}
                            </span>
                            <a href="{{ $events->previousPageUrl() }}"><i class="icon icon-arrow-left3" style="margin-left: 32px;"></i></a>
                            <a href="{{ $events->nextPageUrl() }}"><i class="icon icon-arrow-right3" style="margin-left: 24px;"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js_files_foot')
@endpush