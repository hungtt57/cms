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
                    <a href="{{ route('missions.list') }}" class="btn btn-link">
                        <i class="icon-arrow-left8"></i>
                    </a>
                    Tạo nhiệm vụ mới
                </h2>
            </div>
        </div>
    </div>
    <div class="page-container">
        <div class="page-content">
            <div class="content-wrapper">
                <div class="panel" id="formUpdateMission" >
                    <div class="panel-body">
                        <form class="form-horizontal" action="{{ route('missions.update', $mission->id) }}" method="post" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            {!! method_field('PUT') !!}
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <label for="missionName" class="col-sm-4 control-label">Name</label>
                                <div class="col-sm-8">
                                    <input type="text" name="name" value="{{ old('name') ? old('name') : $mission->name }}" class="form-control" id="missionName" placeholder="login_facebook">
                                    @if ($errors->has('name'))
                                        <span class="help-block">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('hook') ? 'has-error' : '' }}">
                                <label for="missionHook" class="col-sm-4 control-label">Hook</label>
                                <div class="col-sm-8">
                                    <input type="text" name="hook" value="{{ old('hook') ? old('hook') : $mission->hook }}" class="form-control" id="missionHook" placeholder="login_facebook">
                                    @if ($errors->has('hook'))
                                        <span class="help-block">{{ $errors->first('hook') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="missionDescription" class="col-sm-4 control-label">Mô tả nhiệm vụ</label>
                                <div class="col-sm-8">
                                    <textarea name="description" id="missionDescription" class="form-control" rows="6" placeholder="Login facebook">{{ old('description') ? old('description') : $mission->description }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-4 control-label">Thông số</label>
                                <div class="col-sm-4">
                                    <input type="number" name="points" min="0" value="{{ old('points') }}" id="missionPoints" class="form-control" placeholder="Points">
                                </div>
                                <div class="col-sm-4">
                                    <input type="number" name="specialPoints" min="0" value="{{ old('specialPoints') }}" id="missionSpecialPoints" class="form-control" placeholder="specialPoints">
                                </div>
                                <div class="col-sm-4">
                                    <input type="number" name="maxPerDay" min="0" value="{{ old('maxPerDay') ? old('maxPerDay') : $mission->maxPerDay }}" id="missionMaxPerDay" class="form-control" placeholder="Max per day">
                                </div>
                                <div class="col-sm-4 col-sm-offset-4">
                                    <input type="number" name="repeat" min="0" value="{{ old('repeat') ? old('repeat') : $mission->repeat }}" id="missionRepeat" class="form-control" placeholder="Repeat">
                                </div>
                                <div class="col-sm-4">
                                    <input type="number" name="maxComplete" min="0" value="{{ old('maxComplete') ? old('maxComplete') : $mission->maxComplete }}" id="missionMaxComplete" class="form-control" placeholder="Max complete">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-4 control-label">
                                    Điều kiện
                                    ( <a href="javascript:void(0)" id="addCondition">add</a> )
                                </label>
                                <div class="col-sm-8">
                                    <div id="listCondition">
                                        @if (isset($mission->conditions))
                                            <?php $conditions = $mission->conditions ?>
                                            @foreach ($conditions as $condition)
                                                <div class="condition">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <input type="text" name="param[]" value="{{ $condition['param'] }}" class="form-control" placeholder="param">
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <select name="operator[]" class="form-control">
                                                                <option value="eq" {{ $condition['operator'] == 'eq' ? 'selected' : '' }}>Equal</option>
                                                                <option value="gt" {{ $condition['operator'] == 'gt' ? 'selected' : '' }}>Greater</option>
                                                                <option value="gte" {{ $condition['operator'] == 'gte' ? 'selected' : '' }}>Greater or equal</option>
                                                                <option value="lt" {{ $condition['operator'] == 'lt' ? 'selected' : '' }}>Less</option>
                                                                <option value="lte" {{ $condition['operator'] == 'lte' ? 'selected' : '' }}>Less or equal</option>
                                                                <option value="ne" {{ $condition['operator'] == 'ne' ? 'selected' : '' }}>Not equal</option>
                                                                <option value="in" {{ $condition['operator'] == 'in' ? 'selected' : '' }}>In array</option>
                                                                <option value="nin" {{ $condition['operator'] == 'nin' ? 'selected' : '' }}>Not in array</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="value[]" value="{{ $condition['value'] }}" class="form-control" placeholder="value">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <button type="submit" class="btn btn-primary btn-sm">Cập nhật nhiệm vụ</button>
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
            var listCondition = document.getElementById('listCondition');
            var condition = listCondition.firstElementChild;
            var addCondition = document.getElementById('addCondition');

            addCondition.onclick = function (a) {
                listCondition.appendChild(condition.cloneNode(true));
            }
        });
    </script>
@endpush
