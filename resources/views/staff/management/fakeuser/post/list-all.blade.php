@extends('_layouts/staff')

@push('styles_head')
    <style>
        #listPostOfVirtualUser {
            padding: 16px;
        }
        .btn-xs {
            padding: 2px 4px;
        }
        .btn-sm {
            padding: 4px 8px;
        }
        .panel-body img {
            width: 100%;
        }
        .lc-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .lc-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .lc-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .ct {
            -webkit-column-count: 3; /* Chrome, Safari, Opera */
            -moz-column-count: 3; /* Firefox */
            column-count: 3;
        }
        .panel {
            margin-bottom: 16px;
            display: inline-block;
        }
    </style>
@endpush

@section('content')
    <div class="panel panel-default" style="margin: 0; margin-top: 8px;display: block">
        <div class="panel-body text-right" style="padding: 8px 24px;">
            <form class="form-inline">
                <div class="form-group">
                    <input type="text" name="query" value="{{ isset($query['query']) ? $query['query'] : '' }}" class="form-control" placeholder="Search" style="width: 320px;">
                </div>
                <div class="form-group">
                    <select name="type" class="form-control">
                        <option value="">All</option>
                        <option value="4" @if(request()->query('type') == 4) selected @endif>News</option>
                        <option value="2" @if(request()->query('type') == 2) selected @endif>Review</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Lọc</button>
            </form>
        </div>
    </div>
    <section id="listPostOfVirtualUser">
        <div class="container-fluid ct">
            {{--<div class="row">--}}
                @foreach($posts as $post)
                    @continue(! isset($post['header']['owner']['social_type']) || ! isset($post['header']['owner']['social_name']))
                    {{--<div class="col-md-4">--}}
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <?php $owner = $post['header']['owner'] ?>
                                <?php $time = \Carbon\Carbon::createFromTimestamp($post['created_at'])->diffForHumans() ?>
                                <?php $likeCount = $post['footer']['like_count'] ?>
                                <?php $commentCount = $post['footer']['comment_count'] ?>
                                <div class="media">
                                    <div class="media-left">
                                        <img width="48" height="48" class="media-object" src="{{ $owner['avatar'] ? get_image_url($owner['avatar']) : ($owner['social_type'] == 'facebook' ? 'http://graph.facebook.com/'.$owner['social_id'].'/picture' : asset('assets/images/image.png')) }}" alt="{{ $owner['social_name'] }}">
                                    </div>
                                    <div class="media-body">
                                        <h4 class="media-heading lc-1">{{ $owner['social_name'] }}</h4>
                                        <ul class="list-inline">
                                            <li>{{ $time }}</li>
                                            <li><span class="p-{{ $post['id'] }}-lc">{{ $likeCount }}</span> <a href="javascript:void(0)" @if(request()->has('lid')) class="btn-like" data-id="{{ $post['id'] }}" @endif><i class="glyphicon glyphicon-hand-left"></i></a></li>
                                            <li>{{ $commentCount }} <a href="{{ route('Staff::Management::virtualUser@post.comments.list', ['post' => $post['id']]) }}"><i class="glyphicon glyphicon-comment"></i></a></li>
                                            <li><a href="{{ request()->fullUrlWithQuery(['uid' => $owner['icheck_id'], 'page' => 1]) }}">List post</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @if (! empty($post['body']['news']))
                                <?php $news = $post['body']['news'] ?>
                                <div class="panel-body" style="padding-top: 0">
                                    @if (! empty($news['thumb']))
                                        <img src="{{ get_image_url($news['thumb']) }}" alt="Thumb" class="img-rounded">
                                    @endif
                                    <h4 class="lc-2">{{ $news['title'] }}</h4>
                                    <p class="lc-3">{{ $news['description'] }}</p>
                                </div>
                            @elseif(! empty($post['body']['product']))
                                <?php $product = $post['body']['product'] ?>
                                <?php $content = $post['body']['content'] ?>
                                <div class="panel-body" style="padding-top: 0">
                                    <div class="media">
                                        <div class="media-left">
                                            <img class="media-object" src="{{ $product['image_default'] ? get_image_url($product['image_default']) : asset('assets/images/image.png') }}" alt="{{ $product['product_name'] }}">
                                        </div>
                                        <div class="media-body">
                                            <h6 class="media-heading lc-2">{{ $product['product_name'] }}</h6>
                                            <ul class="list-inline">
                                                <li>{{ $product['gtin_code'] }}</li>
                                                <li>{{ $product['price_default'] }} {{ $product['currency_default'] }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <p class="lc-3">{{ $content }}</p>
                                </div>
                            @endif
                            <div class="panel-body text-right" style="padding-top: 0">
                                <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#ml{{$post['id']}}">seed like</button>
                                <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#mc{{$post['id']}}">seed comment</button>
                            </div>
                            <div class="modal fade" tabindex="-1" id="ml{{$post['id']}}" role="dialog">
                                <div class="modal-dialog modal-sm" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <form id="formLike{{$post['id']}}" action="{{ route('Staff::Management::virtualUser@post.like', ['post' => $post['id']]) }}">
                                                <input type="hidden" name="post" value="{{ $post['id'] }}">
                                                <div class="form-group">
                                                    <input type="number" name="quantity" value="2" class="form-control" min="1" max="10" placeholder="Số lượng like">
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                                            <button type="submit" form="formLike{{$post['id']}}" class="btn btn-primary">Like</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" tabindex="-1" id="mc{{$post['id']}}" role="dialog">
                                <div class="modal-dialog modal-sm" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <form id="form{{$post['id']}}" action="{{ route('Staff::Management::virtualUser@comment.add') }}">
                                                <input type="hidden" name="post" value="{{ $post['id'] }}">
                                                <select name="quantity" class="form-control">
                                                    <option>2</option>
                                                    <option>6</option>
                                                    <option>10</option>
                                                </select>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                                            <button type="submit" form="form{{$post['id']}}" class="btn btn-primary">Tiếp</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {{--</div>--}}
                @endforeach
            {{--</div>--}}
        </div>
        <div class="text-center">
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
    </section>
    @if(request()->has('lid'))
        <script>
            {
                "use strict";
                var buttonLikes = document.querySelectorAll('.btn-like');
                var userIdLike = '{{ request()->input('lid') }}';
                var likePostUrl = '{{ route('Staff::Management::virtualUser@post.ajax-like', ['post' => 'pid']) }}';

                for (let buttonLike of buttonLikes) {
                    buttonLike.addEventListener('click', function (event) {
                        var id = this.dataset.id;
                        let url = likePostUrl.replace('pid', id);

                        fetch(`${url}?lid=${userIdLike}`, {
                            credentials: 'include'
                        })
                            .then(function (response) {
                                return response.json();
                            })
                            .then(function (response) {
                                if (response.status == 200) {
                                    let lcw = document.querySelector(`.p-${id}-lc`);
                                    lcw.innerText = parseInt(lcw.innerText) + 1;
                                }
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                    });
                }
            }
        </script>
    @endif
@endsection

@push('scripts_foot')
@endpush

@push('scripts_ck')
    <script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
@endpush