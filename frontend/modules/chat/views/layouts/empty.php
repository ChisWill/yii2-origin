<?php use common\helpers\Html; ?>
<?php chat\assets\ServerAsset::register($this) ?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

    <?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>