$(function () {
    // 初始化marked
    marked.setOptions({
        renderer: new marked.Renderer(),
        gfm: true, // 启动Github样式的Markdown
        tables: true, // 支持Github表格，必须打开gfm选项
        breaks: true, // 支持Github换行符，必须打开gfm选项
        pedantic: false, // 只解析符合markdown.pl定义的，不修正markdown的错误
        sanitize: false, // 原始输出，忽略HTML标签
        smartLists: false, // 优化列表输出
        smartypants: false,
        highlight: function (code) {
            return hljs.highlightAuto(code).value;
        }
    });

    // markdown转换成html
    String.prototype.translate = function () {
        var string = this.toString();
        return string.parseHtml().replace(/<(\/)?(script|\?php)/ig, '&lt;$1$2');
    };

    //菜单条目hover效果
    $(".js-tree-anchor").hover(function () { 
        $(this).prev('.js-tree-hover').addClass('hovered');
    }, function () {
        $(this).prev('.js-tree-hover').removeClass('hovered');
    });

    // 任意区域点击隐藏菜单
    $("body").on('click', function () {
        $(".rightkey-menu").hide();
    });

    // 图片点击浏览大图事件
    $(".img-fancybox").fancybox({
        openEffect  : 'elastic',
        closeEffect : 'fade',
        padding: 0
    });

    // 显示最外层的菜单
    $("div.js-tree>ul.js-tree-container").addClass('extend');
});

(function ($) {
    $.fn.extend({
        // 给所有图片包裹一个指定规则的a标签
        wrapImg: function () {
            $(this).find('img').each(function () {
                $(this).wrap('<a class="img-fancybox" rel="upload-img" href="' + $(this).attr('src') + '"></a>');
            });
            return $(this);
        },
        /**
         * 字符串插入
         * 
         * @param  string vs 插入开始处
         * @param  string ve 插入结束处
         * @return object    当前的jQuery对象
         */
        insertAtCaret: function (vs, ve) {
            if (!vs) {
                console.error('At least one argument required!');
                return false;
            }
            ve = ve || '';
            var t = $(this)[0];
            if (document.selection) {
                this.focus();
                sel = document.selection.createRange();
                sel.text = vs;
                this.focus();
            } else if (t.selectionStart != undefined) {
                var startPos = t.selectionStart,
                    endPos = t.selectionEnd,
                    strs = t.value.substring(0, startPos),
                    strm = t.value.substring(startPos, endPos),
                    stre = t.value.substring(endPos, t.value.length),
                    p = strm.split(/\n/g),
                    pl = p.length,
                    vsl = vs.length,
                    vel = ve.length;
                if (pl > 1) {
                    var tempstr = '';
                    var len = 0;
                    for (var i = 0; i < pl; i++) {
                        if (p[i] != '') {
                            tempstr += vs + p[i] + ve + '\n';
                            len++;
                        } else {
                            tempstr += '\n';
                        }
                    };
                    // strm.replace('\n', vs + '\n' + ve);
                    t.value = strs + tempstr + stre;
                    this.focus();
                    t.selectionEnd = endPos + len * (vsl + vel);
                } else {
                    t.value = strs + vs + strm + ve + stre;
                    this.focus();
                    if (startPos == endPos) {
                        t.selectionEnd = endPos + vsl;
                    } else {
                        t.selectionEnd = endPos + vsl + vel;
                    }
                }
            } else {
                this.focus();
            }
            return $(this);
        }
    })
})(jQuery);