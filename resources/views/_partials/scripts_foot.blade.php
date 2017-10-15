<!-- Core JS files -->
<script type="text/javascript" src="{{ asset('assets/js/plugins/loaders/pace.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/core/libraries/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/core/libraries/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/loaders/blockui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/ui/nicescroll.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/ui/drilldown.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
<!-- /core JS files -->

<!-- Theme JS files -->
@stack('js_files_foot')

<script type="text/javascript" src="{{ asset('assets/js/core/app.js') }}"></script>

<script type="text/javascript" src="{{ asset('assets/js/plugins/ui/ripple.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        (function () {
            $('<audio id="notificationSound"><source src="{{ asset('assets/sounds/new-notification.ogg') }}" type="audio/ogg"><source src="{{ asset('assets/sounds/new-notification.mp3') }}" type="audio/mpeg"><source src="{{ asset('assets/sounds/new-notification.wav') }}" type="audio/wav"></audio>').appendTo('body');

            function loadNotifications(since) {
                var $notifications = $('#notifications');
                var maxItemShow = 10;

                $.ajax({
                    url: '{{ route('Business::notification@index') }}',
                    data: since ? { since: since } : { limit: maxItemShow }
                }).then(function (response) {
                    var data = response.data.reverse();
                    var unreadCount = response.metadata.unreadCount;

                    $('#notifications-unread-count').text((unreadCount > 0 ? unreadCount : ''));

                    $notifications.find('.js-notification-time').each(function (child) {
                        var $this = $(this),
                                time = moment($this.data('time'));

                        $this.text(time.fromNow());
                    });

                    if (data.length) {
                        if (since) {
                            $('#notificationSound')[0].play();
                        }

                        data.forEach(function (notification) {
                            var $template = $('#notifications-item-template'),
                                    $item = $($template.html());

                            $item.find('.js-notification-link').attr('href', notification.link);

                            if (notification.unread) {
                                $item.find('.js-notification-link').addClass('border-left-xlg border-left-green');
                            }

                            $item.find('.js-notification-content').html(notification.content);
                            var time = moment(notification.created_at);

                            $item.find('.js-notification-time')
                                    .data('time', time.toISOString())
                                    .attr('title', time.toISOString())
                                    .text(time.fromNow());

                            $item.prependTo($notifications);

                            since = time.utc().unix() + 1;
                        });

                        var total = $notifications.children().length;

                        if (total > maxItemShow) {
                            $notifications.children(':nth-last-child(-n+' + (total - maxItemShow) + ')').remove();
                        }
                    }

                    setTimeout(function () {
                        loadNotifications(since);
                    }, 1000 * 15);
                });
            }

            loadNotifications();
        })();

        $('.js-help-icon').popover({
            container: "body",
            html: true,
            trigger: "hover",
            delay: { "hide": 1000 }
        });
    });

</script>
@stack('scripts_foot')
<!-- /theme JS files -->
