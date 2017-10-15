@extends('_layouts/staff')

@push('styles_head')
    <style>
        #listPostOfVirtualUser {
            padding: 16px;
        }
        .btn-xs {
            padding: 2px 4px;
        }
    </style>
@endpush

@section('content')
    <section id="listPostOfVirtualUser">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading text-right">
                            <div class="pull-left">
                                {{ session('message') }}
                            </div>
                            <form id="formLikePosts" action="{{ route('Staff::Management::virtualUser@user-like-posts', ['uid' => request()->input('uid')]) }}" method="post">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-primary btn-sm">Like</button>
                            </form>
                        </div>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" id="checkAll">
                                        </label>
                                    </div>
                                </th>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Content</th>
                                <th>Likes</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($posts as $p)
                                <?php $likeCount = $p['footer']['like_count'] ?>
                                <?php $commentCount = $p['footer']['comment_count'] ?>
                                <?php $post = $p['body'] ?>
                                <?php $news = $post['news'] ?>
                                <tr>
                                    <td>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" form="formLikePosts" name="posts[]" value="{{ $post['id'] }}">
                                            </label>
                                        </div>
                                    </td>
                                    <td>{{ $post['id'] }}</td>
                                    <td><div style="width: 48px; height: 48px; {{ ! empty($post['news']['thumb']) ? "background: url('".get_image_url($post['news']['thumb'], 'thumb_small')."') center/contain no-repeat" : '' }}"></div></td>
                                    <td>{{ $news['title'] }}</td>
                                    <td>{{ $news['description'] }}</td>
                                    <td>{{ $news['content'] }}</td>
                                    <td>{{ $likeCount }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <script>
                            {
                                "use strict";

                                var checkAll = document.getElementById('checkAll');
                                var checkboxes = document.querySelectorAll('input[name="posts[]"]');

                                checkAll.addEventListener('change', function (event) {
                                    for (let checkbox of checkboxes) {
                                        checkbox.checked = this.checked;
                                    }
                                });
                            }
                        </script>
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