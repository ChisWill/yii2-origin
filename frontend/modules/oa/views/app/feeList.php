<?php use common\helpers\Html; ?>

<?= $html ?>

<p class="mt-20">项目总金额：<?= Html::likeSpan($totalAmount, ['class' => 'totalAmount']) ?>，总余款：<?= Html::errorSpan($totalRest, ['class' => 'totalRest']) ?></p>