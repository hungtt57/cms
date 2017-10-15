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
                    Thêm nhiệm vụ cho sự kiện
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
                                <input type="submit" form="formAddMission" value="add" style="background: none;border: none;color: #1E88E5;">
                            </div>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <td>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" id="checkAllMission">
                                        </label>
                                    </div>
                                </td>
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
                            <form action="{{ route('events.missions.store', $event->id) }}" id="formAddMission" method="post">
                                {{ csrf_field() }}
                            </form>
                            @foreach($missions as $mission)
                                <tr>
                                    <td>
                                        <div class="checkbox">
                                            <label>
                                                <input class="mission" type="checkbox" name="mission[]" form="formAddMission" value="{{ $mission->id }}">
                                            </label>
                                        </div>
                                    </td>
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
                                                    <a href="{{ route('missions.edit', $mission->id) }}">Sửa</a>
                                                </li>
                                                <li>
                                                    <a href=""><input style="background: transparent;border: none;padding: 0;" type="submit" value="Xóa" form="formDeleteMission" formaction="{{ route('missions.delete', $mission->id) }}"></a>
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
    <script>
        $(document).ready(function () {
            var checkAllMission = document.getElementById('checkAllMission');

            checkAllMission.onclick = function () {
                var missions = document.getElementsByClassName('mission');
                var i;
                var c = missions.length;

                for (i = 0; i < c; i++) {
                    missions[i].checked = checkAllMission.checked;
                }
            }
        });
    </script>
@endpush