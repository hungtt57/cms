@extends('_layouts/staff')

@push('css_files_head')
<style>

    .chat-list {
        overflow: overlay !important;
        height: 520px !important;
    }

    .chat-list .media.reversed {
        margin-right: 20px !important;

    }

</style>
<link rel="stylesheet" href="{{url('assets-chat/font-awesome-4.7.0/css/font-awesome.min.css')}}">
<link rel="stylesheet" href="{{url('assets-chat/css/chat.css')}}">
@endpush
@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2></h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">

                </div>
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
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        {{ session('success') }}
                    </div>
                @endif
                <div class="panel panel-flat chat-lists">

                            <div class="col-md-4 chat-left-box">
                                <div class="left">
                                    <div class="top">
                                        <div class="ui-widget">
                                            <input id="tags">
                                        </div>
                                    </div>
                                    <ul class="people" >


                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-8 chat-right-box">
                                <div class="right">
                                    <div class="top"><span>To: <span class="personName"></span></span></div>


                                </div>
                            </div>



                </div>
            </div>
            <!-- /main content -->
        </div>


        <!-- /page content -->
    </div>
    <!-- /page container -->


@endsection

@push('scripts_foot')
<script>
    var guest = '{{url('image/guest.jpg')}}';
    var cskh = '{{url('image/cskh.png')}}';
    var urlSendNotification = '{{route('Staff::Management::chat@sendNotification')}}';
    var token = '{{ csrf_token() }}';
    var urlUpload = '{{route('Ajax::Staff::upload@image')}}';
    var urlSearchGtin  = '{{route('Staff::Management::chat@searchGtin')}}';
    var tokenLogin = '{{$token}}';
    var tokenIcheck = '{{$tokenIcheck}}';
    var urlSOCKET = 'https://sandbox.icheck.com.vn:4337';
</script>
<script src="{{url('assets-chat/js/jquery-ui.js')}}"></script>
<script src="{{url('/js/icheckio.js')}}"></script>
<script src="{{url('assets-chat/js/firebase.js')}}"></script>
{{--<script src="https://www.gstatic.com/firebasejs/3.6.2/firebase-database.js"></script>--}}
<script src="{{url('assets-chat/js/moment-with-locales.min.js')}}"></script>

<script>

    icheckio.initializeApp(urlSOCKET, {
        'query': 'token='+tokenIcheck
    });



    $(document).on('mousedown', '.left .person', function (e) {
        if ($(this).hasClass('.active')) {
            return false;
        } else {
            var findChat = $(this).attr('data-chat');
            var personName = $(this).find('.name').text();
            $('.right .top .personName').html(personName);
            $('.chat').removeClass('active-chat');
            $('.left .person').removeClass('active');
            $(this).addClass('active');

                if(($(this).find('.unread').text())){
                    console.log(1111);
                    var realId = findChat.replace('_', '|');
                    var currentUser = firebase.auth().currentUser;
                    var userId = currentUser.uid;
                    firebase.database().ref('rooms-users/' + userId + '/' + realId+ '/').child('isRead').set(true);

                }
            $(this).find('.unread').remove();
            $(this).removeClass('has-message');
            $('.chat[data-chat = ' + findChat + ']').addClass('active-chat');
            $('.chat[data-chat = ' + findChat + '] .conversation-start').animate({scrollTop: $('.chat[data-chat = ' + findChat + '] .conversation-start').prop("scrollHeight")}, 10);



        }
    });

</script>
<script src="{{url('assets-chat/js/main.js')}}"></script>

<script>
    $(function () {
        $("#tags").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "{{route('Staff::Management::chat@search')}}",
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function (data) {
                        response(data);
                    },
                    error:function(error){
                        alert('Đang xảy ra lỗi !! Vui lòng thử lại sau.');
                    }
                });
            },
            select: function (event, ui) {

                var account_id  = ui.item.social_id;
                var icheck_id = ui.item.icheck_id;
                var name = ui.item.social_name;
                var userId =  firebase.auth().currentUser.uid;
                var url ='https://graph.facebook.com/'+account_id+'/picture?width=40&height=40';
                var id ='';
                if(userId.localeCompare(icheck_id) == -1 ){
                    id = userId+'_'+icheck_id;
                }
                if(userId.localeCompare(icheck_id) == 1 ){
                    id =icheck_id+'_'+userId;
                }

                if($('.'+id).length > 0 ){
                    if ($('.'+id).hasClass('.active')) {
                        return false;
                    } else {
                        var findChat = $('.'+id).attr('data-chat');
                        var personName = $('.'+id).find('.name').text();
                        $('.right .top .name').html(personName);
                        $('.chat').removeClass('active-chat');
                        $('.left .person').removeClass('active');
                        $('.'+id).addClass('active');
                        $('.chat[data-chat = ' + findChat + ']').addClass('active-chat');
                        $('.chat[data-chat = ' + findChat + '] .conversation-start').animate({scrollTop: $('.chat[data-chat = ' + findChat + '] .conversation-start').prop("scrollHeight")}, 10);
                    }
                }else{
                    $('.chat').removeClass('active-chat');
                    $('.left .person').removeClass('active');

                    firebase.database().ref('users/'+icheck_id).once('value').then(function(snapshot){

                        // append left chat

                        var people = '<li class="active person '+id+'"  data-chat="'+id+'" ><img src="'+url+'" alt="" /> <span class="name">'+name+'</span> <span class="preview"></span> </li>';
                        $('.people').prepend(people);

                        // append chatbox
                        var chat = '<div class="chat chat'+id+'" data-chat="'+id+'">' +
                                '<div class="conversation-start" onscroll="myScroll(this)"></div>' +
                                '<div class="write">' +
                                '<textarea name="enter-message" class="enter-message"  placeholder="Enter your message..."></textarea> ' +
                                ' <button type="button" class="btn bg-teal-400 btn-labeled btn-labeled-right send-message"><b><i class="icon-circle-right2"></i></b> Send</button>' +
                                '</div>' +
                                '<div class="upload-image">' +
                                '' +
                                '<label for="file-input-'+id+'">' +
                                '<i class="fa fa-picture-o"></i>' +
                                '</label>' +
                                '<input type="file" class="file-input" id="file-input-'+id+'" name="upload-image-input" >' +
                                '' +
                                '</div>' +
                                '<div class="upload-gtin">' +
                                '' +
                                '<label class="label-gtin">' +
                                '<i class="fa fa-barcode" aria-hidden="true"></i>' +
                                '</label>' +
                                '<input type="text" placeholder="nhập gtin_code " class="input-gtin" data-id="'+id+'" id="gtin-input-'+id+'" name="upload-gtin-input" >' +
                                '' +
                                '</div>'+
                                '</div>';

                        $('.right').append(chat);
                        $('.chat[data-chat = ' + id + ']').addClass('active-chat');
                        $('.right .top .personName').html(name);

                        initGtin('gtin-input-'+id);
                    });

                    icheckio.ref("/users/"+icheck_id+"/popup").on('updated', function(data){
                        $('.' + id).attr('data-online', data.is_online);
                    });

                }



            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) {

            var image ='https://graph.facebook.com/'+item.social_id+'/picture?width=40&height=40';

            var template = '<img class="image-search" src="'+image+'">'+'<span class="name-search">'+item.social_name+'</span>';
            return $('<li>').append(template).appendTo(ul);
        };
    });



</script>

@endpush
