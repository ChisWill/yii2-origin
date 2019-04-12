<?php use common\helpers\Html; ?>

<?= $html ?>

<p class="mt-10">待处理任务数：<?= Html::errorSpan($waitCount, ['class' => 'waitCount']) ?></p>

<script>
$(function () {
    // 标记完成
    $(".list-container").on('click', '.overBtn', function () {
        var $this = $(this);
        $.confirm($(this).data('info'), function () {
            $.post($this.attr('href'), function (msg) {
                if (msg.state) {
                    window.location.reload();
                } else {
                    $.alert(msg.info);
                }
            }, 'json');
        });
        return false;
    });
    // 审核
    $(".list-container").on('click', '.verifyBtn', function () {
        var $this = $(this),
            ajaxPost = function (data) {
                $.post($this.attr('href'), data, function (msg) {
                    if (msg.state) {
                        window.location.reload();
                    } else {
                        $.alert(msg.info);
                    }
                }, 'json');
            };
        layer.confirm($this.data('info'), {
            title: '审核',
            btn: ['确认已完成', '没完成']
        }, function () {
            ajaxPost({state: 1});
        }, function () {
            $.prompt('请输入驳回理由', function (value) {
                ajaxPost({state: 0, info: value});
            });
        });
        return false;
    });
    // 延期
    $(".list-container").on('click', '.delayBtn', function () {
        var $this = $(this),
            ajaxPost = function (data) {
            $.post($this.attr('href'), data, function (msg) {
                if (msg.state) {
                    $.alert('已延期' + data['day'] + '天', function () {
                        window.location.reload();
                    });
                } else {
                    $.alert(msg.info);
                }
            }, 'json');
        };
        $.prompt($this.data('info'), function (day) {
            $.prompt('请输入延期理由', function (reason) {
                ajaxPost({day: day, reason: reason});
            });
        });
        return false;
    });
});
</script>