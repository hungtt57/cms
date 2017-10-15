'use strict';

// Initializes FriendlyChat.
function FriendlyChat() {

    this.initFirebase();

}
var scrolling = 1;
var oldTimeStamp = 0;
// Sets up shortcuts to Firebase features and initiate firebase auth.
FriendlyChat.prototype.initFirebase = function () {
    var config = {
        apiKey: "AIzaSyC3y3I6RKQvHLY0mn7Fqxd6nJVgZ1wZ9-U",
        authDomain: "helical-history-126218.firebaseapp.com",
        databaseURL: "https://helical-history-126218.firebaseio.com",
        storageBucket: "helical-history-126218.appspot.com",
        messagingSenderId: "873655301603"
    };
    firebase.initializeApp(config);

    // Shortcuts to Firebase SDK features.
    this.auth = firebase.auth();
    this.database = firebase.database();
    this.online = [];
    // // Initiates Firebase auth and listen to auth state changes.
    var that = this;
    this.auth.signOut().then(function(){
        that.auth.onAuthStateChanged(that.onAuthStateChanged.bind(that));
    });
};

// Signs-in Friendly Chat.
FriendlyChat.prototype.signIn = function () {
    firebase.auth().signInWithCustomToken(tokenLogin).then(function (data) {

    }).catch(function (error) {
        var errorCode = error.code;
        var errorMessage = error.message;
        console.log(errorMessage);
    });
};

// Signs-out of Friendly Chat.
FriendlyChat.prototype.signOut = function () {
    var currentUser = this.auth.currentUser;
    var uid = currentUser.uid;
    var d = firebase.database.ServerValue.TIMESTAMP;
    // var profile = firebase.database().ref('users/' + uid);
    // profile.child('isOnline').set(false);
    // profile.child('lastActivityTime').set(d);
    this.auth.signOut();
};

