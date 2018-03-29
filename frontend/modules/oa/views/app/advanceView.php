<?php use common\helpers\Html; ?>
<h2 class="text-c pd-10"><?= $app->code ?> - <?= $app->label($field) ?></h2>
<pre><code><?= $value['text'] ?></code></pre>
<div class="pl-10 pr-10 pt-10">最后更新人：<?= Html::errorSpan($value['updated_by']) ?></div>
<div class="pl-10 pr-10 pt-10">最后更新时间：<?= Html::errorSpan($value['updated_at']) ?></div>