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
            margin-top: 10px;
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
        .box{
            width: 15px;
            height:15px;
            display: inline-block;

        }
        .red{
            background-color: red;
        }
        .yellow{
            background-color: yellow;
        }
        .blue{
            background-color: blue;
        }
        .form-control{
            width:100% !important;
        }
    </style>
@endpush

@section('content')
    <div class="panel panel-default " style="margin: 0; margin-top: 8px;display: block">

        <div class="panel-body text-right" style="padding: 8px 24px;">
            <form class="form-inline">
                <div class="form-group" style="width:300px;">
                    <input type="text" name="user" id="user" value="{{ Request::input('user') }}" class="form-control" placeholder="Nhập tên user">
                </div>
                <div class="form-group" style="width:300px;">
                    <input type="text" name="date" id="created-at-to" value="{{ Request::input('date') }}" class="form-control js-date-picker" placeholder="chọn ngày">
                </div>
                <div class="form-group">

                    <select name="type" class="form-control">
                        <option value="" >All</option>
                        <option value="-1" @if(request()->query('type') == -1) selected @endif> Không nên dùng</option>
                        <option value="0" @if( (string) request()->query('type') == (string) 0) selected @endif>Bình thường</option>
                        <option value="1" @if(request()->query('type') == 1) selected @endif>Nên dùng</option>
                    </select>
                </div>
                <div class="form-group">

                    <select name="resolved" class="form-control">
                        <option value="0" >All</option>
                        <option value="1" @if(request()->query('resolved') == 1) selected @endif>Đã cộng điểm</option>
                        <option value="2" @if( (string) request()->query('resolved') == (string) 2) selected @endif>Không cộng điểm</option>
                        <option value="3" @if(request()->query('resolved') == 3) selected @endif>Chưa xử lý</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Lọc</button>
            </form>
        </div>
    </div>

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
    <section id="listPostOfVirtualUser">
        <div class="container-fluid ct">

                @foreach($comments as $comment)
                    {{--@continue(! isset($comment->owner['social_type']) || ! isset($comment->owner['social_id']))--}}

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <?php $owner = $comment->owner;
                                    $name = '';

                                if(isset($owner['social_name'])){
                                    $name = $owner['social_name'];
                                }
                                if(isset($comment->account()->name)){
                                    $name = $comment->account()->name;
                                }
                                $image_account = '';
                                    if(isset($comment->account()->account_id)){
                                        $image_account = 'http://graph.facebook.com/'.$comment->account()->account_id.'/picture';
                                    }elseif(isset($owner['social_id'])){
                                        $image_account = 'http://graph.facebook.com/'.$owner['social_id'].'/picture';
                                    }else

                                $icheck_id = '';
                                if(isset($comment->account()->icheck_id)){
                                    $icheck_id = $comment->account()->icheck_id;
                                }elseif(isset($owner['icheck_id'])){
                                    $icheck_id = $owner['icheck_id'];
                                }


                                ?>
                                <?php $vote_type = $comment->vote_type; ?>
                                <?php $time =$comment['createdAt'];?>
                                <?php $likeCount = isset($comment->like_count) ? $comment->like_count : 0; ?>

                                <div class="media">
                                    <div class="media-left">
                                        <img width="48" height="48" class="media-object" src="{{($image_account) ? $image_account : asset('assets/images/image.png')}}" alt="{{ $name }}">
                                    </div>
                                    <div class="media-body">
                                        <h4 class="media-heading lc-1">{{ $name}} <span class="box
                                             @if($vote_type == -1) red @elseif($vote_type == 0) yellow @else blue @endif"></span></h4>
                                        <ul class="list-inline">
                                            <li>{{ $time }}</li>
                                            <li><span class="p-{{ $comment->id }}-lc">{{ $likeCount }}</span> <a href="javascript:void(0)" @if(request()->has('lid')) class="btn-like" data-id="{{ $comment->id }}" @endif><i class="glyphicon glyphicon-hand-left"></i></a></li>
                                            @if($icheck_id)
                                                <li><a href="{{route('Staff::Management::statistical@listCommentByUser',['icheck_id'=>$icheck_id])}}">List review</a></li>
                                                @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            @if (! empty($comment->product))
                                <?php $product = $comment->product ?>
                                <?php $content = $comment->content;?>
                                <div class="panel-body" style="padding-top: 0">
                                    <div class="media">
                                        <div class="media-left">
                                            <img class="media-object" src="{{ @$product->image_default ? get_image_url($product->image_default) : asset('assets/images/no_product.png') }}" alt="{{ @$product->product_name }}">
                                        </div>
                                        <div class="media-body">
                                            <h6 class="media-heading lc-2">{{@$product->product_name }}</h6>
                                            <ul class="list-inline">
                                                <li>{{@$product->gtin_code }}</li>
                                                <li>{{ @$product->price_default}}</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <p class="lc-3">{{ $content }}</p>
                                </div>
                                @else
                                <?php $content = $comment->content;?>
                                <div class="panel-body" style="padding-top: 0">

                                    <p class="lc-3">{{ $content }}</p>
                                </div>
                            @endif

                            <div class="panel-body text-right" style="padding-top: 0">
                                @if( $comment->addPoint != \App\Models\Mongo\Product\PComment::ADDED_POINT)
                                <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#ml{{$comment->id}}">Cộng điểm</button>
                                @endif
                                @if( !isset($comment->addPoint) )

                                        <a class="not-add-point" href="{{route('Staff::Management::statistical@notAddPoint',['id' => $comment->id])}}">
                                            <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#mc{{$comment->id}}">Không cộng điểm</button>

                                        </a>

                                    @endif
                            </div>
                            <div class="modal fade" tabindex="-1" id="ml{{$comment->id}}" role="dialog">
                                <div class="modal-dialog modal-sm" role="document">

                                    <div class="modal-content">
                                        <div class="modal-header bg-warning">
                                            <button type="button" class="close" data-dismiss="modal">×</button>
                                            <h6 class="modal-title">Chọn số điểm muốn cộng</h6>
                                        </div>
                                        <div class="modal-body ">
                                            <?php $listPoint = config('listPoint');
                                         ?>
                                            @if($listPoint)
                                                @foreach($listPoint as $key => $point)
                                                        <a class="point-link"
                                                           href="{{route('Staff::Management::statistical@addPoint',['id' => $comment->id,'icheck_id' => $icheck_id,'point' => $key])}}"
                                                        >  <button class="btn btn-warning">{{$point}}</button> </a>
                                                    @endforeach
                                                @endif

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                @endforeach

        </div>
        <div class="text-center">

               {!! $comments->appends(Request::all())->render() !!}

        </div>
    </section>

@endsection

@push('js_files_foot')
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/picker.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/picker.date.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/picker.time.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/legacy.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
@endpush
@push('scripts_foot')
<script>
    $('.not-add-point').click(function(e){
        if(confirm('Bạn có chắc chắn không cộng điểm !')){
            return true;
        }else{
            e.preventDefault()
        }
    });
    $('.point-link').click(function(e){
        if(confirm('Bạn có chắc chắn muốn cộng point cho user này')){
            return true;
        }else{
            e.preventDefault()
        }

    });
    $('#created-at-to').pickadate({
        format: 'dd-mm-yyyy'
    });
</script>
@endpush

@push('scripts_ck')

    <script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
@endpush