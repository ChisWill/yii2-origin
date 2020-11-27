<?php use common\helpers\Html; ?>
<?php es\assets\Asset::register($this) ?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0, minimum-scale=1, maximum-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

    <header><?= $this->title ?></header>
    <?php if ($this->context->id != 'site'): ?>
        <div class="breadcrumbs">
            <a href="<?= url('site/index') ?>">返回</a>
        </div class="breadcrumbs">
    <?php endif ?>
    <?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>