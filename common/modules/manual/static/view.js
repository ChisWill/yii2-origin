$(function () {
    // 获取当前点击的元素
    var getClickedItem = function () {
        if ($(".navg-item.active").data('mode') === 'index') {
            return $(".js-tree-hover.clicked");
        } else {
            return $(".ul-list").find('li.active');
        }
    };

    // 获取指定列表项目的名称
    var getItemName = function ($item) {
        if ($(".navg-item.active").data('mode') === 'index') {
            return $item.parent().find('a').html();
        } else {
            return $item.html();
        }
    };

    // 获取当前激活的列表
    var getActiveList = function () {
        if ($(".navg-item.active").data('mode') === 'index') {
            return $(".js-tree-item>.js-tree-hover");
        } else {
            return $(".tab-item.active").find(".ul-list li");
        }
    };

    // 获取指定列表项目的ID
    var getItemId = function ($item) {
        if ($(".navg-item.active").data('mode') === 'index') {
            return $item.parent().data('id');
        } else {
            return $item.data('id');
        }
    };

    // 获取上一篇内容
    var setJumpUpArticle = function () {
        var index = getActiveList().index(getClickedItem());
        if (index > 0) {
            var lastItem = getActiveList().eq(index - 1);
            $(".jump-up").find('a').data('id', getItemId(lastItem)).html(getItemName(lastItem));
            $(".jump-up").show();
        } else {
            $(".jump-up").hide();
        }
    };

    // 获取下一篇内容
    var setJumpDownArticle = function () {
        var index = getActiveList().index(getClickedItem());
        if (index < getActiveList().length - 1) {
            var nextItem = getActiveList().eq(index + 1);
            $(".jump-down").find('a').data('id', getItemId(nextItem)).html(getItemName(nextItem));
            $(".jump-down").show();
        } else {
            $(".jump-down").hide();
        }
    };

    // 旋转父类菜单前的标识
    var toggleDropdown = function ($dp) {
        $dp.toggleClass('rotate');
        $dp.parent().next('.js-tree-container').toggleClass('extend');
    };
    
    // 菜单栏切换效果
    $(".cl-navg").on('click', '.navg-item', function () {
        if ($(this).hasClass('active')) {
            return false;
        } else {
            var mode = $(this).data('mode');
            $(".navg-item.active").removeClass('active');
            $(".tab-item.active").removeClass('active');
            $(this).addClass('active');
            $(".tab-item[data-mode='" + mode + "']").addClass('active');
            if (mode === 'collect') {
                $.get('/manual/site/collect-list', function (msg) {
                    if (msg.info) {
                        $(".collect-empty").hide();
                    } else {
                        $(".collect-empty").show();
                    }
                    var data = msg.info || {};
                    var $div = $("<div>");
                    for (var k in data) {
                        $div.append($("<li>").attr({
                            'data-id': data[k]['menu']['id']
                        }).text(data[k]['menu']['name']));
                    }
                    $(".collect-list").html($div.html());
                }, 'json');
            }
        }
    });
    
    // 搜索按钮事件
    $(".search-btn").click(function () {
        var keyword = $("input[name='keyword']").val();
        if (!keyword) {
            return false;
        }
        $.get('/manual/site/search', {'keyword': keyword}, function (msg) {
            if (msg.info) {
                $(".search-empty").hide();
            } else {
                $(".search-empty").show();
            }
            var data = msg.info || {};
            var $div = $("<div>");
            for (var k in data) {
                $div.append($("<li>").attr({
                    'data-id': data[k]['menu']['id'],
                    'data-keyword': keyword
                }).text(data[k]['menu']['name']));
            }
            $(".search-list").html($div.html());
        }, 'json');
    });

    // 文章收藏/取消
    $(".collect-item").click(function () {
        var $this = $(this),
            params = {
                menuId: $this.data('id')
            };
        // 取消收藏
        if ($this.hasClass('cancel')) {
            params.cancel = 1;
        } else {
            params.cancel = 0;
        }
        $.post('/manual/site/collect', params, function (msg) {
            if (msg.state) {
                if (params.cancel === 1) {
                    $this.removeClass('cancel').find('b').html('收藏');
                } else {
                    $this.addClass('cancel').find('b').html('取消');
                }
            } else {
                $.alert(msg.info);
            }
        }, 'json');
    });

    // 父类菜单前标识旋转事件
    $(".js-tree.view").on('click', '.js-tree-dropdown', function (e) {
        e.stopPropagation();
        var $dp = $(this);
        toggleDropdown($dp);
    });

    // 菜单条目点击效果
    $(".js-tree.view").on('click', '.js-tree-anchor', function (e) {
        e.stopPropagation();
        var $dp = $(this).find('.js-tree-dropdown');
        if ($dp.length > 0 && !$(this).next('ul').hasClass('extend')) {
            toggleDropdown($dp);
        }
        if ($(this).prev('div').hasClass('clicked')) {
            return false;
        }
        getClickedItem().removeClass('clicked');
        $(this).prev('.js-tree-hover').addClass('clicked');
        var menuId = $(this).parent().data('id');
        getViewContent(menuId);
    });

    // 搜索/收藏内容结果查看
    $(".ul-list").on('click', 'li', function () {
        if ($(this).hasClass('active')) {
            return false;
        }
        $(".ul-list .active").removeClass('active');
        $(this).addClass('active');
        getViewContent($(this).data('id'), $(this).data('keyword'));
    });

    // 获取查看的文档内容
    var getViewContent = function (menuId, keyword) {
        keyword = keyword || '';
        $.get('/manual/site/view-article', {
            menuId: menuId,
            keyword: keyword
        }, function (msg) {
            if (msg.state) {
                var inputString = msg.data.content.translate();
                var html = inputString.highlight(keyword);
                $(".view-body").html(html).wrapImg();
            } else {
                $(".view-body").html('');
            }
            $("#articleTitle").html(getItemName(getClickedItem()).highlight(keyword));
            if (msg.data.isCollect === false) {
                $(".collect-item").removeClass('cancel').find('b').html('收藏');
            } else {
                $(".collect-item").addClass('cancel').find('b').html('取消');
            }
            $(".collect-item").data('id', menuId);
            setJumpUpArticle();
            setJumpDownArticle();
        }, 'json');
    };

    // 文章点击的跳转
    $(".jump-up-link").click(function () {
        var id = $(this).data('id'),
            $target;
        if ($(".navg-item.active").data('mode') === 'index') {
            $target = $(".js-tree-item[data-id=" + id + "]>.js-tree-anchor");
            $target.parents('ul.js-tree-container').addClass('extend');
        } else {
            $target = $(".ul-list>li[data-id=" + id + "]");
        }
        $target.trigger('click');
    });

    // 点击第一个菜单
    $(".js-tree-anchor:eq(0)").trigger('click');
});