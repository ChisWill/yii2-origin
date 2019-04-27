<?php use frontend\models\Article; ?>
<?php $this->regCss('aboutus') ?>
<?php use yii\widgets\Breadcrumbs; ?>
<?php $this->regJs('slider') ?> 
<!-- 内容 -->
<div class="content">
    <img class="aboutusCenter" src="<?= img('aboutus-img.jpg') ?>">
    <div class="aboutus">
        <div class="crumbs">
            <div class="main-crumbs">
                <span>您的位置:</span>
                <?= Breadcrumbs::widget(['itemTemplate' => "<li>{link}＞</li>\n", 'links' => $this->context->links]) ?>
            </div>
        </div>
        <div class="aboutus-content">
            <div class="con-top">
                <div><span>关于</span>我们</div>
                <div>ABOUT US</div>
                <img src="<?= img('senluo_03.png') ?>" alt="<?= config('web_name') . '-关于我们' ?>">
            </div>
            <div class="con-bottom">
                <div>
                    <img src="<?= img('5_03.png') ?>">
                    <img src="<?= img('5_05.png') ?>">
                </div>
                <div>
                    <div class="company">公司背景</div>
                     <?php foreach(Article::getArticleQuery('index-about1')->limit(1)->asArray()->all() as $article): ?>
                    <div class="introduce"><?= strip_tags($article['content']) ?></div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>        
    </div>
    <div class="idea" style="background-image: url(<?= img('background_03.png') ?>)">
        <div class="idea-content">
            <div class="idea-title">公司理念</div>
            <?php foreach(Article::getArticleQuery('index-about3')->limit(1)->asArray()->all() as $article): ?>
            <div class="idea-intro"><?= strip_tags($article['content']) ?></div>
            <?php endforeach ?>
        </div>
    </div>
    <div class="our-culture">
        <div>
            <span>企业文化 &nbsp;/</span>
            <span>THE  ENTERPRISE CULTURE</span>
        </div>
        <div class="culture-box">
            <?php foreach(Article::getArticleQuery('index-about2')->limit(1)->asArray()->all() as $article): ?>
            <div class="culture-content"><?= strip_tags($article['content']) ?></div>
            <?php endforeach ?>
            <img src="<?= img('road_03.png') ?>">
        </div>
        <div class="culture-foot">
            <div>
                <img src="<?= img('aboutme_03.png') ?>" alt="<?= config('web_name') . '-网站建设，网站定制，微信小程序' ?>">
                <h5>专业</h5>
            </div>
            <div class="active">/</div>
            <div>
                <img src="<?= img('aboutme_05.png') ?>" alt="<?= config('web_name') . '-网站建设，网站定制，微信小程序' ?>">
                <h5>经验</h5>
            </div>
            <div class="active">/</div>
             <div>
                <img src="<?= img('aboutme_07.png') ?>" alt="<?= config('web_name') . '-网站建设，网站定制，微信小程序' ?>">
                <h5>创意</h5>
            </div>
        </div>
    </div>
    <div class="our-team">
        <div>
            <span>我们的</span>团队
        </div>
        <img src="<?= img('senluo_03.png') ?>">
        <div>
            <div class="active"></div>
            <div>OUR COOPERATION</div>
            <div class="active"></div>
        </div>
    </div>
    <div id="slider">
        <?php foreach (Article::getArticleQuery('about-team')->asArray()->all() as $article): ?>
        <div class="spic">
            <img src="<?= img('zoom.png') ?>">
            <div class="spic-back"><img src="<?= $article['cover'] ?>" /></div>
            <a href="javascript:;"><?= $article['title'] ?></a>
            <a href="javascript:;"><?= $article['summary'] ?></a>
            <a href="javascript:;" style="opacity: 0; height: 0px;"><?= $article['content'] ?></a>
        </div>
        <?php endforeach ?>
    </div> 
</div>

<script>
// $(function () {
//     var index = 0;
//     $(".rightAble img").click(function() {
//         index++;
//         var len = $("ul.mobile li").length;
//         if (index + 5 > len) {
//             $(".member ul.mobile").stop().append($("ul.mobile").html());
//         }
//         $(".member ul.mobile").stop().animate({left: -index * 4.35 + 'rem'}, 1000);
//     });

//     $(".leftAble img").click(function() {
//         if (index == 0) {
//             $("ul.mobile").prepend($("ul.mobile").html());
//             $("ul.mobile").css("left", "-1380px");
//             index = 3;
//         }
//         index--;
//         $(".member ul.mobile").stop().animate({left: -index * 4.35 + 'rem'}, 1000);
//     });
// });
// $(document).ready(function() {
//     $('#slider').slider({ speed: 500 });
// }); 
$(document).ready(function() {
    $('#slider').slider({ speed: 500 });
});  
</script>