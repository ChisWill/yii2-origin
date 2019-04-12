<?= $html ?>

<script>
$(function () {
    $(".list-container").on('click', '.editPass', function () {
        var $this = $(this);
        $.prompt('修改密码', function (msg) {
            $.post($this.attr('href'), {
                value: msg
            }, function (msg) {
                if (msg.state) {
                    $.alert('修改成功');
                } else {
                    $.alert(msg.info);
                }
            }, 'json');
        });
        return false;
    });
});
</script>