$(function () {
    var $chatBox = $("#chatBox");
    var $serviceList = $("#serviceList");
    var $chatMain = $chatBox.find('.chat-main');
    var $chatList = $chatBox.find(".chat-list");
    var $chatContainer = $chatBox.find(".chatContainer");
    var $chatTextArea = $chatBox.find("#chatTextArea");
    // 从缓存中读取聊天数据
    var initHistory = function () {
        var fromId = $.getToId();
        $chatMain.find("ul").html("");
        $.getCache($.getToId(), []).forEach(function (v) {
            if (v['userId'] == 0) {
                $.addSystemMsg(0, v['content'], {time: v['time']});
            } else if (v['userId'] !== fromId) {
                switch (v['type']) {
                    case 'msg':
                        $.addRightMsg(v['userId'], v['content'], {time: v['time']});
                        break;
                    case 'picture':
                        $.addRightPicture(v['userId'], v['content'], {time: v['time']});
                        break;
                    case 'file':
                        $.addRightFile(v['userId'], v['content'], {time: v['time'], originName: v['extra']['originName'], size: v['extra']['size']});
                        break;
                }
            } else {
                switch (v['type']) {
                    case 'msg':
                        $.addLeftMsg(fromId, v['content'], {isSave: false, time: v['time']});
                        break;
                    case 'picture':
                        $.addLeftPicture(fromId, v['content'], {isSave: false, time: v['time']});
                        break;
                    case 'file':
                        $.addLeftFile(fromId, v['content'], {isSave: false, time: v['time'], originName: v['extra']['originName'], size: v['extra']['size']});
                        break;
                }
            }
        });
        $.scrollBottom();
    };

    // 初始化人物列表
    var initMsgList = function () {
        var msgKeyList = $.getCache("msgKeyList", {}),
            msgList = [];
        for (var id in msgKeyList) {
            msgList.push({
                id: id,
                content: msgKeyList[id]['content'],
                time: msgKeyList[id]['time']
            });
        }
        msgList.sort(function (a, b) {
            return b['time'] - a['time'];
        });
        if (!$.isGuest()) {
            for (var key in msgList) {
                $.addList(msgList[key].id, msgList[key].content, msgList[key].time);
            }
        }
        if (msgKeyList[id]) {
            $.updateList(id, msgKeyList[id]['content'], msgKeyList[id]['time']);
        }
        // 计算消息列表数量
        $.updateListCount();
    };

    // 初始化总消息个数
    var initTotalMsg = function () {
        var totalNum = 0;
        $serviceList.find(".memberList li").each(function () {
            totalNum += Number($(this).find(".msgNum i").html());
        });
        if (totalNum > 0) {
            $("#slideService").find(".total-num").removeClass('hide');
            $("#slideService").find(".total-num i").html(totalNum);
        } else {
            $("#slideService").find(".total-num").addClass('hide');
            $("#slideService").find(".total-num i").html("0");
        }
    };
    // 图片预览
    $('.fancyboxImage').fancybox({
        padding: 5,
        opacity: true,
        closeEffect: 2,
        autoScale: true,
        closeClick: true
    });

    //自动居中对话框
    var autoCenter = function (el) {
        var bodyW = $(window).width();
        var bodyH = $(window).height();
        var elW = el.width();
        var elH = el.height();
        $chatBox.css({"left": (bodyW - elW) / 2 + 'px', "top": (bodyH - elH) / 2 + 'px', 'marginTop': '0', 'marginLeft': '0'});        
    };
    
    //声明需要用到的变量
    var mx = 0, my = 0;      //鼠标x、y轴坐标（相对于left，top）
    var dx = 0, dy = 0;      //对话框坐标（同上）
    var isDraging = false;      //不可拖动

    //鼠标按下
    $chatBox.find(".moveBox").mousedown(function (e) {
        e = e || window.event;
        mx = e.pageX;     //点击时鼠标X坐标
        my = e.pageY;     //点击时鼠标Y坐标
        dx = $chatBox.offset().left;
        dy = $chatBox.offset().top;
        isDraging = true;      //标记对话框可拖动                
    });

    //鼠标移动更新窗口位置
    $(document).mousemove(function (e) {
        var e = e || window.event;
        var x = e.pageX;      //移动时鼠标X坐标
        var y = e.pageY;      //移动时鼠标Y坐标
        if (isDraging) {        //判断对话框能否拖动
            var moveX = dx + x - mx;      //移动后对话框新的left值
            var moveY = dy + y - my;      //移动后对话框新的top值
            //设置拖动范围
            var pageW = $(window).width();
            var pageH = $(window).height();
            var dialogW = $chatBox.width();
            var dialogH = $chatBox.height();
            var maxX = pageW - dialogW;       //X轴可拖动最大值
            var maxY = pageH - dialogH;       //Y轴可拖动最大值
            moveX = Math.min(Math.max(0, moveX), maxX);     //X轴可拖动范围
            moveY = Math.min(Math.max(0, moveY), maxY);     //Y轴可拖动范围
            //重新设置对话框的left、top
            $chatBox.css({"left": moveX + 'px', "top": moveY + 'px', 'marginTop': '0', 'marginLeft': '0'});
        };
    });

    //鼠标离开
    $chatBox.find(".moveBox").mouseup(function () {
        isDraging = false;
    });

    // 客服列表点击
    var memberArr = [];
    $serviceList.on('click', '.memberList li', function () {
        $(this).addClass('active').siblings().removeClass('active');
        $chatBox.removeClass('hide');
        //将滚动条始终保持在底部
        $chatMain.scrollTop($chatMain[0].scrollHeight);
        var username = $(this).find(".username").html();
        var headicon = $(this).find(".headicon").attr('src');
        var dataId = $(this).data('id');
        $chatBox.find('.targetUserId').val(dataId);
        $chatBox.find(".chat-headicon").attr('src', headicon);
        $chatBox.find(".chat-username").html(username);
        $chatBox.find(".chat-list li[data-id= " + dataId + "]").trigger('click');

        for (var key in memberArr) {
            if (dataId == memberArr[key].dataId) {
                return false; 
            }
        }
        memberArr.push({'dataId': dataId, 'username' : username, 'headicon': headicon});
        if (memberArr.length > 1) {
            $chatList.html("");
            $chatBox.addClass('chat-right');
            $chatContainer.css("marginLeft", '200px');
            $chatList.show();
            for (var key in memberArr) {
                var listText = '<li data-id="' + memberArr[key].dataId +'">' +
                                    '<img src="' + memberArr[key].headicon +'">' +
                                    '<span class="username">' + memberArr[key].username + '</span>' +
                                    '<i class="chat-icon deleteIcon">ဇ</i>' +
                                    '<span class="message-num msgNum hide">' +
                                        '<i>1</i>' +
                                    '</span>' +
                                '</li>';
                $chatList.append(listText);
            }
            $chatBox.find(".chat-list li:last").trigger('click');
        } else {
            $chatBox.removeClass('chat-right');
            $chatContainer.css("marginLeft", '0px');
            $chatList.hide();
        }
        $.clearNotify();
        // 点击修改消息阅读 状态
        var tempArray = $.getCache(dataId, []);
        for (var key in tempArray) {
            tempArray[key].isRead = true;
            $.setCache(dataId, tempArray);
        }
        initHistory();
    });

    // 点击左侧列表
    $chatList.on('click', 'li', function () {
        $(this).addClass('active').siblings().removeClass('active');
        var username = $(this).find("span").html();
        var headicon = $(this).find("img").attr('src');
        var dataId = $(this).data('id');
        $chatBox.find(".chat-headicon").attr('src', headicon);
        $chatBox.find(".chat-username").html(username);
        $chatBox.find('.targetUserId').val(dataId);
        $.clearNotify();
        initHistory();
    });

    // 左侧删除按钮
    $chatList.on('click', '.deleteIcon', function (e) {
        e.stopPropagation();//阻止冒泡到父级
        // 点击删除自动选择兄弟节点
        if ($(this).parent("li").next() > 0) {
            $(this).parent("li").next().trigger("click");
        } else {
            $(this).parent("li").prev().trigger("click");
        }
        $(this).parent().remove();
        var dataId = $(this).parent("li").data('id');

        for (var key in memberArr) {
            if (memberArr[key].dataId == dataId) {
                memberArr.splice(key, 1);
            }
        }
        
        if (memberArr.length < 1) {
            $chatList.hide();
            $chatBox.removeClass('chat-right');
            $chatContainer.css("marginLeft", '0px');
            autoCenter($chatBox);
        }
    });

     //点击关闭对话框
    $chatBox.find(".closeChat").click(function () {
        $chatBox.addClass('hide');
        memberArr = [];
    });

    //窗口大小改变时，对话框始终居中
    $(window).resize(function() {
        autoCenter($chatBox);
    });

    // 关闭客服列表
    $serviceList.find(".closeList").click(function () {
        initTotalMsg();
        $("#serviceList").slideToggle('normal');
        $("#slideService").slideToggle("normal");
    });

    // 最小化后的客服窗口点击事件
    $('#slideService').click(function () {
        $("#serviceList").slideToggle('normal');
        $("#slideService").slideToggle("normal");
    });

    // 发送消息按钮
    $chatBox.on('click', '.sendBtn', function () {
        var from = $.getFromId(),
            to = $.getToId(),
            content = $chatTextArea.html();
        if ($chatTextArea.text().length <= 0) {
            $chatBox.find(".sendBtn").tips('请输入聊天内容');
            return false;
        }
        $.sendMsg(content);
        $.addRightMsg(from, content, {time: $.serverTime()});
        $.saveMsg(to, from, content, 'msg', true, {time: $.serverTime()});
        $chatTextArea.html("");
    });

    $chatBox.keydown(function (event) {
        var eventKey = $.getEventKey(event);
        if (eventKey == $.keyCode['ENTER'] && !(event.ctrlKey)) {
            if ($chatTextArea.text().length > 0) {
                $chatBox.find(".sendBtn").trigger('click');
            } else {
                $chatBox.find(".sendBtn").tips('请输入聊天内容');
            }
            return false;
        }
    });

    $chatBox.keyup(function (event) {
        var eventKey = $.getEventKey(event);
        if (eventKey == $.keyCode['ENTER'] && event.ctrlKey) {
            $chatTextArea.html($chatTextArea.html() + '<div><br></div>');
            //设置输入焦点
            var last = document.getElementById("chatTextArea").lastChild;
            var textbox = document.getElementById('chatTextArea');
            var sel = window.getSelection();//表示用户选择的文本范围或光标的当前位置。
            var range = document.createRange();//返回range对象
            range.selectNodeContents(textbox);//选择节点的子节点
            range.collapse(false);//向边界点折叠range
            range.setEndAfter(last);//节点的range对象的起点位置;
            range.setStartAfter(last);//节点的range对象的结束位置;
            sel.removeAllRanges();//移除所有额range对象
            sel.addRange(range);
        }
    });

    // 发送图片
    $("#inputImage").uploadFile('/chat/push/picture', {
        from: $.getFromId(),
        to: $.getToId()
    }, function (msg, self) {
        if (msg.state) {
            var $picture = $.addRightPicture($.getFromId(), URL.createObjectURL(self.files[0]), {time: $.serverTime()});
            $picture.attr('href', msg.info);
            $.sendPicture(msg.info);
            $.saveMsg($.getToId(), $.getFromId(), msg.info, 'picture', true, {time: $.serverTime()});
        } else {
            $.alert(msg.info);
        }
        $(self).val('');
    }, {
        before: function (file) {
            if (file.files[0].size > 500 * 1024) {
                $.alert('上传文件不得大于500K');
                return false;
            }
            $chatBox.find('.upload-progress').show('fast');
            return true;
        },
        progress: function (percent, self) {
            $chatBox.find('.upload-bar').css('width', percent + '%');
            if (percent >= 100) {
                $chatBox.find('.upload-progress').hide('fast');
            }
        }
    });

    // 发送文件
    $("#inputFile").uploadFile('/chat/push/file', {
        from: $.getFromId(),
        to: $.getToId()
    }, function (msg, self) {
        if (msg.state) {
            var sarr = $(self).val().split('\\'),
                name = sarr[sarr.length - 1],
                size = self.files[0].size;
            $.sendFile(msg.info, name, size);
            $.addRightFile($.getFromId(), URL.createObjectURL(self.files[0]), {originName: name, size: size, time: $.serverTime()});
            $.saveMsg($.getToId(), $.getFromId(), msg.info, 'file', true, {originName: name, size: size, time: $.serverTime()});
        } else {
            $.alert(msg.info);
        }
        $(self).val('');
    }, {
        before: function (file) {
            if (file.files[0].size > 2000 * 1024) {
                $.alert('上传文件不得大于2M');
                return false;
            }
            $chatBox.find('.upload-progress').show('fast');
            return true;
        },
        progress: function (percent, self) {
            $chatBox.find('.upload-bar').css('width', percent + '%');
            if (percent >= 100) {
                $chatBox.find('.upload-progress').hide('fast');
            }
        }
    });
    // 游客初始化操作
    if ($.isGuest()) {
        if ($.getCache('chatUserName', []).length < 3) {
            var initGuestName = $.getGuestName();
            $serviceList.find('.user').html(initGuestName);
            $.setCache('chatUserName', initGuestName);
        } else {
            $serviceList.find('.user').html($.getCache('chatUserName', []));
        }
        // 设置用户ID
        $serviceList.find(".userId").val($.getFromId());
        // 更新客服信息到缓存
        $serviceList.find(".memberList li").each(function () {
            var id = $(this).data('id'),
                name = $(this).find('.userName').html(),
                face = $(this).find('.userFace').attr('src'),
                data = {id: id, name: name, face: face};
            $.saveUserInfo(id, data);
        });
    } else {
        // 判断当前缓存信息是否和当前用户信息匹配
        if ($.getFromId() != $serviceList.find(".userId").val()) {
            $.clearCache();
        }
    }

    // 清除缓存
    $serviceList.find(".clearIcon").click(function () {
        $.clearCache();
        window.location.reload();
    });
    // 共通初始化操作
    ;!(function () {
        // 初始化本地时间
        $chatBox.find('.localTime').val(Date.now());
        initMsgList();
        initTotalMsg();
        // 保存自身信息
        var id = $.getFromId(),
            data = {id: id, name: $.getFromName(), face: $.getFromFace()};
        $.saveUserInfo(id, data);
    })();
});