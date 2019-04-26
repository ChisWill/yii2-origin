<?php use common\helpers\Html; ?>

<?= $html ?>

<p class="mt-10">
    总收入：<?= Html::successSpan($income, ['class' => 'income']) ?>，
    总支出：<?= Html::errorSpan($spend, ['class' => 'spend']) ?>，
    盈亏总计：<?= Html::likeSpan($count, ['class' => 'count']) ?>
</p>
<p class="mt-10">
    人工支出：<?= Html::errorSpan($human, ['class' => 'human']) ?>，
    股东分红：<?= Html::errorSpan($share, ['class' => 'share']) ?>
</p>

<script>
$(function () {
    $("[name='search[month]']").attr('onfocus', "WdatePicker({dateFmt: 'yyyy-MM'});");
});
</script>