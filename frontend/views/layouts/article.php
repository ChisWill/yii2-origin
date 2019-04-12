<?php use common\helpers\Html; ?>
<?php use common\models\ArticleMenu; ?>
<?php use yii\widgets\Breadcrumbs; ?>
<?php frontend\assets\AppAsset::register($this) ?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
<?php if (config('seo_switch')): ?>
    <meta name="keywords" content="<?= config('seo_key') ?>">
    <meta name="description" itemprop="description" content="<?= config('seo_desc') ?>">
<?php endif ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0, minimum-scale=1, maximum-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

    <?php foreach (ArticleMenu::getMenus() as $action => $menu): ?>
        <?php if ($this->context->url == $action): ?>
            <?= Html::a($menu['name'] . '（x）', [$action]) ?>
            <br>
        <?php else: ?>
            <?= Html::a($menu['name'], [$action]) ?>
            <br>
        <?php endif ?>
    <?php endforeach ?>

    <?= Breadcrumbs::widget(['itemTemplate' => "<li>{link}-></li>\n", 'links' => $this->context->links]) ?>

    <?= $content ?>

    <div style="position: fixed; bottom: 50px; left: 800px;">
        <a href="http://www.miitbeian.gov.cn" target="_blank"><?= config('web_copyright') ?></a>
    </div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>