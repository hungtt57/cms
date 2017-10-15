<style>
    .panel {
        margin-bottom: 5px !important;
    }

    .button-div {
        text-align: center;
        padding-top: 10px;
    }
</style>
<div class="col-md-6 col-md-offset-3 col-xs-12">
    <div class="title-question">
        <h2>Câu hỏi mã : <b>{{$question->code}}</b></h2>
    </div>
    <div id="list-answer">

        <div class="panel panel-flat border-right-xlg border-blue">
            <div class="panel-body">
                <div class="media">


                    <div class="media-body">Y
                        <p class="js-comment-content">{!! $question->content !!}</p>
                        <div class="media-annotation mt-5 js-action-time">{{date_format($question->created_at,'H:i:s d/m/Y')}}</div>
                    </div>
                    <div class="media-right">
                        <img src="{{ asset('assets/images/image.png') }}"
                             class="img-circle" alt="">
                    </div>
                </div>
            </div>
        </div>

        @if($answer_question)
            @foreach ($answer_question as $answer)
                @if($answer->answerBy == \App\Models\Enterprise\DNAnswerQuestion::ANSWER_BY_ICHECK)
                    <div class="panel panel-flat border-left-xlg border-blue">
                        <div class="panel-body">
                            <div class="media">
                                <div class="media-left">
                                    <img src="{{ asset('assets/images/image.png') }}"
                                         class="img-circle" alt="">
                                </div>

                                <div class="media-body">
                                    <p class="js-comment-content">{!! $answer->content !!}</p>
                                    <div class="media-annotation mt-5 js-action-time">{{date_format($answer->created_at,'H:i:s d/m/Y')}}</div>

                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="panel panel-flat border-right-xlg border-blue">
                        <div class="panel-body">
                            <div class="media">
                                <div class="media-body">
                                    <p class="js-comment-content">{!! $answer->content !!}</p>
                                    <div class="media-annotation mt-5 js-action-time">{{date_format($answer->created_at,'H:i:s d/m/Y')}}</div>

                                </div>
                                <div class="media-right">
                                    <img src="{{ asset('assets/images/image.png') }}"
                                         class="img-circle" alt="">
                                </div>

                            </div>
                        </div>
                    </div>

                @endif
            @endforeach
    </div>
    <div class="row">
        <div class="col-xs-9">
            <textarea name="" id="content-send" class="form-control content-answer"
                      placeholder="Nhập nội dung trả lời"></textarea>

        </div>
        <div class="col-xs-3 button-div">
            <button id="button-send" data-id="{{$question->id}}" class="btn btn-success btn-xs legitRipple">Trả lời</button>
        </div>
        <div class="col-md-12" style="margin-top:20px">
            <label><input type="checkbox" name="status_rep_answer" id="status_rep_answer" class="s" value="1"> Đồng ý với câu trả lời của icheck</label>
            <button class="btn btn-success btn-xs legitRipple"  data-id="{{$question->id}}" id="close-button" style="margin-left:20px">Đóng câu hỏi</button>
        </div>
    </div>

    @endif
</div>
