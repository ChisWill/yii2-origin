<?php use frontend\models\Article; ?>
<?php use frontend\models\ArticleMenu; ?>
<?php use yii\widgets\Breadcrumbs; ?>
<?= $this->regCss('news') ?>
<div class="content">
    <img class="news-img wow pulse animated" src="<?= img('news-img.jpg') ?>">
    <div class="crumbs">
        <div class="main-crumbs">
            <span>您的位置:</span>
            <?= Breadcrumbs::widget(['itemTemplate' => "<li>{link}＞</li>\n", 'links' => $this->context->links]) ?>
        </div>
    </div>
    <div class="news-title-wrap">
        <img src="<?= img('new-title.png') ?>">
        <div class="news-tab">
        <?php foreach ($subMenus as $menu): ?>
            <?php 
                if ($menu['id'] == $child['id']) {
                    $class = 'active';
                } else {
                    $class = '';
                }
            ?>
            <a href="<?= url([$parent['url'], 'id' => $menu['id']]) ?>" class="tab-title <?= $class ?>"><?= $menu['name'] ?><span class="arrow-tri"></span></a>
        <?php endforeach ?>
        </div>
    </div>
    <div class="news-list">
        <?php foreach (Article::getArticleQuery($child['id'])->paginate() as $article): ?>
        <a class="news-item clearfix" href="<?= url(['detail', 'id' => $article['id']]) ?>">
            <img class="fl item-left" src="<?= $article['cover'] ?>">
            <div class="fr item-right">
                <div class="item-title"><?= $article['title'] ?></div>
                <div class="item-abstract"><?= $article['summary'] ?></div>
                <div class="item-time">
                    <img src="<?= img('mobile-time.png') ?>">
                    <span><?= $article['created_at'] ?></span>
                </div>
            </div>
        </a>
        <?php endforeach ?>
    </div>
    <div class="page-wrap">
        <?= self::linkPager() ?>
    </div>
</div>