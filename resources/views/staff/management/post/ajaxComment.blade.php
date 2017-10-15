<div class="panel panel-flat border-left-xlg border-blue">
    <div class="panel-body ">
        <div class="media">
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
                <p class="js-comment-content">{!! convertTextToLink($comment->content) !!}</p>
                @if ($comment->image)
                    <img src="http://ucontent.icheck.vn/{{ $comment->image }}_original.jpg"
                         class="img-responsive"/>
                @endif
                <div class="media-annotation mt-5 js-action-time">
                    <div class="col-md-4"> {{ $comment->createdAt }}</div>
                    <div class="col-md-8 answer">
                        <button type="button"
                                class="btn text-slate-800 btn-flat button-answer" data-id="{{$comment->_id}}">Trả
                            lời<span class="legitRipple-ripple"></span></button>
                        <button type="button"
                                class="btn text-slate-800 btn-flat button-delete" data-url="{{route('Staff::Management::post@deleteComment',['id' => $comment->_id])}}" data-id="{{$comment->_id}}">
                            Xóa<span class="legitRipple-ripple"></span></button>

                    </div>
                    <div style="clear:both"></div>
                </div>

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
                                <input name="enter-message" class="form-control enter-message"
                                       id="{{$comment->_id}}content"
                                       placeholder="Enter your message...">
                            </p>

                            <div class="media-annotation mt-5 js-action-time">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <ul class="icons-list icons-list-extended mt-10">

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
                <div class="child-comment" id="{{$comment->_id}}child-comment">

                </div>
            </div>
        </div>
    </div>
</div>