// Triggers when the auth state change for instance when the user signs-in or signs-out.
FriendlyChat.prototype.onAuthStateChanged = function (user) {
    if (user) { // User is signed in!

        // Get uid and email
        var userId = user.uid,
            email = user.email;

        var database = firebase.database().ref('rooms-users/' + userId);
        var conversions = database.orderByChild('timestamp').limitToLast(10);
        conversions.on('child_added', function (conversion) {
            var id = conversion.key.split('|')[0] + '_' + conversion.key.split('|')[1];
            var o = conversion.val();


            var name = '';
            var idTo = '';
            if (o.from == userId) {
                idTo = o.to;
            } else {

                idTo = o.from;
            }

            if ($("." + id).length > 0) {

            } else {
                var social_id = firebase.database().ref('users/' + idTo).once('value').then(function (snapshot) {
                    if (snapshot.val()) {
                        var url = 'https://graph.facebook.com/' + snapshot.val().social_id + '/picture?width=40&height=40';
                        var name = snapshot.val().name;
                    } else {
                        var url = 'http://s3.postimg.org/yf86x7z1r/img2.jpg';
                        var name = idTo;
                    }

                    var people = '<li class="person ' + id + '" data-timestamp="'+ o.timestamp +'" data-chat="' + id + '" ><img src="' + url + '" alt="" /> <span class="name">' + name + '</span> <span class="preview">' + o.content + '</span> </li>';
                    $('.people').prepend(people);
                    if (o.isRead == false) {
                        $('.' + id).append('<span class="unread">1</span>');
                        $('.'+id).addClass('has-message');
                    }

                });

            }
            if (o.isRead == false) {
                $('.'+id).addClass('has-message');
                $('.' + id).append('<span class="unread">1</span>');

            }
            // firebase.database().ref('users/' + idTo).on('child_changed', function (snapshot) {
            //
            //     if (snapshot.key == 'isOnline') {
            //         $('.' + id).attr('data-online', snapshot.val());
            //     }
            // });

            icheckio.ref("/users/"+idTo+"/popup").on('updated', function(data){
                $('.' + id).attr('data-online', data.is_online);
            });




            if ($('.chat' + id).length > 0) {

            } else {
                var chat = '<div class="chat chat' + id + '" data-chat="' + id + '">' +
                    '<div class="conversation-start" onscroll="myScroll(this)"></div>' +
                    '<div class="write">' +
                    '<textarea name="enter-message" class="enter-message"  placeholder="Enter your message..."></textarea> ' +
                    ' <button type="button" class="btn bg-teal-400 btn-labeled btn-labeled-right send-message"><b><i class="icon-circle-right2"></i></b> Send</button>' +
                    '</div>' +

                    '<div class="upload-image">' +
                    '' +
                    '<label for="file-input-' + id + '">' +
                    '<i class="fa fa-picture-o"></i>' +
                    '</label>' +
                    '<input type="file" class="file-input" id="file-input-' + id + '" name="upload-image-input" >' +
                    '' +
                    '</div>' +

                    '<div class="upload-gtin">' +
                    '' +
                    '<label class="label-gtin">' +
                    '<i class="fa fa-barcode" aria-hidden="true"></i>' +
                    '</label>' +
                    '<input type="text" placeholder="nhập gtin_code " class="input-gtin" data-id="' + id + '" id="gtin-input-' + id + '" name="upload-gtin-input" >' +
                    '' +
                    '</div>' +

                    '</div>';

                $('.right').append(chat);
                initGtin('gtin-input-' + id);
            }

            //append message
            var child = firebase.database().ref('chat_data/u2u/' + conversion.key + '/messages').orderByChild('timestamp').limitToLast(10);
            child.on('child_added', function (s) {
                var noidung = '';
                moment.locale('vi');
                var m = s.val();
                if(m.content){
                    noidung = convertSpace(m.content);
                }
                var notify_content = noidung;
                $("." + id ).find('.preview').html(noidung);
                noidung = convert(noidung);
                if (m.image) {
                    noidung += '<img data-image="' + m.image + '" class="img-chat" src="http://ucontent.icheck.vn/' + m.image + '_small.jpg">';
                    notify_content = 'đã gửi bạn một ảnh';
                }
                if (m.gtin_code) {
                    noidung += '<div class="template_product span-' + m.gtin_code + '">Gtin_code:' + m.gtin_code + '</div>';
                    getInfoProduct(m.gtin_code);
                    notify_content = 'đã gửi bạn một sản phẩm';
                }
                var active_chat = $('.active-chat').attr('data-chat');
                if(active_chat != undefined ){
                    var name = $("." + id).find('.name').text();
                    if(active_chat != id){
                        $('.'+id).addClass('has-message');
                        $('.' + id).append('<span class="unread">1</span>');
                        // new PNotify({
                        //     title: name,
                        //     text: notify_content,
                        // });
                        var clone = $('.'+id).clone();
                        $('.'+id).remove();
                        $('.people').prepend(clone);
                    }
                }

                var templateTo = '<div class="bubble me"><p>' + noidung + '</p> </div>' +
                    '<p class="timestamp">' + m.timestamp + '</p>' +
                    '<p class="time-chat">' + moment.unix(m.timestamp / 1000).fromNow() + '</p>';

                var templateFrom = '<div class="bubble you"><p>' + noidung + '</p> </div>' +
                    '<p class="timestamp">' + m.timestamp + '</p>' +
                    '<p class="time-chat">' + moment.unix(m.timestamp / 1000).fromNow() + '</p>';
                if (userId == m.from) {
                    $('.chat' + id + ' .conversation-start').append(templateFrom);
                } else {
                    $('.chat' + id + ' .conversation-start').append(templateTo);

                }
                $('.chat[data-chat = ' + id + '] .conversation-start').animate({scrollTop: $('.chat[data-chat = ' + id + '] .conversation-start').prop("scrollHeight")}, 10);
                $('.img-chat').load(function () {
                    $('.chat[data-chat = ' + id + '] .conversation-start').animate({scrollTop: $('.chat[data-chat = ' + id + '] .conversation-start').prop("scrollHeight")}, 10);
                });
            });


        });

        // thong bao soluong icon
        conversions.on('child_changed', function(snapshot) {
            var message = snapshot.val();
            if(message.isRead == false){
                var count =  $('#message-unread-count').text();
                if(count == ''){
                    count = 0;
                }else{
                    count = parseInt($('#message-unread-count').text()) + 1;
                }
                $('#message-unread-count').text(count);

            }else{
                var count =  $('#message-unread-count').text();

                if(count != 0){
                    count = 0;
                }else{
                    count = parseInt($('#message-unread-count').text()) - 1;
                }
                if(count < 0){
                    count = 0;
                }
                $('#message-unread-count').text(count);
            }
        });
        conversions.once('value', function(snapshot) {
            var message = snapshot.val();
            for(var key in message){
                if (message.hasOwnProperty(key)) {
                    if(message[key].isRead == false){
                        var count =  $('#message-unread-count').text();
                        if(count == ''){
                            count = 0;
                        }else{
                            count = parseInt($('#message-unread-count').text()) + 1;
                        }
                        $('#message-unread-count').text(count);

                    }

                }
            }

        });

    } else { // User is signed out!

        this.signIn();
    }
};


