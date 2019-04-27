<?php use frontend\models\Article; ?>
<?php use yii\widgets\Breadcrumbs; ?>
<?= $this->regCss('projectDetail') ?>
<div class="content">
    <img class="project-img wow pulse animated" src="<?= img("project-detail-bg.jpg") ?>">
    <div class="crumbs">
        <div class="main-crumbs">
            <span>您的位置:</span>
            <?= Breadcrumbs::widget(['itemTemplate' => "<li>{link}＞</li>\n", 'links' => $this->context->links]) ?>
        </div>
    </div>
    <div class="detail-wrap">
        <img class="solution-head" src="<?= img('solution-head.jpg') ?>">
        <div class="solution-top">
            <div class="solution-title"><?= $article['title'] ?></div>
            <img src="<?= img('solution-icon.png') ?>">
            <div class="solution-summary"><?= $article['summary'] ?></div>
        </div>
        <div class="solution-content">
            <?= $article['content'] ?>
        </div>
    </div>
</div>