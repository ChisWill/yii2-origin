<?php use frontend\models\Article; ?>
<?php use yii\widgets\Breadcrumbs; ?>
<?= $this->regCss('case') ?>
<?php 
    $id = get('id');
    $article = Article::findModel($id);
 ?>

<div class="content">
    <img class="case-img wow pulse animated" src="<?= img('case-img.jpg') ?>" />
    <div class="crumbs">
        <div class="main-crumbs">
            <span>您的位置:</span>
            <?= Breadcrumbs::widget(['itemTemplate' => "<li>{link}＞</li>\n", 'links' => $this->context->links]) ?>
        </div>
    </div>
    <div class="case-content wow bounceInUp animated">
        <?= $article['content'] ?>
    </div>
</div>