FriendlyChat.LOADING_IMAGE_URL = 'https://www.google.com/images/spin-32.gif';

FriendlyChat.prototype.saveMessage = function (id, content) {

    // var d = new Date();
    // d = d.getTime();

    var d = firebase.database.ServerValue.TIMESTAMP;
    var currentUser = this.auth.currentUser;
    var userId = currentUser.uid;
    var toUser = '';

    if (id.split('|')[0] != userId) {
        toUser = id.split('|')[0];
    } else {
        toUser = id.split('|')[1];
    }


    var messageFrom = {
        content: content,
        from: userId,
        to: toUser,
        isRead: true,
        timestamp: d
    };

    var messageTo = {
        content: content,
        from: userId,
        to: toUser,
        isRead: false,
        timestamp: d
    };

    var message = {
        content: content,
        from: userId,
        to: toUser,
        timestamp: d
    };
    var messageRef = firebase.database().ref('chat_data/u2u/' + id + '/messages');
    var roomRefFrom = firebase.database().ref('rooms-users/' + userId + '/');
    roomRefFrom.child(id).set(messageFrom);
    var roomRefTo = firebase.database().ref('rooms-users/' + toUser);
    roomRefTo.child(id).set(messageTo);
    messageRef.push().set(message);

    var realId = id.replace('|', '_');

        $('.'+realId).removeClass('has-message');

    if ($('.' + realId).attr('data-online') == 'false') {
        sendNotificationOffline(userId, toUser, content, 'CSKH');
    }


};

FriendlyChat.prototype.imageMessage = function (id, name) {
    var d = firebase.database.ServerValue.TIMESTAMP;

    var currentUser = this.auth.currentUser;
    var userId = currentUser.uid;

    var toUser = '';

    if (id.split('|')[0] != userId) {
        toUser = id.split('|')[0];
    } else {
        toUser = id.split('|')[1];
    }

    var messageFrom = {
        content: '',
        from: userId,
        to: toUser,
        image: name,
        isRead: true,
        timestamp: d
    };

    var messageTo = {
        content: '',
        from: userId,
        to: toUser,
        image: name,
        isRead: false,
        timestamp: d
    };

    var message = {
        content: '',
        from: userId,
        to: toUser,
        image: name,
        timestamp: d
    };
    var messageRef = firebase.database().ref('chat_data/u2u/' + id + '/messages');
    var roomRefFrom = firebase.database().ref('rooms-users/' + userId + '/');
    roomRefFrom.child(id).set(messageFrom);
    var roomRefTo = firebase.database().ref('rooms-users/' + toUser);
    roomRefTo.child(id).set(messageTo);
    messageRef.push().set(message);

    var realId = id.replace('|', '_');
    $('.'+realId).removeClass('has-message');
    if ($('.' + realId).attr('data-online') == 'false') {
        sendNotificationOffline(userId, toUser, 'CSKH vửa gửi cho bạn một hình ảnh', 'CSKH');
    }


};

