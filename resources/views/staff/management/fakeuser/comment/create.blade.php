@extends('_layouts/staff')

@section('content')
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>
                    <a href="{{ route('Staff::Management::virtualUser@post.all.list') }}" class="btn btn-link">
                        <i class="icon-arrow-left8"></i> Danh sách post
                    </a>
                </h2>
            </div>
        </div>
    </div>
    <div class="page-container">
        <div class="page-content">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <form id="formAddComment" action="{{ route('Staff::Management::virtualUser@comment.create') }}" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="post" value="{{ request('post') }}">
                                    @foreach($users as $k => $user)
                                        <div class="media">
                                            <div class="media-left">
                                                <img class="media-object" data-toggle="modal" data-target="#selectUser" src="{{ $user['avatar'] ? get_image_url($user['avatar']) : asset('assets/images/image.png') }}" alt="User">
                                            </div>
                                            <div class="media-body">
                                                <h4 class="media-heading">{{ $user['name'] }}</h4>
                                                <input type="hidden" name="comments[{{ $k }}][icheck_id]" value="{{ $user['icheck_id'] }}">
                                                <input type="text" name="comments[{{ $k }}][message]" class="form-control" placeholder="Message">
                                            </div>
                                        </div>
                                    @endforeach
                                </form>
                                <div class="text-right" style="margin-top: 16px;">
                                    <button class="btn btn-primary btn-xs" form="formAddComment" type="submit">Tạo</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="selectUser" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Choose user comment</h4>
                </div>
                <div class="modal-body">
                    <ul class="list-unstyled">
                        @foreach($users as $user)
                            <li>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="user" form="formAddComment" value="{{ $user['icheck_id'] }}">
                                        <img width="32" height="32" src="{{ $user['avatar'] ? get_image_url($user['avatar']) : asset('assets/images/image.png') }}" alt="{{ $user['name'] }}" class="img-rounded">
                                        {{ $user['name'] }}
                                    </label>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="selectUserLike" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Choose user comment</h4>
                </div>
                <div class="modal-body">
                    <form id="formLikeUser" method="post" action="{{ route('Staff::Management::virtualUser@comment.like', ['comment' => 'cid']) }}">
                        {{ csrf_field() }}
                        <ul class="list-unstyled">
                            @foreach($users as $user)
                                <li>
                                    <div class="radio">
                                        <label>
                                            <input type="checkbox" name="users[]" value="{{ $user['icheck_id'] }}">
                                            <img width="32" height="32" src="{{ $user['avatar'] ? get_image_url($user['avatar']) : asset('assets/images/image.png') }}" alt="{{ $user['name'] }}" class="img-rounded">
                                            {{ $user['name'] }}
                                        </label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="formLikeUser" class="btn btn-primary">Like</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts_foot')
<script>
//    {
//        "use strict";
//
//        var likeButtons = document.querySelectorAll('a.like');
//        var formUserLike = document.getElementById('formLikeUser');
//
//        for (let likeButton of likeButtons) {
//            likeButton.addEventListener('click', function (event) {
//                event.preventDefault();
//                formUserLike.action = formUserLike.action.replace('cid', this.dataset.id);
//                $('#selectUserLike').modal();
//            });
//        }
//    }
</script>
@endpush
