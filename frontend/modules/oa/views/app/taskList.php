<?php use common\helpers\Html; ?>

<?= $html ?>

<p class="mt-10">待处理任务数：<?= Html::errorSpan($waitCount, ['class' => 'waitCount']) ?></p>

<script>
$(function () {
    $(".list-container").on('click', '.overBtn', function () {
        var $this = $(this);
        $.confirm($(this).data('info'), function () {
            $.post($this.attr('href'), function () {
                location.reload();
            }, 'json');
        });
        return false;
    });
});
</script>