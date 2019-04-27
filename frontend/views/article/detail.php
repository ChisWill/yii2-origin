<?php use frontend\models\Article; ?>
<?php use yii\widgets\Breadcrumbs; ?>
<?= $this->regCss('detail') ?>
<title>首页详情</title>
<div class="wrap">
    <!-- 内容 -->
   <div class="content">
        <img class="detail-img" src="<?= img('detail-img.jpg') ?>">
        <div class="crumbs">
            <div class="main-crumbs">
                <span>您的位置:</span>
                <?= Breadcrumbs::widget(['itemTemplate' => "<li>{link}＞</li>\n", 'links' => $this->context->links]) ?>
            </div>
        </div>
        <div class="detail-wrap">
            <div class="detail-top">
                <div class="detail-title"><?= $article['title'] ?></div>
                <div class="time-account">
                    <img src="<?= img('time.png') ?>">
                    <span><?= $article['created_at'] ?></span>
                    <span>浏览次数:<?= $article['count'] ?>次</span>
                </div>
            </div>
            <div class="detail-bottom">
                <div class="detail-abstract">
                    摘要：<?= $article['summary'] ?>
                </div>
                <?= $article['content'] ?>
            </div>
        </div>
   </div>
</div>
