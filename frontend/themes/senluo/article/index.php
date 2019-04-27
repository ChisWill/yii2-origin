<?php use frontend\models\Article; ?>
<?php use frontend\models\ArticleMenu; ?>
<?php use frontend\models\Picture; ?>
<?php $this->regCss('index') ?>
<!-- 内容 -->
<div class="content">
    <!-- 轮播图 -->
    <div class="carousel swiper-container">
        <div class="swiper-wrapper">
        <?php foreach (Picture::getSwipers() as $picture): ?>
            <img class="swiper-slide" src="<?= $picture['path'] ?>">
        <?php endforeach ?>
        </div>
        <div class="swiper-pagination swiper-pagination-white"></div>
    </div>
    <!-- 产品服务 -->
    <div class="server">
        <div class="sub-title">
            <img class="wow rubberBand animated" src="<?= img('server-title.png') ?>">
        </div>
        <div class="product-content clearfix">
            <div class="product-item fl wow rotateIn animated"></div>
            <div class="product-item fl wow rotateIn animated"></div>
            <div class="product-item fl wow rotateIn animated"></div>
            <div class="product-item fl wow rotateIn animated"></div>
            <div class="product-item fl wow rotateIn animated"></div>
        </div>
    </div>
    <!-- 案例展示 -->
    <div class="show">
        <div class="sub-title show-title">
            <img class="fadeInDownBig animated wow" src="<?= img('show-title.png') ?>">
        </div>
        <div class="show-tab">
            <h1 class="active" data-tab="all-tab">全部</h1>
            <h1 data-tab="pc-tab">网站</h1>
            <h1 data-tab="phone-tab">移动端</h1>
            <h1 data-tab="program-tab">小程序</h1>
        </div>
        <div class="show-content">
            <a href="<?= url('case') ?>" class="right_btn"></a>
            <div id="all-tab" class="tab-container clearfix">
            <?php foreach (Article::getAllArticleQuery('case-all')->limit(6)->asArray()->all() as $article): ?>
                <div class="show-item wow zoomIn animated" href="<?= url(['detail', 'id' => $article['id']]) ?>">
                    <img src="<?= $article['cover'] ?>" alt="<?= config('web_name') . '-网站开发' ?>">
                    <figcaption>
                        <p><?= $article['title'] ?></p>
                        <i>View more</i>
                        <a href="<?= url(['detail', 'id' => $article['id']]) ?>" title="<?= $article['title'] ?>"></a>
                    </figcaption>
                </div>
            <?php endforeach ?>
            </div>
            <div id="pc-tab" class="tab-container clearfix hide">
            <?php foreach (Article::getArticleQuery('case-web')->limit(6)->asArray()->all() as $article): ?>
                <div class="show-item" href="<?= url(['detail', 'id' => $article['id']]) ?>">
                    <img src="<?= $article['cover'] ?>" alt="<?= config('web_name') . '-网站建设' ?>">
                    <figcaption>
                        <p><?= $article['title'] ?></p>
                        <i>View more</i>
                        <a href="<?= url(['detail', 'id' => $article['id']]) ?>" title="<?= $article['title'] ?>"></a>
                    </figcaption>
                </div>
            <?php endforeach ?>
            </div>
            <div id="phone-tab" class="tab-container clearfix hide">
            <?php foreach (Article::getArticleQuery('case-phone')->limit(6)->asArray()->all() as $article): ?>
                <div class="show-item" href="<?= url(['detail', 'id' => $article['id']]) ?>">
                    <img src="<?= $article['cover'] ?>" alt="<?= config('web_name') . '-移动端开发' ?>">
                    <figcaption>
                        <p><?= $article['title'] ?></p>
                        <i>View more</i>
                        <a href="<?= url(['detail', 'id' => $article['id']]) ?>"></a>
                    </figcaption>
                </div>
            <?php endforeach ?>
            </div>
            <div id="program-tab" class="tab-container clearfix hide">
            <?php foreach (Article::getArticleQuery('case-mini')->limit(6)->asArray()->all() as $article): ?>
                <div class="show-item" href="<?= url(['detail', 'id' => $article['id']]) ?>">
                    <img src="<?= $article['cover'] ?>" alt="<?= config('web_name') . '-微信小程序开发' ?>">
                    <figcaption>
                        <p><?= $article['title'] ?></p>
                        <i>View more</i>
                        <a href="<?= url(['detail', 'id' => $article['id']]) ?>" title="<?= $article['title'] ?>"></a>
                    </figcaption>
                </div>
            <?php endforeach ?>
            </div>
        </div>
    </div>
    <!-- 合作流程 -->
    <div class="process">
        <div class="sub-title wow fadeInUpBig animated">
            <img src="<?= img('process-title.png') ?>">
        </div>
        <div class="process-content clearfix">
            <div class="process-top clearfix">
                <div class="step1 step"><div class="step1-icon step-icon"></div></div>
                <div class="arrow-right arrow"><img src="<?= img('arrow-right.png') ?>"></div>
                <div class="step2 step"><div class="step2-icon step-icon"></div></div>
                <div class="arrow-right arrow"><img src="<?= img('arrow-right.png') ?>"></div>
                <div class="step3 step"><div class="step3-icon step-icon"></div></div>
                <div class="arrow-right arrow"><img src="<?= img('arrow-right.png') ?>"></div>
                <div class="step4 step"><div class="step4-icon step-icon"></div></div>
            </div>
            <div class="arrow-bottom"><img src="<?= img('arrow-bottom.png') ?>"></div>
            <div class="process-bottom clearfix">
                <div class="step5 step"><div class="step5-icon step-icon"></div></div>
                <div class="arrow-left arrow"><img src="<?= img('arrow-left.png') ?>"></div>
                <div class="step6 step"><div class="step6-icon step-icon"></div></div>
                <div class="arrow-left arrow"><img src="<?= img('arrow-left.png') ?>"></div>
                <div class="step7 step"><div class="step7-icon step-icon"></div></div>
                <div class="arrow-left arrow"><img src="<?= img('arrow-left.png') ?>"></div>
                <div class="step8 step"><div class="step8-icon step-icon"></div></div>
            </div>
            
        </div>
    </div>
    <!-- 关于我们 -->
    <div class="aboutus">
        <div class="sub-title wow fadeInDownBig animated">
            <img src="<?= img('aboutus-title.png') ?>">
        </div>
        <div class="aboutus-content clearfix">
            <div class="aboutus-left fl">
                <div class="aboutus-left-item active" data-tab="data-bg"></div>
                <div class="aboutus-left-item" data-tab="data-culture"></div>
                <div class="aboutus-left-item" data-tab="data-idea"></div>
            </div>
            <a class="aboutus-right fr text-center" href="<?= url(['article/about']) ?>" title="关于我们">
                <?php foreach(Article::getArticleQuery('index-about1')->limit(1)->asArray()->all() as $article): ?>
                <div id="data-bg" class="wow zoomIn animated">
                    <?= strip_tags($article['summary']) ?> 
                </div>
                <?php endforeach ?>

                <?php foreach(Article::getArticleQuery('index-about2')->limit(1)->asArray()->all() as $article): ?>
                <div id="data-culture" class="hide wow animated zoomIn">
                    <?= strip_tags($article['summary']) ?>
                </div>
                <?php endforeach ?>

                <?php foreach(Article::getArticleQuery('index-about3')->limit(1)->asArray()->all() as $article): ?>
                <div id="data-idea" class="hide wow animated zoomIn">
                    <?= strip_tags($article['summary']) ?>
                </div>
                <?php endforeach ?>
            </a>
        </div>
    </div>
    <!-- 新闻资讯 -->
    <div class="news">
        <div class="sub-title wow fadeInDownBig animated">
            <img src="<?= img('news-title.png') ?>">
        </div>
        <div class="news-content clearfix">
            <div class="news-left fl wow pulse animated">
                <div class="top-ad"></div>
                <div class="bottom-content">
                    <?php foreach (Article::getAllArticleQuery('index-news')->limit(5)->asArray()->all() as $article): ?>
                    <a class="news-item clearfix" href="<?= url(['detail', 'id' => $article['id']]) ?>">
                        <img src="<?= img('index-tri.png') ?>">
                        <span class="time"><?= substr($article['created_at'], 0, 10)?></span>
                        <span><?= $article['title'] ?></span>
                        <img src="<?= img('index-arrow.png') ?>">
                    </a>
                    <?php endforeach ?>
                </div>
            </div>
            <div class="news-right fr wow lightSpeedIn animated">
                <a class="know-more" href="<?= url(['index-news', 'type' => 'index-senluo']) ?>"></a>
                <?php foreach (Article::getAllArticleQuery('index-news')->limit(1)->asArray()->all() as $article): ?>
                <a class="newest-item" href="<?= url(['detail', 'id' => $article['id']]) ?>">
                    <div class="newest-title"><?= $article['title'] ?></div>
                    <div class="newsest-img" style="background: url(<?= $article['cover'] ?>) center center no-repeat;background-size: cover;"></div>
                </a>
                <?php endforeach ?>
            </div>
        </div>
    </div>
    <!-- 移动端新闻资讯 -->
    <div class="mobile-news">
        <img class="mobile-news-title" src="<?= img('news-title.png') ?>">
        <div class="mobile-news-wrap">
            <?php foreach (Article::getAllArticleQuery('index-news')->limit(5)->asArray()->all() as $article): ?>
            <a class="mobile-news-item wow lightSpeedIn animated" href="<?= url(['detail', 'id' => $article['id']]) ?>">
                <img class="news-item-cover" src="<?= $article['cover'] ?>">
                <div class="mobile-news-info">
                    <div class="news-item-title"><?= $article['title'] ?></div>
                    <div class="news-item-summary"><?= $article['summary'] ?></div>
                    <div class="news-item-time"><img class="mobile-time" src="<?= img('mobile-time.png') ?>" /><?= $article['created_at'] ?></div>
                </div>
            </a>
            <?php endforeach ?>
            <a class="mobile-news-more" href="<?= url(['index-news', 'type' => 'index-senluo']) ?>">查看更多</a>
        </div>
    </div>
