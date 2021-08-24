<?php

use common\helpers\Html; ?>

<?= $html ?>

<div>
    <span>总销售额：</span>
    <?= Html::errorSpan($total) ?>
    <span>元</span>
</div>