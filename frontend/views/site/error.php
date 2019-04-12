<?php
use common\helpers\Html;
$this->title = $name;
?>
<?= $this->regCss('error') ?>
<?= $this->regJs('rem') ?>
<div class="error-box">
    <div class="error-text">
        <?= nl2br(Html::encode($message)) ?>
    </div>
</div>