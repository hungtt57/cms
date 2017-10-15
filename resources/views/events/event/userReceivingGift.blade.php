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
                    Danh sách nhận quà
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
                            </div>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Gift</th>
                                <th>User duoc nhan qua</th>
                                <th>Thong tin nguoi nhan qua</th>
                                <th>Status</th>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            <form id="formUpdateUserReceiveStatus" method="post">
                                {{ csrf_field() }}
                                {{ method_field('PUT') }}
                            </form>
                            @foreach($gifts as $gift)
                                <tr id="g-{{ $gift->id }}">
                                    <td>{{ $gift->name }}</td>
                                    @if($gift->userReceive)
                                      <td>
                                        @if (@$gift->receiver->facebook_id)
                                        <a href="https://fb.com/{{ $gift->receiver->facebook_id }}" target="_blank">{{ $gift->receiver->facebook_name }}</a>
                                        @endif
                                      </td>
                                        <td>

                                        Name: {{ $gift->userReceive->name }}<br />
                                        Phone: {{ $gift->userReceive->phone }}<br />
                                        Email: {{ $gift->userReceive->email }}<br />
                                        Address: {{ $gift->userReceive->address }}
                                        </td>
                                        <td>{{ $gift->userReceive->status ? 'Đã nhận' : 'Chưa nhận' }}</td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <a id="menuLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="icon-menu7" style="font-size: 24px;"></i>
                                                </a>
                                                <ul class="dropdown-menu pull-right" aria-labelledby="menuLabel">
                                                    <li>
                                                        <a href="{{ route('events.userreceivinggift.edit', $gift->userReceive->id) }}">Người nhận quà</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    @else
                                        <td>
                                        @if (@$gift->receiver->facebook_id)
                                        <a href="https://fb.com/{{ $gift->receiver->facebook_id }}" target="_blank">{{ $gift->receiver->facebook_name }}</a>
                                        @endif
                                        </td>
                                        <td></td>
                                        <td>Chưa nhận</td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <a id="menuLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="icon-menu7" style="font-size: 24px;"></i>
                                                </a>
                                                <ul class="dropdown-menu pull-right" aria-labelledby="menuLabel">
                                                </ul>
                                            </div>
                                        </td>
                                    @endif
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
