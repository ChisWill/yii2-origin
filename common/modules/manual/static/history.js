$(function () {
    // 版本恢复按钮
    $("button.reset").click(function () {
        if ($(".history-list li.active").index($(".history-list li")) === 0) {
            $.alert('当前已是最新版本，不需要恢复~！');
            return false;
        }
        $.confirm('确定要恢复当前文章至此版本吗？', function () {
            $.post('/manual/site/revert', {
                id: $(".history-list li.active").data('id')
            }, function (msg) {
                if (msg.state) {
                    parent.window._triggerClickItem();
                    parent.$.fancybox.close();
                } else {
                    $.alert(msg.info);
                }
            }, 'json');
        });
    });

    // 版本选择事件
    $(".history-list").on('click', 'li', function () {
        var $this = $(this);
        if ($this.hasClass('active')) {
            return false;
        }
        $this.siblings('li').removeClass('active');
        $this.addClass('active');
        $("#versionAction").html($(".history-list-li.active .history-message").html());
        $("#versionId").text($(".history-list-li.active").data('title'));
        $("#versionDescription").html($(".history-list-li.active .history-meta").text());
        
        $.post('/manual/site/diff', {
            id: $this.data('id'),
            menuId: $this.data('menu-id')
        }, function (msg) {
            if (msg.state) {
                $(".history-files-patch").html(msg.info);
                $(".text-success span").html($(".history-files-patch td.Right").length);
                $(".text-danger span").html($(".history-files-patch .ChangeReplace td.Left").length);
                $(".history-files-patch td").each(function () {
                    $(this).parent('tr').children('th').addClass($(this).attr('class'));
                });
            } else {
                $.alert(msg.info);
            }
        }, 'json');
    });

    $(".history-list>li:eq(0)").trigger('click');
});