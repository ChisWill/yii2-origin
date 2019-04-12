<?php use common\helpers\Html; ?>
<!-- 引入静态资源包，相当于加载公共的 JS 和 CSS 文件，具体内容查阅该资源包类 -->
<?php common\modules\demo\assets\Asset::register($this) ?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0, minimum-scale=1, maximum-scale=1">
    <?= Html::csrfMetaTags() ?>
    <!-- 页面标题 -->
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
    <!-- 以下为页面头部内容 -->
    <header>
        <!-- 引用控制器的变量，使用`$this->context`可以访问到当前控制器 -->
        <?= $this->context->title ?>
    </header>
    <div class="container clearfix">
        <!-- 引入子布局，子布局命名必须以下划线开头 -->
        <?= $this->render('_left') ?>
        <div class="right-aside">
            <div class="content">
                <!-- 渲染页面主体内容 -->
                <?= $content ?>
            </div>
            <!-- 以下为页面尾部内容 -->
            <footer>
                <?= $this->context->hint ?>
            </footer>
        </div>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>