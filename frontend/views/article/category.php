<?php use frontend\models\Article; ?>
<?php use frontend\models\ArticleMenu; ?>
<?php use yii\widgets\Breadcrumbs; ?>
<?php $this->regCss('caseCenter') ?>
<?php $this->regJs('jQuery.marquee.min') ?>

<!-- 内容 -->
<div class="content">
    <img class="caseCenter-img wow pulse animated" src="<?= img("caseCenter.jpg") ?>">
    <div class="crumbs">
        <div class="main-crumbs">
            <span>您的位置:</span>
            <?= Breadcrumbs::widget(['itemTemplate' => "<li>{link}＞</li>\n", 'links' => $this->context->links]) ?>
        </div>
    </div>
    <div class="caseCenter-content">
        <!-- 案例展示 -->
        <div class="case-show">
            <img class="title wow zoomIn animated" src="<?= img('caseshow-title.jpg') ?>">
            <div class="show-tab">
                <h1 class="active" data-tab="all-tab">全部</h1>
                <h1 data-tab="pc-tab">网站</h1>
                <h1 data-tab="phone-tab">移动端</h1>
                <h1 data-tab="program-tab">小程序</h1>
            </div>
            <div class="show-content">
                <div id="all-tab" class="tab-container clearfix">
                <?php foreach (Article::getAllArticleQuery('case-all')->asArray()->all() as $article): ?>
                    <a href="<?= url(['detail', 'id' => $article['id']]) ?>" class="show-item wow zoomIn animated">
                    <div class="case-cover" style="background: url(<?= $article['cover'] ?>) center center no-repeat;background-size: cover;"></div>
                        <div class="product-title"><?= $article['title'] ?></div>
                        <img class="case-line" src="<?= img('case-line.png') ?>" alt="<?= config('web_name') . '-网站开发' ?>">
                        <div class="product-abstract"><?= $article['summary'] ?></div>
                    </a>
                <?php endforeach ?>
                </div>
                <div id="pc-tab" class="tab-container clearfix hide">
                <?php foreach (Article::getArticleQuery('case-web')->asArray()->all() as $article): ?>
                    <a href="<?= url(['detail', 'id' => $article['id']]) ?>" class="show-item">
                        <img class="case-cover" src="<?= $article['cover'] ?>" alt="<?= config('web_name') . '-网站建设' ?>">
                        <div class="product-title"><?= $article['title'] ?></div>
                        <img class="case-line" src="<?= img('case-line.png') ?>">
                        <div class="product-abstract"><?= $article['summary'] ?></div>
                    </a>
                <?php endforeach ?>
                </div>
                <div id="phone-tab" class="tab-container clearfix hide">
                <?php foreach (Article::getArticleQuery('case-phone')->asArray()->all() as $article): ?>
                    <a href="<?= url(['detail', 'id' => $article['id']]) ?>" class="show-item">
                        <img class="case-cover" src="<?= $article['cover'] ?>" alt="<?= config('web_name') . '-移动端开发' ?>">
                        <div class="product-title"><?= $article['title'] ?></div>
                        <img class="case-line" src="<?= img('case-line.png') ?>">
                        <div class="product-abstract"><?= $article['summary'] ?></div>
                    </a>
                <?php endforeach ?>
                </div>
                <div id="program-tab" class="tab-container clearfix hide">
                <?php foreach (Article::getArticleQuery('case-mini')->asArray()->all() as $article): ?>
                    <a href="<?= url(['detail', 'id' => $article['id']]) ?>" class="show-item">
                        <img class="case-cover" src="<?= $article['cover'] ?>" alt="<?= config('web_name') . '-微信小程序开发' ?>">
                        <div class="product-title"><?= $article['title'] ?></div>
                        <img class="case-line" src="<?= img('case-line.png') ?>">
                        <div class="product-abstract"><?= $article['summary'] ?></div>
                    </a>
                <?php endforeach ?>
                </div>
            </div>
        </div>
        <!-- 我们的合作 -->
        <img class="teamwork-title wow zoomIn animated" src="<?= img('teamwork-title.jpg') ?>">
        <div class="teamwork" id="marquee1">
            <ul class="teamwork-content clearfix" >
            <?php foreach (Article::getArticleQuery('case-cooperate')->asArray()->all() as $article): ?>
                <li class="teamwork-item fl">
                  <img src="<?= $article['cover'] ?>">
                  <span class="help_img"></span>
                </li>
            <?php endforeach ?>
            </ul>
        </div>
        
        <!-- 解决方案 -->
        <img class="solution-title wow zoomIn animated" src="<?= img('solution-title.jpg') ?>" alt="移动应用、网站建设解决方案">
        <div class="solution-content">
            <div class="solution-tab">
                <?php $active = 'active' ?>
                <?php foreach (ArticleMenu::getSubMenus('case-solution') as $menu): ?>
                <div class="<?= $active ?>" data-id="<?= $menu['id'] ?>"><?= $menu['name'] ?></div>
                <?php $active = ''; ?>
                <?php endforeach ?>
            </div>
            <div class="solution-container">
                
            </div>
        </div>
    </div>
</div>

<script>
$(function () {
    $(".show-tab h1").click(function() {
        $(this).addClass('active').siblings().removeClass('active');
        $("#" + $(this).data('tab')).show().siblings().hide();
    })

    $(".solution-tab div").click(function() {
        $(this).addClass('active').siblings().removeClass('active');
        $("#" + $(this).data('tab')).show().siblings().hide();
        var id = $(this).data('id');
        $(".solution-container").html("");
        $.get("<?= url(['article/data']) ?>", {id: id}, function (msg) {
            if (msg.state) {
                var article = msg.info;
                for(var key in article) {
                    var $solutionItem = $("<div>").addClass("solution-item clearfix wow zoomIn animated");
                    var $solutionImg = $("<img>").addClass('fl').attr("src", article[key]['cover']).attr("alt", article[key]['title']);
                    var $solutionRight = $("<div>").addClass("fr solution-info");
                    var $solutionTitle = $("<div>").html(article[key]['title']);
                    var $solutionSummary = $("<div>").html(article[key]['summary']);
                    var $solutionContent = $("<div>").html((article[key]['content']).replace(/<\/?(img|a)[^>]*>/gi, '').substr(0, 90));
                    var $solutionUrl = $("<a>").html("了解详情").attr("href", 'detail?id=' + article[key]["id"]);
                    var $solutionIcon = $("<span>").addClass("know-icon");
                    $solutionItem.append($solutionImg);
                    $solutionRight.append($solutionTitle);
                    $solutionRight.append($solutionSummary);
                    $solutionRight.append($solutionContent);
                    $solutionUrl.append($solutionIcon);
                    $solutionRight.append($solutionUrl);
                    $solutionItem.append($solutionRight);
                    $(".solution-container").append($solutionItem);
                }
            }
        })
    })
    $(".solution-tab div:first").trigger('click');

    $('#marquee1').kxbdMarquee({
      direction: 'left'
    });

});
</script>