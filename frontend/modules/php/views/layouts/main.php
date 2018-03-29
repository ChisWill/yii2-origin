<?php use common\helpers\Html; ?>
<?php php\assets\Asset::register($this) ?>
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
<div class="wrap">
    <header class="text-center">
        <nav>
        <?php foreach ($this->context->menu as $url => $name): ?>
        <?php 
            if ($this->context->route === $url) {
                $class = 'selected';
                $span = '<span></span>';
            } else {
                $class = 'font-14';
                $span = '';
            }
        ?>
            <a class="<?= $class ?>" href="<?= url([$url]) ?>"><?= $name . $span ?></a>
        <?php endforeach ?>
        </nav>
        <div class="login-regist">
        <?php if (user()->isGuest): ?>
            <a class="font-14" href="<?= url(['site/login']) ?>"><?= t('Login') ?></a>
            <a class="font-14" href="<?= url(['site/register']) ?>"><?= t('Register') ?></a>
        <?php else: ?>
            <a class="font-14" href="<?= url(['account/index']) ?>"><?= u()->username ?></a>
            <a class="font-14" href="<?= url(['site/logout']) ?>"><?= t('Logout') ?></a>
        <?php endif ?>
        </div>
    </header>

    <?= $content ?>

    <?php if (config('web_copyright')): ?>
    <footer class="text-center">
        <span><?= config('web_copyright') ?></span>
    </footer>
    <?php endif ?>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>