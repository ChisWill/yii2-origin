var sendList = {};
var closeSocket;
$(function () {
    var mainFrame = parent.window,
        io = $.io(document.getElementById('serverUrl').value),
        isActiveClose = false,
        notify = new iNotify({
            notification: {
                icon: '/favicon.ico',
                title: '',
                body: ''
            }
        });
    // 判断当前页面是否被激活
    var isPageActive = true;
    document.addEventListener('visibilitychange', function () {
        if (!document['hidden']) {
            isPageActive = true;
        } else {
            isPageActive = false;
        }
    });
    // 发送消息
    Object.defineProperties(sendList, {
        // 单聊
        msg: {
            set: function (value) {
                io.emit('msg', value);
            }
        },
        // 图片
        picture: {
            set: function (value) {
                io.emit('picture', value);
            },
        },
        // 文件
        file: {
            set: function (value) {
                io.emit('file', value);
            },
        }
    });

    io.on("connect", function () {
        // 登录
        io.emit('login', mainFrame.$.getUserId());
        // 接受消息
        io.on('response', function (msg) {
            // 保存用户信息
            if (msg.type != 'system') {
                var id = msg.value.from,
                    name = msg.value.name,
                    face = msg.value.face,
                    data = {id: id, name: name, face: face};
                mainFrame.$.saveUserInfo(id, data);
                if (notify.isPermission() && isPageActive === false) {
                    notify.notify({
                        title: "有新消息",
                        body: mainFrame.$.getUserInfo(msg.value.from, 'name') + '的新消息',
                        openurl: ''
                    });
                }
            }
            mainFrame.receiveList[msg.type] = msg.value;
        });
    });
    io.on('disconnect', function () {
        if (isActiveClose === true) {
            console.log('disconnect');
        } else {
            // 断线重连
            console.log('reconnect');
            setTimeout(function () {
                window.location.reload();
            }, 1000);
        }
    });
    closeSocket = function () {
        tes('logout');
        isActiveClose = true;
        io.close();
    };
});