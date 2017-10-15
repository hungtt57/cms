@extends('_layouts/default')

@section('content')
    <style>
        .answer {
            text-align: right;
        }

        .button-answer {

        }
        .add-comment-button{
            float: right;
        }
        .pin{
            position: absolute;
            right:0px;
            z-index: 99;
        }
        .pin .pined{
            font-size: 15px;
            padding-left: 5px;
        }
    </style>
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>
                    <a href="{{ route('Business::product@index') }}" class="btn btn-link">
                        <i class="icon-arrow-left8"></i>
                    </a>
                    Bình luận về sản phẩm {{$product->name}}
                    @if($account)
                    <button  class="btn btn-success btn-xs legitRipple add-comment-button">Thêm bình luận</button>
                    @endif
                </h2>
            </div>
        </div>
    </div>
    <!-- /page header -->
    <!-- Page container -->
    <div class="page-container">
        <!-- Page content -->
        <div class="page-content">
            <!-- Main content -->
            <div class="content-wrapper">

                @if (session('success'))
                    <div class="alert bg-success alert-styled-left">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert bg-danger alert-styled-left">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        {{ session('error') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-offset-3 col-md-6" id="commented-list">
                        @if($account)
                        <div class="panel panel-flat border-left-xlg border-blue " id="add-comment" style="display:none">
                            <div class="panel-body">
                        <div class="add-comment" id="" >
                            <div class="media">
                                <div class="media-left">
                                    @if ($account->account_id)
                                        <img src="http://graph.facebook.com/{{ $account->account_id}}/picture"
                                             class="img-circle" alt="">
                                    @else
                                        <img src="{{ asset('assets/images/image.png') }}"
                                             class="img-circle" alt="">
                                    @endif

                                </div>
                                <div class="media-body">
                                    <h6 class="media-heading"><strong
                                                class="js-actor-name">{{$account->name}}</strong>
                                    </h6>
                                    <p class="js-comment-content">
                                        <input name="enter-message" class="add-comment-content form-control enter-message"
                                                placeholder="Enter your message...">
                                    </p>

                                    <div class="media-annotation mt-5 js-action-time">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <ul class="icons-list icons-list-extended mt-10">
                                                    {{--<li><a href="#" data-popup="tooltip" title=""--}}
                                                    {{--data-container="body"--}}
                                                    {{--data-original-title="Send photo"><i--}}
                                                    {{--class="icon-file-picture"></i></a>--}}
                                                    {{--</li>--}}
                                                </ul>
                                            </div>

                                            <div class="col-xs-6 text-right">
                                                <button type="button"
                                                        class="add-button-send btn bg-teal-400 btn-labeled btn-labeled-right legitRipple">
                                                    <b><i class="icon-circle-right2"></i></b> Send
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                            </div>
                        </div>

                        @endif



                    @foreach ($comments as $comment)
                        {{--@php dd($comment); @endphp--}}
                            <div class="panel panel-flat border-left-xlg border-blue" >
                                <div class="panel-body " >
                                    <div class="media">
                                        @if($comment->score==1)
                                            <div class="pin">
                                                <i class="glyphicon glyphicon-pushpin"></i>
                                                <button type="button"
                                                        class="btn text-slate-800 btn-flat button-unpin" data-url="{{route('Business::product@unpinComment',['id' => $comment->_id])}}" data-id="{{$comment->_id}}">Bỏ Gim<span class="legitRipple-ripple"></span></button>
                                            </div>

                                        @endif
                                        <div class="media-left">

                                            @if(isset($comment->account()->account_id))
                                                <img src="http://graph.facebook.com/{{ $comment->account()->account_id }}/picture"
                                                     class="img-circle" alt="">
                                                @else
                                                <img src="{{ asset('assets/images/image.png') }}"
                                                     class="img-circle" alt="">
                                                @endif

                                        </div>
                                        <div class="media-body">
                                            <h6 class="media-heading"><strong
                                                        class="js-actor-name">{{$comment->account()->name}}</strong>
                                            </h6>
                                            <p class="js-comment-content">{{ $comment->content }}</p>
                                            @if ($comment->image)
                                                <img src="http://ucontent.icheck.vn/{{ $comment->image }}_original.jpg"
                                                     class="img-responsive"/>
                                            @endif
                                            <div class="media-annotation mt-5 js-action-time">
                                                <div class="col-md-3"> {{ $comment->createdAt }}</div>
                                                @if($account)
                                                <div class="col-md-9 answer">
                                                    <button type="button"
                                                            class="btn text-slate-800 btn-flat button-answer" data-id="{{$comment->_id}}">Trả
                                                        lời<span class="legitRipple-ripple"></span></button>
                                                    @if( $comment->account()->icheck_id == $account->icheck_id)
                                                        @if($comment->score!=1)
                                                            <button type="button"
                                                                    class="btn text-slate-800 btn-flat button-pin" data-url="{{route('Business::product@pinComment',['id' => $comment->_id])}}" data-id="{{$comment->_id}}">Gim<span class="legitRipple-ripple"></span></button>

                                                        @endif

                                                        <button type="button"
                                                                class="btn text-slate-800 btn-flat button-delete" data-url="{{route('Business::product@deleteComment',['id' => $comment->_id])}}" data-id="{{$comment->_id}}">
                                                            Xóa<span class="legitRipple-ripple"></span></button>

                                                    @endif
                                                </div>
                                                @endif
                                                <div style="clear:both"></div>
                                            </div>
                                            @if($account)
                                            <div class="answer-comment" id="{{$comment->_id}}answer-comment" style="display:none">
                                                <div class="media">
                                                    <div class="media-left">
                                                        @if ($account->account_id)
                                                            <img src="http://graph.facebook.com/{{ $account->account_id}}/picture"
                                                                 class="img-circle" alt="">
                                                        @else
                                                            <img src="{{ asset('assets/images/image.png') }}"
                                                                 class="img-circle" alt="">
                                                        @endif

                                                    </div>
                                                    <div class="media-body">
                                                        <h6 class="media-heading"><strong
                                                                    class="js-actor-name">{{$account->name}}</strong>
                                                        </h6>
                                                        <p class="js-comment-content">
                                                            <input  name="enter-message" class="form-control enter-message" id="{{$comment->_id}}content"
                                                                      placeholder="Enter your message...">
                                                        </p>

                                                        <div class="media-annotation mt-5 js-action-time">
                                                            <div class="row">
                                                                <div class="col-xs-6">
                                                                    <ul class="icons-list icons-list-extended mt-10">
                                                                        {{--<li><a href="#" data-popup="tooltip" title=""--}}
                                                                               {{--data-container="body"--}}
                                                                               {{--data-original-title="Send photo"><i--}}
                                                                                        {{--class="icon-file-picture"></i></a>--}}
                                                                        {{--</li>--}}
                                                                    </ul>
                                                                </div>

                                                                <div class="col-xs-6 text-right">
                                                                    <button type="button" data-id="{{$comment->_id}}"
                                                                            class="send btn bg-teal-400 btn-labeled btn-labeled-right legitRipple">
                                                                        <b><i class="icon-circle-right2"></i></b> Send
                                                                    </button>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="child-comment" id="{{$comment->_id}}child-comment" >
                                            @foreach ($comment->childs() as $child)

                                                @php @endphp

                                                <div class="media" >
                                                    <div class="media-left">
                                                        @if(isset($child->account()->account_id))
                                                            <img src="http://graph.facebook.com/{{ $child->account()->account_id}}/picture"
                                                                 class="img-circle" alt="">
                                                        @else
                                                            <img src="{{ asset('assets/images/image.png') }}"
                                                                 class="img-circle" alt="">
                                                        @endif
                                                    </div>

                                                    <div class="media-body">
                                                        <h6 class="media-heading"><strong
                                                                    class="js-actor-name">{{ $child->account()->name }}</strong>
                                                        </h6>
                                                        <p class="js-comment-content">{{ $child->content }}</p>
                                                        @if ($child->image)
                                                            <img src="http://ucontent.icheck.vn/{{ $child->image }}_original.jpg"
                                                                 class="img-responsive"/>
                                                        @endif
                                                        <div class="media-annotation mt-5 js-action-time">

                                                            <div class="col-md-3"> {{ $child->createdAt }}</div>

                                                            <div class="col-md-9 answer">
                                                                @if($account)
                                                                @if( $child->account()->icheck_id == $account->icheck_id)
                                                                    <button type="button"
                                                                            class="btn text-slate-800 btn-flat button-delete" data-url="{{route('Business::product@deleteComment',['id' => $child->_id])}}" data-id="{{$child->_id}}">
                                                                        Xóa<span class="legitRipple-ripple"></span></button>

                                                                @endif
                                                                @endif
                                                            </div>
                                                            <div style="clear:both"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- /main content -->
        </div>
        <!-- /page content -->
    </div>
    <!-- /page container -->
