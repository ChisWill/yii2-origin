var receiveList = {};
;!(function() {
    var $chatBox = $("#chatBox"),
        $serviceList = $("#serviceList"),
        $chatMain = $chatBox.find('.chat-main'),
        $chatList = $chatBox.find(".chat-list"),
        $memberItem = $serviceList.find(".memberList li"),
        $slideService = $("#slideService");

    Object.defineProperties(receiveList, {
        system: {
            set: function (value) {
                switch (value['event']) {
                    case 'offline':
                        $.addSystemMsg(value['from'], value['content'], {isSave: true, time: value['time']});
                        break;
                    case 'kick':
                        $.getServerFrame().closeSocket();
                        $.alert(value['content'], function () {
                            $.logout();
                        });
                        break;
                }
            }
        },
        msg: {
            set: function (value) {
                $.addLeftMsg(value['from'], value['content'], {isSave: true, time: value['time']});
            }
        },
        picture: {
            set: function (value) {
                $.addLeftPicture(value['from'], value['content'], {isSave: true, time: value['time']});
            }
        },
        file: {
            set: function (value) {
                $.addLeftFile(value['from'], value['content'], {isSave: true, time: value['time'], originName: value['originName'], size: value['size']});
            }
        }
    });

    /**
     * 获取当前时间
     */
    Date.prototype.format = function (format) {
        var options = {
            "M+": this.getMonth() + 1, //month 
            "d+": this.getDate(), //day 
            "h+": this.getHours(), //hour 
            "m+": this.getMinutes(), //minute 
            "s+": this.getSeconds(), //second 
            "q+": Math.floor((this.getMonth() + 3) / 3), //quarter 
            "S": this.getMilliseconds() //millisecond 
        };

        if (/(y+)/.test(format)) {
            format = format.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
        }

        for (var k in options) {
            if (new RegExp("(" + k + ")").test(format)) {
                format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? options[k] : ("00" + options[k]).substr(("" + options[k]).length));
            }
        }

        return format;
    }

    $.extend({
        getServerFrame: function () {
            return window.frames['server'];
        },
        sendMsg: function (value) {
            $.getServerFrame().sendList['msg'] = {
                to: $.getToId(),
                name: $.getFromName(),
                face: $.getFromFace(),
                isGuest: $.isGuest(),
                content: value
            };
        },
        sendPicture: function (value) {
            $.getServerFrame().sendList['picture'] = {
                to: $.getToId(),
                name: $.getFromName(),
                face: $.getFromFace(),
                isGuest: $.isGuest(),
                content: value
            };
        },
        sendFile: function (value, name, size) {
            $.getServerFrame().sendList['file'] = {
                to: $.getToId(),
                name: $.getFromName(),
                face: $.getFromFace(),
                isGuest: $.isGuest(),
                content: value,
                originName: name,
                size: size
            };
        },
        logout: function () {
            if (!$.isGuest()) {
                $.setCache('chatUserId', '');
            }
            top.window.location.href = '/site/logout';
        },
        isGuest: function (userId) {
            if (userId) {
                return isNaN(userId);
            } else {
                return parseInt($serviceList.find('.isGuest').val()) == 1; // 游客为1，客服为0
            }
        },
        getUserId: function () {
            var userId = $.getCache('chatUserId', null);
            if (!userId) {
                if ($.isGuest()) {
                    userId = $.getRandomString();
                } else {
                    userId = $serviceList.find('.userId').val();
                }
                $.setCache('chatUserId', userId);
            }
            return userId;
        },
        getRandomString: function (length) {
            length = length || 16;
            var chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnprstuvwxyz0123456789',
                maxPos = chars.length,
                result = '';
            for (i = 0; i < length; i++) {
            　　result += chars.charAt(Math.floor(Math.random() * maxPos));
            }
            return result;
        },
        getFromId: function () {
            return $.getUserId();
        },
        getFromName: function () {
            if ($.isGuest()) {
                return $.getCache('chatUserName', []);
            } else {
                return $serviceList.find('.fromName').val();
            }
        },
        getFromFace: function () {
            if ($.isGuest()) {
                return $.getGuestFace($.getFromName());
            } else {
                return $serviceList.find(".fromFace").val();
            }
        },
        getToId: function () {
            return $chatBox.find('.targetUserId').val();
        },
        hasChatBox: function () {
            return !$chatBox.hasClass('hide');
        },
        isInList: function (from) {
            if ($serviceList.find(".memberList li[data-id=" + from + "]").length > 0) {
                return true;
            } else {
                return false;
            }
        },
        convertByte: function (size) {
            if (size < 1024) {
                return size + 'B';
            } else if (size >= 1024 && size < 1024 * 1024) {
                return (size / 1024).toFixed(2) + 'KB';
            } else {
                return (size / 1024 / 1024).toFixed(2) + 'MB';
            }
        },
        addList: function (from, content, time) {
            var time = $.nowTime(time);
            var memberItem = 
                '<li data-id="' + from + '">' +
                    '<img class="headicon userFace" src="' + $.getUserInfo(from, 'face') + '">' +
                    '<span class="username userName">' + $.getUserInfo(from, 'name') + '</span>' +
                    '<span class="msg-time lastTime">' + time.slice(11, 19) + '</span>' +
                    '<div class="msgText">' + content + '</div>' +
                    '<span class="message-num msgNum hide">' +
                        '<i>0</i>' +
                    '</span>' +
                '</li>';
            $serviceList.find(".memberList").append(memberItem);
            $.updateListCount();
        },
        updateListCount: function () {
            $serviceList.find(".service-count").html($serviceList.find(".memberList li").length);
        },
        updateList: function (from, content, time) {
            $serviceList.find('.memberList li[data-id=' + from + '] .msgText').html(content);
            $serviceList.find('.memberList li[data-id=' + from + '] .lastTime').html($.nowTime(time));
            $serviceList.find(".memberList li").each(function () {
                var userId = $(this).data('id');
                var length = $.getMsg(userId, true).length;
                if (length > 0) {
                    $(this).find('.message-num').removeClass('hide');
                    $(this).find('i').html(length);
                }
                var lastMsg = $.getLastMsg(userId);
                if (lastMsg !== false && lastMsg['time'] !== undefined) {
                    $.showLastMsg(userId, lastMsg['content'], lastMsg['type'], lastMsg['time']);
                }
            });
        },
        getCache: function (key, defaultValue) {
            var value = window.localStorage.getItem(key);
            if (value) {
                return JSON.parse(value);
            } else if (typeof defaultValue !== 'undefined') {
                return defaultValue;
            } else {
                return {};
            }
        },
        setCache: function (key, value) {
            window.localStorage.setItem(key, JSON.stringify(value));
        },
        clearCache: function () {
            window.localStorage.clear();
        },
        saveMsg: function (key, userId, content, type, isRead, extra) {
            isRead = isRead || false;
            extra = extra || {};
            if (extra.time) {
                var time = extra.time;
                delete extra.time;
            } else {
                var time = parseInt((new Date).getTime() / 1000);
            }
            var history = $.getCache(key, []);
            var data = {
                userId: userId,
                content: content,
                type: type,
                isRead: isRead,
                time: time
            };
            if (!$.isEmptyObject(extra)) {
                data['extra'] = extra;
            }
            if (history.length >= 100) {
                history.shift();
            }
            history.push(data);
            $.setCache(key, history);
        },
        getLastMsg: function (key) {
            var list = $.getCache(key, []);
            do {
                var last = list.pop() || {};
            } while (last['type'] == 'system');
            if (last) {
                return last;
            } else {
                return false;
            }
        },
        getMsg: function (userId, isNew) {
            var history = $.getCache(userId, []);
            if (isNew === true) {
                var list = [];
                for (var k in history) {
                    if (history[k]['isRead'] == false) {
                        list.push(history[k]);
                    }
                }
                return list;
            } else {
                return history;
            }
        },
        _initUser: function () {
            if (typeof $.getServerFrame().userList === 'undefined') {
                $.getServerFrame().userList = $.getCache('userList');
            }
        },
        saveUserInfo: function (id, data) {
            if ($.isUserExist(id)) {
                $.updateUserInfo(id, data);
            } else {
                $.setUserList([data]);
            }
        },
        updateUserInfo: function (userId, info) {
            $._initUser();
            $.extend($.getServerFrame().userList[userId], info);
            $.setCache('userList', $.getServerFrame().userList);
        },
        getUserList: function () {
            return $.getCache('userList', {});
        },
        setUserList: function (list) {
            $._initUser();
            list.forEach(function (v) {
                $.getServerFrame().userList[v['id']] = v;
            });
            $.setCache('userList', $.getServerFrame().userList);
        },
        isUserExist: function (userId) {
            $._initUser();
            return $.getServerFrame().userList.hasOwnProperty(userId);
        },
        getGuestName: function (face) {
            return getName();
        },
        getGuestFace: function (name) {
            return new mdAvatar({
                size: 50,
                text: name
            }).toDataURL();
        },
        getUserInfo: function (userId, field) {
            $._initUser();
            if ($.getServerFrame().userList.hasOwnProperty(userId)) {
                if (field == 'face' && $.isGuest(userId)) {
                    return $.getGuestFace($.getUserInfo(userId, 'name'));
                }
                return $.getServerFrame().userList[userId][field];
            } else {
                switch (field) {
                    case 'face':
                        return '/images/default-head.png';
                    case 'nickname':
                        return '无名氏';
                }
            }
        },
        showLastMsg: function (from, content, type, time) {
            var $memberRow = $serviceList.find('li[data-id=' + from + ']'),
                $msgText = $memberRow.find('.msgText'),
                $msgTime = $memberRow.find('.lastTime');
            switch (type) {
                case 'msg':
                   break;
                case 'picture':
                    content = '[图片]';
                    break;
                case 'file':
                    content = '[文件]';
                    break;
                case 'record':
                    content = '[语音]';
                    break;
                case 'video':
                    content = '[视频通话]';
                    break;
            }
            $msgText.html(content);
            $msgTime.html($.nowTime(time).slice(11, 19));
        },
        msgType2str: function (type) {
            switch (type.toString()) {
                case '0':
                    return 'system';
                case '1':
                    return 'msg';
                case '2':
                    return 'picture';
                case '3':
                    return 'file';
                case '4':
                    return 'record';
                case '5':
                    return 'video';
            }
        },
        notify: function (from, content, type) {
            var $memberRow = $serviceList.find('li[data-id=' + from + ']');
            var $msgRow = $chatList.find('li[data-id=' + from + ']');
            // 客服列表存在，但聊天窗口不存在
            var $memberArea = $memberRow.find('.msgNum>i'),
                $msgArea = $msgRow.find('.msgNum>i');
                num = parseInt($memberArea.html());
            if (num >= 99) {
                num = '99+';
            } else {
                num++;
            }
            $memberArea.html(num).parent().removeClass('hide');
            $msgArea.html(num).parent().removeClass('hide');

            $.updateLastMsg(from, content, $.serverTime());
            $.prependEle(from);
            $.showLastMsg(from, content, type, $.serverTime());
            // 消息列表回到顶部
            $serviceList.find(".memberList").animate({scrollTop: 0}, 200);
            $.msgShake();
        },
        msgShake: function () {
            if ($slideService.is(':visible') && !$slideService.hasClass('msg-shake')) {
                $slideService.addClass('msg-shake');
                setTimeout(function() {
                    $slideService.removeClass('msg-shake');
                }, 400);
            }
            $slideService.find(".total-num").removeClass('hide');
            var totalNum = parseInt($slideService.find(".total-num i").html());
            if (totalNum >= 99) {
                totalNum = '99+';
            } else {
                totalNum++;
            }
            $slideService.find(".total-num i").html(totalNum);
        },
        updateLastMsg: function (from, content, time) {
            var msgKeyList = $.getCache('msgKeyList', {});
            msgKeyList[from] = {
                content: content,
                time: time
            };
            $.setCache('msgKeyList', msgKeyList);
        },
        prependEle: function (from) {
            if (!$.isGuest()) {
                $serviceList.find('.memberList').prepend($serviceList.find('.memberList li[data-id=' + from + ']'));
            }
        },
        scrollBottom: function (delay) {
            delay = delay || false;
            //将滚动条始终保持在底部.延迟100毫秒，等图片消息加载到消息容器中
            if ($chatMain.find('ul li').length > 0) {
                var scroll = function () {
                    $chatMain.find("ul li:last")[0].scrollIntoView(); 
                }
                if (delay === true) {
                    setTimeout(scroll, 10);
                } else {
                    scroll();
                }
            }
        },
        appendMsg: function (msgText, delay) {
            $chatBox.find(".chat-main ul").append(msgText);
            $.scrollBottom(delay);
        },
        serverTime: function () {
            var initServerTime = parseInt($chatBox.find(".serverTime").val()),
                initLocalTime = parseInt($chatBox.find(".localTime").val() / 1000),
                diffTime = initLocalTime - initServerTime;

            return parseInt(Date.now() / 1000) + diffTime;
        },
        nowTime: function (time) { // time 单位为秒
            time = time || Date.now() / 1000;
            return new Date(time * 1000).format('yyyy/MM/dd hh:mm:ss');
        },
        clearNotify: function () {
            var $roomItem = $chatList.find(".active");
            var $dataId = $roomItem.data('id') || $serviceList.find(".active").data('id');
            var $memberItem = $serviceList.find("li[data-id=" + $dataId + " ] .msgNum>i");
            var $numArea = $roomItem.find('.msgNum>i');
            var $memArea = $memberItem.find('.msgNum>i');

            if (!$numArea.parent().hasClass('hide') || !$memArea.parent().hasClass('hide')) {
                $numArea.parent().addClass('hide');
                $memberItem.parent().addClass('hide');
            }
            $numArea.html('0');
            $memberItem.html('0');
        },
        addLeftMsg: function (from, content, params) {
            var isSave = params.isSave || false;
            var time = $.nowTime(params.time);

            var saved = false;
            // 判断当前消息的发送人聊天窗口是否存在
            if (from == $.getToId() && $.hasChatBox()) {
                // 通过校验后，确认当前页面对象符合消息接受条件，以下为显示消息
                var text = 
                    '<li>' +
                        '<div class="chat-user">' +
                            '<img src="' + $.getUserInfo(from, 'face') + '">' +
                            '<cite>' +
                                '<span>' + $.getUserInfo(from, 'name') + '</span>' +
                                '<i>' + time + '</i>' +
                            '</cite>' +
                        '</div>' +
                        '<div class="chat-text">' + content + '</div>' +
                    '</li>';
                $.appendMsg(text);
                if (isSave == true) {
                    $.saveMsg(from, from, content, 'msg', true, {time: params.time});
                    saved = true;
                }
            } else {
                $.saveMsg(from, from, content, 'msg', false, {time: params.time});
                saved = true;
                 // 人物列表中有，并且发送人聊天窗口不存在
                if (!$.isInList(from)) {
                    $.addList(from, content);
                }
                $.notify(from, content, 'msg', params.time);
            }
            $.updateLastMsg(from, content, params.time);
            $.showLastMsg(from, content, 'msg', params.time);
        },
        addRightMsg: function (from, content, params) {
            var time = $.nowTime(params.time);
            var text = 
                '<li class="chat-mine">' +
                    '<div class="chat-user">' +
                        '<img src=' + $.getUserInfo(from, 'face') + '>' +
                        '<cite>' +
                            '<i>' + time +'</i>' +
                            '<span>' + $.getUserInfo(from, 'name') + '</span>' +
                        '</cite>' +
                    '</div>' +
                    '<div class="chat-text">' + content + '</div>' +
                '</li>';
            $.appendMsg(text);
            $.updateLastMsg($.getToId(), content, params.time);
            $.showLastMsg($.getToId(), content, 'msg', params.time);
        },
        addLeftPicture: function (from, content, params) {
            var isSave = params.isSave || false;
            var time = $.nowTime(params.time);
            var saved = false;
            if (from == $.getToId() && $.hasChatBox()) {
                var text = 
                    '<li>' +
                        '<div class="chat-user">' +
                            '<img src=' + $.getUserInfo(from, 'face') + '>' +
                            '<cite>' +
                                '<span>' + $.getUserInfo(from, 'name') + '</span>' +
                                '<i>' + time + '</i>' +
                            '</cite>' +
                        '</div>' +
                        '<div class="chat-text"><a class="fancyboxImage" data-fancybox href="' + content + '"><img src= "' + content +'" /></a></div>' +
                    '</li>';
                $.appendMsg(text, true);
                if (isSave == true) {
                    $.saveMsg(from, from, content, 'picture', true, {time: params.time});
                    saved = true;
                }
            } else {
                $.saveMsg(from, from, content, 'picture', false, {time: params.time});
                saved = true;
                if (!$.isInList(from)) {
                    $.addList(from, content);
                }
                $.notify(from, content, 'picture', params.time);
            }
            $.updateLastMsg(from, content, params.time);
            $.showLastMsg(from, content, 'picture', params.time);
        },
        addRightPicture: function (from, content, params) {
            var time = $.nowTime(params.time);
            var $picture = $('<a class="fancyboxImage" data-fancybox href="' + content + '"><img src= "' + content +'" /></a>');
            
            var $container = $(
                '<li class="chat-mine">' +
                    '<div class="chat-user">' +
                        '<img src=' + $.getUserInfo(from, 'face') + '>' +
                        '<cite>' +
                            '<i>' + time +'</i>' +
                            '<span>' + $.getUserInfo(from, 'name') + '</span>' +
                        '</cite>' +
                    '</div>' +
                    '<div class="chat-text"></div>' +
                '</li>');
            $container.find('.chat-text').append($picture);
            $.appendMsg($container, true);
            $.updateLastMsg($.getToId(), content, params.time);
            $.showLastMsg($.getToId(), content, 'picture', params.time);
            return $picture;
        },
        addLeftFile: function (from, content, params) {
            var isSave = params.isSave || false;
            var time = $.nowTime(params.time);
            var saved = false;
            var basePath = $serviceList.find(".basePath").val();
            var extra = {
                originName: params.originName,
                size: params.size
            };

            if (from == $.getToId() && $.hasChatBox()) {
                var href = '/chat/download?file=' + content +'&name=' + params.originName;
                var text = 
                    '<li>' +
                        '<div class="chat-user">' +
                            '<img src=' + $.getUserInfo(from, 'face') + '>' +
                            '<cite>' +
                                '<span>' + $.getUserInfo(from, 'name') + '</span>' +
                                '<i>' + time + '</i>' +
                            '</cite>' +
                        '</div>' +
                        '<a class="chat-text file-message clearfix" href="' + content + '">' +
                            '<div class="fl">' +
                                '<div class="file-name">' + params.originName + '</div>' +
                                '<div class="file-size">'+ $.convertByte(params.size) + '</div>' +
                            '</div>'+
                            '<div class="fl">' +
                                '<img class="file-icon" src="' + basePath +'/images/file-icon.png">' +
                            '</div>' +
                        '</a>' +
                    '</li>';
                $.appendMsg(text, true);
                if (isSave == true) {
                    $.saveMsg(from, from, content, 'file', true, extra, {time: params.time});
                    saved = true;
                }
            } else {
                $.saveMsg(from, from, content, 'file', false, extra, {time: params.time});
                saved = true;
                if (!$.isInList(from)) {
                    $.addList(from, content);
                }
                $.notify(from, content, 'file', params.time);
            }
            $.updateLastMsg(from, content, params.time);
            $.showLastMsg(from, content, 'file', params.time);
        },
        addRightFile: function (from, content, params) {
            var time = $.nowTime(params.time);
            var basePath = $serviceList.find(".basePath").val();

            var text = 
                '<li class="chat-mine">' +
                    '<div class="chat-user">' +
                        '<img src="' + $.getUserInfo(from, 'face') + '">' +
                        '<cite>' +
                            '<i>' + time + '</i>' +
                            '<span>' + $.getUserInfo(from, 'name') + '</span>' +
                        '</cite>' +
                    '</div>' +
                    '<a class="chat-text file-message clearfix" href="' + content + '">' +
                        '<div class="fl">' +
                            '<div class="file-name">' + params.originName + '</div>' +
                            '<div class="file-size">'+ $.convertByte(params.size) + '</div>' +
                        '</div>' +
                        '<div class="fl">' +
                            '<img class="file-icon" src="' + basePath +'/images/file-icon.png">' +
                        '</div>' +
                    '</a>' +
                '</li>';
            $.appendMsg(text, true);
            $.updateLastMsg($.getToId(), content, params.time);
            $.showLastMsg($.getToId(), content, 'file', params.time);
        },
        addSystemMsg: function (from, content, params) {
            params = params || {};
            var isSave = params.isSave || false;
            var time = $.nowTime(params.time);
            var text = 
                '<div class="system-tip">' +
                    '<div>' + time + '</div>' +
                    '<div>' + content + '</div>' +
                '</div>';
            $.appendMsg(text);
            if (isSave == true) {
                $.saveMsg(from, 0, content, 'system', true, {time: params.time});
            }
        }
    });
})();
