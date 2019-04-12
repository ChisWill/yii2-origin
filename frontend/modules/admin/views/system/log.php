<style>
    .fieldset-aside {
        padding: 10px 15px;
    }
</style>

<fieldset class="fieldset-box">
    <legend>日志文件</legend>
    <div class="fieldset-aside">
    <?= $fileHtml ?>
    </div>
</fieldset>

<fieldset class="fieldset-box">
    <legend>日志列表</legend>
    <div class="fieldset-aside">
    <?= $html ?>
    </div>
</fieldset>

<script>
$(function () {
    $(".clearFileBtn").click(function () {
        var $this = $(this);
        $.confirm('确认清空"' + $this.data('file') + '"文件？', function () {
            $.get($this.attr('href'), function () {
                window.location.reload();
            }, 'json');
        });
        return false;
    });
});
</script>