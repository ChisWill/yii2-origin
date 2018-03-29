<?php use common\helpers\Html; ?>

<?= $html ?>

<?php foreach ($countData as $value): ?>
<div><?= $value['realname'] ?>业绩为：<?= Html::successSpan($value['score'], ['class' => 'scoreDiv', 'data-uid' => $value['user_id']]) ?></div>
<?php endforeach ?>

<script>
$(function () {
    $(document).ajaxSuccess(function (event, xhr) {
        var data = xhr.responseJSON.data;
        $(".scoreDiv").each(function () {
            if (data[$(this).data('uid')]) {
                $(this).html(data[$(this).data('uid')]).parent().removeClass('hidden');
            } else {
                $(this).parent().addClass('hidden');
            }
        });
    });
});
</script>