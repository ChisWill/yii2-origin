$(function() {
    var io = $.io(),
        notify = new iNotify({
            audio: {
                file: '/media/msg.mp4'
            },
            notification: {
                title: '',
                icon: '/favicon.ico',
                body: ''
            }
        });

    ;!function () {
        io.on("connect", function () {
            io.emit('reg', $("#userId").val());

            io.on('notify', function (msg) {
                notify.player().notify({
                    title: "咦~ 短消息...",
                    body: msg.info,
                    openurl: msg.url
                });
            });
        });

        io.on('disconnect', function () {
            setTimeout(function () {
                window.location.reload();
            }, 500);
        });
    }();

    $.extend({
        notify: function (info, url) {
            io.emit('notify', {
                info: info,
                url: url
            });
        }
    });
});