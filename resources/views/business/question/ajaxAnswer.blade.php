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