function gtinMessage(gtin_id, gtin_code) {
    var id = $('#' + gtin_id).attr('data-id');


    var d = firebase.database.ServerValue.TIMESTAMP;

    var currentUser = firebase.auth().currentUser;
    var userId = currentUser.uid;

    var toUser = '';

    if (id.split('_')[0] != userId) {
        toUser = id.split('_')[0];
    } else {
        toUser = id.split('_')[1];
    }

    var messageFrom = {
        content: '',
        from: userId,
        to: toUser,
        gtin_code: gtin_code,
        isRead: true,
        timestamp: d
    };

    var messageTo = {
        content: '',
        from: userId,
        to: toUser,
        gtin_code: gtin_code,
        isRead: false,
        timestamp: d
    };

    var message = {
        content: '',
        from: userId,
        to: toUser,
        gtin_code: gtin_code,
        timestamp: d
    };

    var RID = id = id.split('_')[0] + '|' + id.split('_')[1];

    var messageRef = firebase.database().ref('chat_data/u2u/' + RID + '/messages');
    var roomRefFrom = firebase.database().ref('rooms-users/' + userId + '/');
    roomRefFrom.child(RID).set(messageFrom);
    var roomRefTo = firebase.database().ref('rooms-users/' + toUser);
    roomRefTo.child(RID).set(messageTo);
    messageRef.push().set(message);

    var realId = id.replace('|', '_');
    $('.'+realId).removeClass('has-message');
    if ($('.' + realId).attr('data-online') == 'false') {
        sendNotificationOffline(userId, toUser, 'CSKH vửa gửi cho bạn một sản phẩm', 'CSKH');
    }

}
$(document).on('keyup', '.enter-message', function (e) {
    var code = (e.keyCode ? e.keyCode : e.which);
    if (code == 13) {
        var content = $(this).val();
        content = content.substring(0, content.length - 1);
        var id = $(this).parent().parent().attr('data-chat');
        id = id.split('_')[0] + '|' + id.split('_')[1];

        if (content != '') {
            window.friendlyChat.saveMessage(id, content);
        }

        $(this).val('');
    }
});

$(document).on('click', '.send-message', function () {
    var enterMessage = $(this).parent().find('.enter-message');
    var id = $(this).parent().parent().attr('data-chat');
    var content = enterMessage.val();
    enterMessage.val('');
    id = id.split('_')[0] + '|' + id.split('_')[1];
    if (content != '') {
        window.friendlyChat.saveMessage(id, content);
    }

});

window.onload = function () {
    window.friendlyChat = new FriendlyChat();
};

$(document).on('change', '.file-input', function () {
    var file = this.files[0];
    var id = $(this).parent().parent().attr('data-chat');
    id = id.split('_')[0] + '|' + id.split('_')[1];

    if (file) {

        var formData = new FormData();
        formData.append('file', file);
        if (confirm('Bạn có chắc muốn upload ảnh này')) {
            $.ajax({
                url: urlUpload,
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': token
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {

                    var name = data.prefix;

                    window.friendlyChat.imageMessage(id, name);
                },
                error: function () {
                    alert('Lỗi, hãy thử lại sau');
                }
            });
        }

    } else {

    }
});

