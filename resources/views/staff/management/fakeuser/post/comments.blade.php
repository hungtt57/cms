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
                                @foreach($comments as $comment)
                                    <div class="media">
                                        <div class="media-left">
                                            <img class="media-object" src="{{ $comment['owner']['avatar'] ? get_image_url($comment['owner']['avatar']) : (isset($comment['owner']['social_type']) && $comment['owner']['social_type'] == 'facebook' ? 'http://graph.facebook.com/'.$comment['owner']['social_id'].'/picture' : asset('assets/images/image.png')) }}" alt="{{ isset($comment['owner']['social_name']) ? $comment['owner']['social_name'] : '' }}">
                                        </div>
                                        <div class="media-body">
                                            <h4 class="media-heading">{{ isset($comment['owner']['social_name']) ? $comment['owner']['social_name'] : '' }}</h4>
                                            <p>{{ $comment['content'] }}</p>
                                            @if (! empty($comment['attachments']))
                                                <div style="margin-bottom: 8px;">
                                                    @foreach($comment['attachments'] as $attachment)
                                                        @if ($attachment['type'] == 'image')
                                                            <img height="120" src="{{ get_image_url($attachment['link']) }}" alt="Image">
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                            <ul class="list-inline">
                                                <li>{{ \Carbon\Carbon::createFromTimestamp($comment['createdAt']/1000)->diffForHumans() }}</li>
                                                <li><a href="#" class="like" data-id="{{ $comment['id'] }}">like</a></li>
                                                <li>{{ $comment['like_count'] }} likes</li>
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                                <form id="formAddComment" action="#" method="post" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="media">
                                        <div class="media-left">
                                            <img class="media-object" data-toggle="modal" data-target="#selectUser" src="{{ asset('assets/images/image.png') }}" alt="User">
                                        </div>
                                        <div class="media-body" style="padding-right: 86px;">
                                            <input type="text" name="message" class="form-control" placeholder="Message">
                                            <input type="file" name="file" class="form-control" style="position: absolute;width: 86px;right: 0;top: 0;">
                                        </div>
                                    </div>
                                </form>
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
    {
        "use strict";

        var likeButtons = document.querySelectorAll('a.like');
        var formUserLike = document.getElementById('formLikeUser');

        for (let likeButton of likeButtons) {
            likeButton.addEventListener('click', function (event) {
                event.preventDefault();
                formUserLike.action = formUserLike.action.replace('cid', this.dataset.id);
                $('#selectUserLike').modal();
            });
        }
    }
</script>
@endpush
