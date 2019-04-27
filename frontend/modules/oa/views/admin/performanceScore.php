<?php use common\helpers\Html; ?>

<?= $html ?>

<div class="pl-5">
    <p class="mt-10">等级描述：</p>
    <p>累计绩效点 <?= Html::successSpan('0 ~ 19') ?> 为 <?= Html::successSpan(1) ?> 级</p>
    <p>累计绩效点 <?= Html::successSpan('20 ~ 59') ?> 为 <?= Html::successSpan(2) ?> 级</p>
    <p>累计绩效点 <?= Html::successSpan('60 ~ 119') ?> 为 <?= Html::successSpan(3) ?> 级</p>
    <p>累计绩效点 <?= Html::successSpan('120 ~ 199') ?> 为 <?= Html::successSpan(4) ?> 级</p>
    <p>以此类推</p>
</div>