@endsection

@push('js_files_foot')
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/notifications/pnotify.min.js') }}"></script>
{{--<script type="text/javascript" src="{{ asset('assets/js/pages/components_notifications_pnotify.js') }}"></script>--}}

@endpush

@push('scripts_foot')
<script>
    $(document).ready(function () {
        $(".js-help-icon").popover({
            html: true,
            trigger: "hover",
            delay: {"hide": 1000}
        });

        // Initialize with options
        $(".js-categories-select").select2({
            templateResult: function (item) {
                if (!item.id) {
                    return item.text;
                }

                var originalOption = item.element,
                        prefix = "----------".repeat(parseInt($(item.element).data('level'))),
                        item = (prefix ? prefix + '| ' : '') + item.text;

                return item;
            },
            templateSelection: function (item) {
                return item.text;
            },
            escapeMarkup: function (m) {
                return m;
            },
            dropdownCssClass: 'border-primary',
            containerCssClass: 'border-primary text-primary-700'
        });

        // Initialize with options
        $(".js-select").select2();

        // Checkboxes, radios
        $(".js-radio").uniform({radioClass: "choice"});

        // File input
        $(".js-file").uniform({
            fileButtonClass: "action btn btn-default"
        });

        $(document).on('click','.add-comment-button',function(){
            $('#add-comment').toggle();

        });


        $(document).on('click', '.button-answer', function(){
            var id = $(this).attr('data-id');
            $('#'+id+'answer-comment').toggle();

        });
        $(document).on('click','.send',function(){
            var id = $(this).attr('data-id');
            var content = $('#'+id+'content').val();
            var gtin = "{{$gtin}}";
            var url  = "{{route('Business::product@answerComment')}}";

            if(content.trim()!=''){
                $.ajax({
                    type: "POST",
                    url: url,
                    headers: {
                        'X-CSRF-Token': "{{ csrf_token() }}"
                    },
                    data: {
                        content : content,
                        parent_id : id,
                        gtin_code : gtin,

                    },
                    dataType: 'html',
                    success: function (data) {
                        $('#'+id+'content').val('');
                        $('#'+id+'child-comment').prepend(data);
                    },
                    error: function () {
                        new PNotify({
                            text: 'Lỗi xảy ra.Vui lòng thử lại!',
                            addclass: 'bg-danger'
                        });
                    }
                });
            }else{
                new PNotify({
                    text: 'Vui lòng nhập nội dung!',
                    addclass: 'bg-danger'
                });
            }
        });


        $(document).on('click','.add-button-send',function(){
            var id = $(this).attr('data-id');
            var content = $('.add-comment-content').val();
            var gtin = "{{$gtin}}";
            var url  = "{{route('Business::product@addComment')}}";

            if(content.trim()!=''){
                $.ajax({
                    type: "POST",
                    url: url,
                    headers: {
                        'X-CSRF-Token': "{{ csrf_token() }}"
                    },
                    data: {
                        content : content,
                        gtin_code : gtin,

                    },
                    dataType: 'html',
                    success: function (data) {

                        $(data).insertAfter('#add-comment');
                        $('.add-comment-content').val('');

                    },
                    error: function () {
                        new PNotify({
                            text: 'Lỗi xảy ra.Vui lòng thử lại!',
                            addclass: 'bg-danger'
                        });
                    }
                });
            }else{
                new PNotify({
                    text: 'Vui lòng nhập nội dung!',
                    addclass: 'bg-danger'
                });
            }
        });



        $(document).on('click','.button-delete',function(){
            if(confirm('Bạn có muốn xóa')){
                var url = $(this).attr('data-url');
                window.location.href = url;
            }

        });

        $(document).on('click','.button-pin',function(){
            if(confirm('Bạn có muốn gim')){
                var url = $(this).attr('data-url');
                window.location.href = url;
            }

        });

        $(document).on('click','.button-unpin',function(){
            if(confirm('Bạn có muốn bỏ gim bài viết này')){
                var url = $(this).attr('data-url');
                window.location.href = url;
            }

        });

    });
</script>
@endpush