</div>
<script>
$(function () {
    // 轮播图设置
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        paginationClickable: '.swiper-pagination',
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        spaceBetween: 30,
        effect: 'fade',
        autoplay: 2000,
        observer: true,
        observeParents: true
    });
    // 案例展示tab切换, 关于我们tab切换
    $(".show-tab h1, .aboutus-left div").click(function() {
        $(this).addClass('active').siblings().removeClass('active');
        $("#" + $(this).data('tab')).show().siblings().hide();
    })

    // 判断流程是否到达可视区域
    var timer;
    $(window).scroll(function() {
        clearInterval(timer);
        var processTop = $(".process-content").offset().top;
        if (processTop >= $(window).scrollTop() && processTop < ($(window).scrollTop() + $(window).height())) {
            for (var i = 0; i < $('.step').length; i++) {
                var allSrc = $(".step").eq(i).find('.step-icon').css('background-image');
                if (allSrc.search('active') != -1) {
                    var newallSrc = allSrc.replace('-active', '');
                } else {
                    var newallSrc = allSrc;
                }
                $(".step").eq(i).find('.step-icon').css('background-image', newallSrc);
            };
            var i = 0;
            timer = setInterval(function() {
                var bgStr = $(".step").eq(i).find('.step-icon').css('background-image');
                if (bgStr.search('active') != -1) {
                    var newStr = bgStr;
                } else {
                    var newStr = bgStr.substr(0, bgStr.length - 6) + '-active.png")';
                }
                var prevStr =  $(".step").eq(i - 1).find('.step-icon').css('background-image');
                if (prevStr.search('active') != -1) {
                    var prevNewStr = prevStr.replace('-active', '');
                } else {
                    var prevNewStr = prevStr;
                }
                $(".step").eq(i - 1).find('.step-icon').css('background-image', prevNewStr);
                $(".step").eq(i).find('.step-icon').css('background-image', newStr);
                if (i >= $('.step').length - 1) {
                    i = 0;
                } else {
                    i++;
                }
            }, 1000)
        } else {
            clearInterval(timer);
        }
    })
    
  
});
</script>