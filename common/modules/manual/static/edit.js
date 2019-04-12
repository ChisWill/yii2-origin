$(function () {
    // 自定义右击菜单
    document.oncontextmenu = function (e) {
        rightkeyItem = $(e.target).parents('.js-tree-item:eq(0)');
        if (rightkeyItem.length > 0) {
            var x = e.clientX;
            var y = e.clientY;
            $(".rightkey-menu").show();
            $(".rightkey-menu").offset({
                top: y,
                left: x
            })
            return false;
        }
    };

    // 动态增加菜单
    String.prototype.appendMenu = function (isRoot) {
        var html = this.toString(),
            $parent = isRoot === true ? $(".js-tree") : rightkeyItem;
        $parent.children('ul').append(html);
        // 选中新增的菜单并且展开所有父级菜单
        $parent.children('ul').find('li:last .js-tree-anchor').trigger('click').parents('ul.js-tree-container').addClass('extend');
    };

    // 文档修改之后是否被保存
    var saved = {
        oldContent: $("#inputTextArea").val(),
        initContent: $("#inputTextArea").val(),
        warning: false,
        setWarning: function (isChanged){
            this.warning = isChanged;
            if (isChanged) {
                $('.thinkeditor-tools .save').addClass('warning');
            } else {
                $('.thinkeditor-tools .save').removeClass('warning');
            }
        }
    };

    // 菜单排序事件
    $(".js-tree-container").dragSort($.config('dragSort', {onEnd: function () {
        var $ul = $(this.el),
            idList = [];
        $ul.children('li').each(function (index, el) {
            idList.push($(this).data('id'));
        });
        $.get('/manual/site/sort-menu', {
            idList: idList
        }, function (msg) {

        }, 'json');
    }}));

    //获取编辑的文档内容
    var getEditContent = function (menuId, keyword) {
        $.get('/manual/site/view-article', {
            menuId: menuId,
            keyword: keyword
        }, function (msg) {
            var content = msg.data.content || '';
            var $history = $(".thinkeditor-tools .history");
            $history.attr('href', $history.data('href') + '?id=' + menuId);
            $("#inputTextArea").val(content);
            $("#outputArea").html(content.translate()).wrapImg();
        }, 'json');
    };

    // 获取当前点击的元素
    var getClickedItem = function () {
        return $(".js-tree-hover.clicked");
    };

    // 获取指定列表项目的名称
    var getItemName = function ($item) {
        return $item.parent().find('a').html();
    };

    // 旋转父类菜单前的标识
    var toggleDropdown = function ($dp) {
        $dp.toggleClass('rotate');
        $dp.parent().next('.js-tree-container').toggleClass('extend');
    };

    var isPreview = true;

    // 在历史记录的弹窗页面中，恢复版本时的回调触发事件
    _triggerClickItem = function () {
        $('.js-tree .js-tree-hover.clicked').next('div').trigger('click', 'revert');
    }

    // 系统菜单的按钮事件
    $(".thinkeditor-tools-group").on('click', 'a', function () {
        var cls = $(this).attr('class').split(' ')[1];
        switch (cls) {
            case 'preview':
                if ($(this).hasClass('active')) {
                    isPreview = false;
                    $(".edit-left").css('width', '100%');
                    $(".edit-right").hide();
                    $(this).removeClass('active');
                } else {
                    isPreview = true;
                    $(".edit-left").css('width', '50%');
                    $(".edit-right").show();
                    $(this).addClass('active');
                }
                break;
            case 'history':
                break;
        }
    });

    // 上传图片事件绑定
    $("#uploadImage").change(function (event) {
        var fileSize = 0,
            target = event.target;
        if ($.isIe()) {
            fileSize = 0;
        } else {
            fileSize = target.files[0].size;
        }
        var size = fileSize / 1024;
        if (size > 2048) {
            $.alert('图片不能大于2M~！');
            return false;
        }
        $("#uploadForm").ajaxSubmit($.config('ajaxSubmit', {
            success: function (msg) {
                if (msg.state) {
                    var $textarea = $('#inputTextArea');
                    $textarea.insertAtCaret('![](' + msg.info + ')');
                    $textarea.trigger('keyup');
                } else {
                    $.alert(msg.info);
                    return false;
                }
            }
        }));
    });

    // 工具栏的按钮事件
    $(".thinkeditor-tools").on('click', 'a', function () {
        var cls = $(this).attr('class').split(' ')[1];
        var $textarea = $('#inputTextArea');
        switch (cls) {
            case 'h1':
                $textarea.insertAtCaret('# ');
                break;
            case 'h2':
                $textarea.insertAtCaret('## ');
                break;
            case 'h3':
                $textarea.insertAtCaret('### ');
                break;
            case 'h4':
                $textarea.insertAtCaret('#### ');
                break;
            case 'bold':
                $textarea.insertAtCaret('**', '**');
                break;
            case 'italic':
                $textarea.insertAtCaret('*', '*');
                break;
            case 'ul':
                $textarea.insertAtCaret('* ');
                break;
            case 'ol':
                $textarea.insertAtCaret('1. ');
                break;
            case 'image':
                $("#uploadImage").trigger('click');
                break;
            case 'code':
                $textarea.insertAtCaret('`', '`');
                break;
            case 'hr':
                $textarea.insertAtCaret('* * * * *\n');
                break;
            case 'blockquote':
                $textarea.insertAtCaret('> ');
                break;
        }
        if (isPreview === true) {
            $("#outputArea").html($textarea.val().translate()).wrapImg();
        }
    });

    //快捷键设置
    $("#inputTextArea").on('keydown', function (e) {
        var eventKey = $.getEventKey(e);
        var $textarea = $('#inputTextArea');
        if (e.ctrlKey && eventKey != $.keyCode['CTRL']) {
            switch (eventKey) {
                case $.keyCode['S']:
                    $(".thinkeditor-tools .save").trigger('click');
                    saved.setWarning(false);
                    return false;
                case $.keyCode['1']:
                    $(".thinkeditor-tools .h1").trigger('click');
                    return false;
                case $.keyCode['2']:
                    $(".thinkeditor-tools .h2").trigger('click');
                    return false;
                case $.keyCode['3']:
                    $(".thinkeditor-tools .h3").trigger('click');
                    return false;
                case $.keyCode['4']:
                    $(".thinkeditor-tools .h4").trigger('click');
                    return false;
                case $.keyCode['B']:
                    $(".thinkeditor-tools .bold").trigger('click');
                    return false;
                case $.keyCode['I']:
                    $(".thinkeditor-tools .italic").trigger('click');
                    return false;
                case $.keyCode['U']:
                    $(".thinkeditor-tools .ul").trigger('click');
                    return false;
                case $.keyCode['O']:
                    $(".thinkeditor-tools .ol").trigger('click');
                    return false;
                case $.keyCode['L']:
                    $(".thinkeditor-tools .link").trigger('click');
                    return false;
                case $.keyCode['G']:
                    $textarea.insertAtCaret('![](http://', ')');
                    return false;
                case $.keyCode['D']:
                    $(".thinkeditor-tools .code").trigger('click');
                    return false;
                case $.keyCode['H']:
                    $(".thinkeditor-tools .hr").trigger('click');
                    return false;
                case $.keyCode['Q']:
                    $(".thinkeditor-tools .blockquote").trigger('click');
                    return false;
                case $.keyCode['/']:
                    $textarea.insertAtCaret('<!--', '-->');
                    return false;
                default:
                    break;
            }
        } else {
            switch (eventKey) {
                case $.keyCode['TAB']:
                    $textarea.insertAtCaret('    ');
                    return false;
                default:
                    break;
            }
        }

        // 列表输入处理
        if (eventKey == $.keyCode['ENTER']) {
            var endPos = this.selectionEnd;
            var str1 = this.value.substring(0, endPos);
            var str2 = this.value.substring(endPos);
            var enterindex = str1.lastIndexOf('\n');
            var substring = str1.substring(enterindex + 1);
            if (substring.search(/\d+\.\s/i) == 0) {
                var num = parseInt(substring.match(/\d+\.\s/i)[0].replace('. ', ''));
                num++;
                $(this).val(str1 + '\n' + num + '. ' + str2);
                this.selectionEnd = endPos + 4;
                return false;
            }
            if (substring.search(/\*\s/i) == 0) {
                $(this).val(str1 + '\n' + '* ' + str2);
                this.selectionEnd = endPos + 3;
                return false;
            }
            if (substring.search(/\s\s\s\s/i) == 0) {
                $(this).val(str1 + '\n' + '    ' + str2);
                this.selectionEnd = endPos + 5;
                return false;
            }
            if (substring.search(/\t/i) == 0) {
                $(this).val(str1 + '\n' + '\t' + str2);
                this.selectionEnd = endPos + 2;
                return false;
            }
        }
    });

    // 保存手册内容事件
    $(".thinkeditor-tools").on('click', '.save.warning', function () {
        $('.saving-tip').addClass('show');
        $.post('/manual/site/update-article', {
            menuId: getClickedItem().parent().data('id'),
            content: $("#inputTextArea").val()
        }, function (msg) {
            if (!msg.state) {
                $.alert(msg.info);
            } else {
                saved.setWarning(false);
                saved.initContent = $("#inputTextArea").val();
            }
            $('.saving-tip').removeClass('show');
        }, 'json');
    });

    // 实时显示效果
    $("#inputTextArea").on('keyup', function () {
        if (saved.oldContent != $(this).val()) {
            saved.setWarning(true);
            saved.oldContent = $(this).val();
        }
        if (saved.initContent == $(this).val()) {
            saved.setWarning(false);
        }
        if (isPreview === true) {
            $("#outputArea").html($(this).val().translate()).wrapImg();
        }
    });

    // 创建根目录事件
    $("#addCatalogBtn").click(function () {
        $.prompt('创建根目录', function (value) {
            $.post('/manual/site/create-menu', {
                menuName: value,
                pid: 0
            }, function (msg) {
                if (msg.state) {
                    msg.info.appendMenu(true);
                } else {
                    $.alert(msg.info);
                }
            });
        });
    });    

    // 父类菜单前标识旋转事件
    $(".js-tree.edit").on('click', '.js-tree-dropdown', function (e) {
        e.stopPropagation();
        var $dp = $(this);
        toggleDropdown($dp);
    });

    // 菜单条目点击效果
    $(".js-tree.edit").on('click', '.js-tree-anchor', function (e, params) {
        e.stopPropagation();
        var that = this,
            $dp = $(this).find('.js-tree-dropdown');
        if (saved.warning) {
            if ($(this).prev('div').hasClass('clicked')) {
                return false;
            }
            $.confirm('编辑区域有尚未保存的内容，确定要离开吗？', function () {
                saved.setWarning(false);
                if ($dp.length > 0) {
                    toggleDropdown($dp);
                }
                getClickedItem().removeClass('clicked');
                $(that).prev('.js-tree-hover').addClass('clicked');
                var menuId = $(that).parent().data('id');
                getEditContent(menuId);
            });
        } else {
            saved.setWarning(false);
            if ($dp.length > 0 && !$(this).next('ul').hasClass('extend')) {
                toggleDropdown($dp);
            }
            if ($(this).prev('div').hasClass('clicked') && params !== 'revert') {
                return false;
            }
            getClickedItem().removeClass('clicked');
            $(that).prev('.js-tree-hover').addClass('clicked');
            var menuId = $(that).parent().data('id');
            getEditContent(menuId);
        }
    });

    // 右键菜单——创建新子集
    $(".rightkey-menu").on('click', '#createChild', function () {
        $.prompt('在“' + rightkeyItem.find('a:eq(0)').text() + '”下创建新目录', function (value) {
            $.post('/manual/site/create-menu', {
                menuName: value,
                pid: rightkeyItem.data('id')
            }, function (msg) {
                if (msg.state) {
                    if (rightkeyItem.find('.js-tree-anchor>i').length === 0) {
                        rightkeyItem.children('.js-tree-anchor').prepend('<i class="js-tree-dropdown"></i>');
                    }
                    msg.info.appendMenu();
                } else {
                    $.alert(msg.info);
                }
            });
        });
    });

    // 右键菜单——编辑菜单名称
    $(".rightkey-menu").on('click', '#editMenu', function () {
        $.prompt('更改“' + rightkeyItem.find('a:eq(0)').text() + '”的标题', function (value) {
            $.post('/manual/site/edit-menu', {
                menuName: value,
                id: rightkeyItem.data('id')
            }, function (msg) {
                if (msg.state) {
                    rightkeyItem.find('a:eq(0)').html(msg.info);
                } else {
                    $.alert(msg.info);
                }
            });
        });
    });

    // 右键菜单——删除菜单
    $(".rightkey-menu").on('click', '#deleteMenu', function () {
        $.confirm('确认删除目录“' + rightkeyItem.find('a:eq(0)').text() + '”？', function () {
            $.post('/manual/site/delete-menu', {
                id: rightkeyItem.data('id')
            }, function (msg) {
                if (msg.state) {
                    if (rightkeyItem.siblings().length === 0) {
                        rightkeyItem.parent('ul').prev().find('i').remove();
                    }
                    // 删除后的菜单选中事件
                    var $target = '';
                    if (rightkeyItem.next('li').length > 0) {
                        $target = rightkeyItem.next('li').children('.js-tree-anchor');
                    } else if (rightkeyItem.prev('li').length > 0) {
                        $target = rightkeyItem.prev('li').children('.js-tree-anchor');
                    } else if (rightkeyItem.parent('ul').parent('li').length > 0) {
                        $target = rightkeyItem.parent('ul').parent('li').children('.js-tree-anchor');
                    }
                    if ($target) {
                        $target.trigger('click');
                    } else {
                        $("#inputTextArea").val('');
                        $("#outputArea").html(''.translate()).wrapImg();
                    }
                    rightkeyItem.remove();
                } else {
                    $.alert(msg.info);
                }
            }, 'json');
        });
    });

    // 点击第一个菜单
    $(".js-tree-anchor:eq(0)").trigger('click');

    // 滚动条同步
    $(".edit-left #inputTextArea").scroll(function () {
        $(".edit-right #outputArea").scrollTop($(this).scrollTop()); 
    });
    $(".edit-right #outputArea").scroll(function () {
        $(".edit-left #inputTextArea").scrollTop($(this).scrollTop()); 
    });

    'fancybox.iframe'.config({
        'minWidth': 1000
    });
});