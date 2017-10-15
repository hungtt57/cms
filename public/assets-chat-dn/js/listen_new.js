'use strict';

// Initializes FriendlyChat.
function FriendlyChat() {

    this.initFirebase();

}


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
    this.auth.onAuthStateChanged(this.onAuthStateChanged.bind(this));
};

// Signs-in Friendly Chat.
FriendlyChat.prototype.signIn = function () {

    $.ajax({
        url : urlgetTokenFireBase,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token_listen_new
        },
        success:function(data){
            firebase.auth().signInWithCustomToken(data).then(function (data) {
            }).catch(function (error) {
                console.log(errorMessage);
            });

        }
    });

};

// Signs-out of Friendly Chat.
FriendlyChat.prototype.signOut = function () {
    var currentUser = this.auth.currentUser;
    var uid = currentUser.uid;
    var d = firebase.database.ServerValue.TIMESTAMP;
    this.auth.signOut();
};
// (new PNotify({
//     title: 'Thông báo',
//     text: 'Có tin nhắn mới từ : ' + message.name,
//     delay:500,
//     desktop: {
//         desktop: true,
//         icon: 'includes/le_happy_face_by_luchocas-32.png'
//     }
// })).get().click(function(e) {
//     window.location.href = urlChat;
// });
// Triggers when the auth state change for instance when the user signs-in or signs-out.
FriendlyChat.prototype.onAuthStateChanged = function (user) {
    if (user) { // User is signed in!

        // Get uid and email
        var userId = user.uid;
        var database = firebase.database().ref('rooms-users/' + userId);
        var conversions = database.orderByChild('timestamp');

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

window.onload = function () {

    PNotify.desktop.permission();
    window.friendlyChat = new FriendlyChat();
};


window.onbeforeunload = function (e) {
    window.friendlyChat.signOut();
};
