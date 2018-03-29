<?php use common\helpers\Html; ?>
<?php use yii\widgets\Breadcrumbs; ?>
<?php frontend\assets\AppAsset::register($this) ?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

    <?php 
    foreach ($this->getMenu() as $action => $menu) {
        if ($this->context->url == $action) {
            echo Html::a($menu['name'] . '（x）', [$action]);
            echo '<br>';
        } else {
            echo Html::a($menu['name'], [$action]);
            echo '<br>';
        }
    }
    ?>

    <?= Breadcrumbs::widget(['itemTemplate' => "<li>{link}-></li>\n", 'links' => $this->context->links]) ?>

    <?= $content ?>

    <div style="position: fixed; bottom: 50px; left: 800px;">
        <a href="http://www.miitbeian.gov.cn" target="_blank"><?= config('web_copyright') ?></a>
    </div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>