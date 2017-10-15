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
                    Danh sách nhiệm vụ
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
                                <a href="{{ route('events.missions.add', $event->id) }}"><i class="icon-plus3" style="font-size: 16px;"></i></a>
                            </div>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Hook</th>
                                <th>Description</th>
                                <th class="text-right">Point</th>
                                <th class="text-right">Max per day</th>
                                <th class="text-right">Repeat</th>
                                <th class="text-right">Max complete</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <form id="formUpdateMission" method="post">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                            </form>
                            @foreach($missions as $mission)
                                <tr>
                                    <td>{{ $mission->hook }}</td>
                                    <td>{{ $mission->description }}</td>
                                    <td class="text-right">{{ $mission->point }}</td>
                                    <td class="text-right">{{ $mission->maxPerDay }}</td>
                                    <td class="text-right">{{ $mission->repeat }}</td>
                                    <td class="text-right">{{ $mission->maxComplete }}</td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <a id="menuLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="icon-menu7" style="font-size: 24px;"></i>
                                            </a>
                                            <ul class="dropdown-menu pull-right" aria-labelledby="menuLabel">
                                                <li>
                                                    <a href=""><input style="background: transparent;border: none;padding: 0;" type="submit" value="Remove" form="formUpdateMission" formaction="{{ route('events.missions.remove', ['event' => $event->id, 'mission' => $mission->id]) }}"></a>
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
                                {{ $missions->firstItem() ? $missions->firstItem() : 0 }}-{{ $missions->lastItem() ? $missions->lastItem() : 0 }} of {{ $missions->total() ? $missions->total() : 0 }}
                            </span>
                            <a href="{{ $missions->previousPageUrl() }}"><i class="icon icon-arrow-left3" style="margin-left: 32px;"></i></a>
                            <a href="{{ $missions->nextPageUrl() }}"><i class="icon icon-arrow-right3" style="margin-left: 24px;"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js_files_foot')
@endpush