function myScroll(element) {
    var scroll = $(element).scrollTop();
    // catch event when scroll TOP ,load more message from firebase
    if (scroll == 0) {

        var parent = $(element).parent();
        var id = parent.attr('data-chat');
        var new_id = id.split('_')[0] + '|' + id.split('_')[1];

        var timestamp = $(element).find('.timestamp:first');

        var time = parseInt(timestamp.text()) - 1;

        var child = firebase.database().ref('chat_data/u2u/' + new_id + '/messages').orderByChild('timestamp').endAt(time).limitToLast(15);
        child.once('value').then(function (snapshot) {

            var childDiv = '';
            snapshot.forEach(function (childSnapshot) {
                var m = childSnapshot.val();

                var userId = firebase.auth().currentUser.uid;


                var noidung = '';
                moment.locale('vi');
                noidung = m.content;
                if (m.image) {
                    noidung = '<img data-image="' + m.image + '" class="img-chat" src="http://ucontent.icheck.vn/' + m.image + '_small.jpg">';

                }
                if (m.gtin_code) {
                    noidung += '<div class="template_product span-' + m.gtin_code + '">Gtin_code:' + m.gtin_code + '</div>';
                    getInfoProduct(m.gtin_code);
                }
                var templateTo = '<div class="bubble me"><p>' + noidung + '</p> </div>' +
                    '<p class="timestamp">' + m.timestamp + '</p>' +
                    '<p class="time-chat">' + moment.unix(m.timestamp / 1000).fromNow() + '</p>';

                var templateFrom = '<div class="bubble you"><p>' + noidung + '</p> </div>' +
                    '<p class="timestamp">' + m.timestamp + '</p>' +
                    '<p class="time-chat">' + moment.unix(m.timestamp / 1000).fromNow() + '</p>';

                if (userId == m.from) {
                    childDiv = childDiv + templateFrom;

                } else {
                    childDiv = childDiv + templateTo;

                }

            });
            if (childDiv != '') {
                $('.chat' + id + ' .conversation-start').prepend(childDiv);
                $('.chat[data-chat = ' + id + '] .conversation-start').animate({scrollTop: $('.chat[data-chat = ' + id + '] .conversation-start').prop("scrollHeight") / 5}, 10);
            }
            child.off();
        });
    }

}
$('.people').on('scroll',function(){

    var scrollTop = $(this).scrollTop();
    //scroll bottom
    var li_last = $(this).children('li').last();
    var id_chat = li_last.attr('data-chat');
    var time_stamp =li_last.attr('data-timestamp');

    if (scrollTop + $(this).innerHeight() >= this.scrollHeight && scrolling == 1 && parseInt(oldTimeStamp) != parseInt(time_stamp) ) {
        scrolling = 0;
        var userId = firebase.auth().currentUser.uid;
        var db = firebase.database().ref('rooms-users/' + userId);
        var con = db.orderByChild('timestamp').endAt(parseInt(time_stamp) - 1).limitToLast(10);
        oldTimeStamp = parseInt(time_stamp);
        con.on('value',function(snap){

            snap.forEach(function(childSnapshot) {
                var childKey = childSnapshot.key;
                var childData = childSnapshot.val();
                var id = childKey.split('|')[0] + '_' + childKey.split('|')[1];
                var o = childData;
                var name = '';
                var idTo = '';
                if (o.from == userId) {
                    idTo = o.to;
                } else {

                    idTo = o.from;
                }

                if ($("." + id).length > 0) {

                } else {
                    var social_id = firebase.database().ref('users/' + idTo).once('value').then(function (snapshot) {
                        if (snapshot.val()) {
                            var url = 'https://graph.facebook.com/' + snapshot.val().social_id + '/picture?width=40&height=40';
                            var name = snapshot.val().name;
                        } else {
                            var url = 'http://s3.postimg.org/yf86x7z1r/img2.jpg';
                            var name = idTo;
                        }

                        var people = '<li class="person ' + id + '" data-timestamp="'+ o.timestamp +'" data-chat="' + id + '" ><img src="' + url + '" alt="" /> <span class="name">' + name + '</span> <span class="preview">' + o.content + '</span> </li>';
                        $('.people').append(people);
                        if (o.isRead == false) {
                            $('.' + id).append('<span class="unread">1</span>');
                            $('.'+id).addClass('has-message');
                        }
                    });
                }
                if (o.isRead == false) {
                    $('.'+id).addClass('has-message');
                    $('.' + id).append('<span class="unread">1</span>');

                }
                // firebase.database().ref('users/' + idTo).on('child_changed', function (snapshot) {
                //
                //     if (snapshot.key == 'isOnline') {
                //         $('.' + id).attr('data-online', snapshot.val());
                //     }
                // });
                icheckio.ref("/users/"+idTo+"/popup").on('updated', function(data){
                    $('.' + id).attr('data-online', data.is_online);
                });


                if ($('.chat' + id).length > 0) {

                } else {
                    var chat = '<div class="chat chat' + id + '" data-chat="' + id + '">' +
                        '<div class="conversation-start" onscroll="myScroll(this)"></div>' +
                        '<div class="write">' +
                        '<textarea name="enter-message" class="enter-message"  placeholder="Enter your message..."></textarea> ' +
                        ' <button type="button" class="btn bg-teal-400 btn-labeled btn-labeled-right send-message"><b><i class="icon-circle-right2"></i></b> Send</button>' +
                        '</div>' +

                        '<div class="upload-image">' +
                        '' +
                        '<label for="file-input-' + id + '">' +
                        '<i class="fa fa-picture-o"></i>' +
                        '</label>' +
                        '<input type="file" class="file-input" id="file-input-' + id + '" name="upload-image-input" >' +
                        '' +
                        '</div>' +

                        '<div class="upload-gtin">' +
                        '' +
                        '<label class="label-gtin">' +
                        '<i class="fa fa-barcode" aria-hidden="true"></i>' +
                        '</label>' +
                        '<input type="text" placeholder="nhập gtin_code " class="input-gtin" data-id="' + id + '" id="gtin-input-' + id + '" name="upload-gtin-input" >' +
                        '' +
                        '</div>' +

                        '</div>';

                    $('.right').append(chat);
                    initGtin('gtin-input-' + id);
                }
                //append message
                var childScroll = firebase.database().ref('chat_data/u2u/' +childKey + '/messages').orderByChild('timestamp').limitToLast(10);
                childScroll.on('child_added', function (s) {
                    var noidung = '';
                    moment.locale('vi');
                    var m = s.val();
                    if(m.content){
                        noidung = convertSpace(m.content);
                    }
                    var notify_content = noidung;
                    $("." + id ).find('.preview').html(noidung);
                    noidung = convert(noidung);
                    if (m.image) {
                        noidung += '<img data-image="' + m.image + '" class="img-chat" src="http://ucontent.icheck.vn/' + m.image + '_small.jpg">';
                        notify_content = 'đã gửi bạn một ảnh';
                    }
                    if (m.gtin_code) {
                        noidung += '<div class="template_product span-' + m.gtin_code + '">Gtin_code:' + m.gtin_code + '</div>';
                        getInfoProduct(m.gtin_code);
                        notify_content = 'đã gửi bạn một sản phẩm';
                    }
                    // var active_chat = $('.active-chat').attr('data-chat');
                    // if(active_chat != undefined ){
                    //     console.log(active_chat);
                    //     var name = $("." + id).find('.name').text();
                    //     if(active_chat != id){
                    //         $('.'+id).addClass('has-message');
                    //         $('.' + id).append('<span class="unread">1</span>');
                    //         var clone = $('.'+id).clone();
                    //         $('.'+id).remove();
                    //         $('.people').prepend(clone);
                    //     }
                    // }

                    var templateTo = '<div class="bubble me"><p>' + noidung + '</p> </div>' +
                        '<p class="timestamp">' + m.timestamp + '</p>' +
                        '<p class="time-chat">' + moment.unix(m.timestamp / 1000).fromNow() + '</p>';

                    var templateFrom = '<div class="bubble you"><p>' + noidung + '</p> </div>' +
                        '<p class="timestamp">' + m.timestamp + '</p>' +
                        '<p class="time-chat">' + moment.unix(m.timestamp / 1000).fromNow() + '</p>';
                    if (userId == m.from) {
                        $('.chat' + id + ' .conversation-start').append(templateFrom);
                    } else {
                        $('.chat' + id + ' .conversation-start').append(templateTo);

                    }
                    $('.chat[data-chat = ' + id + '] .conversation-start').animate({scrollTop: $('.chat[data-chat = ' + id + '] .conversation-start').prop("scrollHeight")}, 10);
                    $('.img-chat').load(function () {
                        $('.chat[data-chat = ' + id + '] .conversation-start').animate({scrollTop: $('.chat[data-chat = ' + id + '] .conversation-start').prop("scrollHeight")}, 10);
                    });
                });

            });
            scrolling = 1;
            con.off();
        });

    }

});

