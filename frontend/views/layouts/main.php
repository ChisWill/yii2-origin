<?php use common\helpers\Html; ?>
<?php frontend\assets\AppAsset::register($this) ?>
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

    <?= $content ?>

   <!--  <div style="position: fixed; bottom: 50px; left: 800px;">
        <a href="http://www.miitbeian.gov.cn" target="_blank"><?= null//config('web_copyright') ?></a>
    </div -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>