@extends('_layouts/staff')

@push('styles_head')
    <style>
        #listVirtualUser {
            padding: 16px;
        }
        .btn-xs {
            padding: 2px 4px;
        }
    </style>
@endpush

@section('content')
    <section id="listVirtualUser">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading text-right text-uppercase">
                            <div class="pull-left">{{ session('message') }}</div>
                            <a href="{{ route('Staff::Management::fake@add') }}" style="margin-left: 8px;">Thêm</a>
                        </div>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Icheck ID</th>
                                <th>Account ID</th>
                                <th>Avatar</th>
                                <th>Name</th>
                                <th class="text-right">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user['id'] }}</td>
                                    <td>{{ $user['icheck_id'] }}</td>
                                    <td>{{ $user['account_id'] }}</td>
                                    <td><div style="width: 48px; height: 48px; {{ ! empty($user['avatar']) ? "background: url('http://ucontent.icheck.vn/{$user['avatar']}_thumb_small.jpg') center/contain no-repeat" : '' }}"></div></td>
                                    <td>{{ $user['name'] }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('Staff::Management::virtualUser@post.all.list', ['lid' => $user['id']]) }}" type="button" class="btn btn-primary btn-xs">likes</a>
                                        <a href="{{ route('Staff::Management::virtualUser@post.list', $user['id']) }}" type="button" class="btn btn-primary btn-xs">posts</a>
                                        <a href="{{ route('Staff::Management::fake@edit', $user['id']) }}" type="button" class="btn btn-primary btn-xs">edit</a>
                                        <a href="{{ route('Staff::Management::fake@block', $user['id']) }}?block={{ $user['status'] ? 'true' : 'false' }}" type="button" class="btn btn-warning btn-xs">{{ $user['status'] ? 'block' : 'unblock' }}</a>
                                        <a href="{{ route('Staff::Management::fake@delete', $user['id']) }}" type="button" class="btn btn-danger btn-xs">delete</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="panel-footer">
                            <ul class="pager">
                                <?php
                                    $cp = request()->input('page', 1);
                                    $pp = $cp;
                                    $np = $cp + 1;

                                    if ($cp > 1) {
                                        $pp = $cp - 1;
                                    }
                                ?>
                                <li><a href="{{ request()->fullUrlWithQuery(['page' => $pp]) }}">Previous</a></li>
                                <li><a href="{{ request()->fullUrlWithQuery(['page' => $np]) }}">Next</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_foot')

@endpush

@push('scripts_ck')
    <script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
@endpush