window.onbeforeunload = function (e) {
    window.friendlyChat.signOut();
};

function sendNotificationOffline(fromUser, toUser, content, name) {

    $.ajax({
        url: urlSendNotification,
        type: 'POST',
        data: {
            toUser: toUser,
            content: content,
            name: name,
            fromUser: fromUser
        },
        headers: {
            'X-CSRF-TOKEN': token
        },
        success: function (data) {
        },
        error: function () {
            alert('Lỗi, khi gửi notification đến user đang offline');
        }
    });
}


function initGtin(id) {

    $("#" + id).autocomplete({
        source: function (request, response) {
            $.ajax({
                url: urlSearchGtin,
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function (data) {
                    response(data);
                },
                error: function (error) {
                    console.log(error);
                    alert('Đang xảy ra lỗi !! Vui lòng thử lại sau.');
                }
            });
        },
        select: function (event, ui) {
            var image = ui.item.image_default;
            var product_name = ui.item.product_name;
            var price = ui.item.price_default;
            var gtin_code = ui.item.gtin_code;
            if (confirm('Bạn có muốn gửi sản phẩm này: ' + gtin_code)) {
                gtinMessage(id, gtin_code);
            }


        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {

        if (item.image_default.indexOf('http') >= 0) {
            var image = item.image_default;
        } else {
            var image = 'http://ucontent.icheck.vn/' + item.image_default + '_thumb_small.jpg';
        }
        var template = '<img class="gtin-image-search" src="' + image + '">' + '' +
            '<span class="gtin-name-search">' + item.product_name + '</span>' +
            '<span class="gtin-code-search">' + item.gtin_code + '</span>' +
            '<span class="gtin-price">' + item.price_default.toLocaleString() + ' </span>' +
            '' +
            '';
        return $('<li>').append(template).appendTo(ul);
    };
}
function getInfoProduct(gtin_code) {
    $.ajax({
        url: urlSearchGtin,
        dataType: "json",
        data: {
            term: gtin_code
        },
        success: function (data) {

            var string = templateInfoProduct(data[0]);
            if(string){
                $('.span-' + gtin_code).html(string);
            }


        },
        error: function (error) {
            alert('Đang xảy ra lỗi  trong quá trình lấy thông tin sản phẩm.');
        }
    });
}
function convertSpace(value){
    var str = value.replace(/ /g, "&nbsp;");
    return str
}

function templateInfoProduct(product) {
    var template = '';
        if(product){
            if(product.image_default){
                if (product.image_default.indexOf('http') >= 0) {
                    var image = product.image_default;
                } else {
                    var image = 'http://ucontent.icheck.vn/' + product.image_default + '_thumb_small.jpg';
                }
            }

            var template = '<img class="gtin-image-search" src="' + image + '">' + '' +
                '<span class="gtin-name-search">' + product.product_name + '</span>' +
                '<span class="gtin-code-search">' + product.gtin_code + '</span>' +
                '<span class="gtin-price">' + product.price_default.toLocaleString() + ' </span>' +
                '' +
                '';
        }



    return template;
}
function convert(value)
{
    var text= value;
    var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
    var text1=text.replace(exp, "<a href='$1' target='_blank'>$1</a>");
    var exp2 =/(^|[^\/])(www\.[\S]+(\b|$))/gim;
   return text1.replace(exp2, '$1<a target="_blank" href="http://$2">$2</a>');
}