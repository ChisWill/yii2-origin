<?php use yii\widgets\Breadcrumbs; ?> 
<?php $this->regCss('technology') ?>
<?php $this->regJs('filed') ?>
<!-- 内容 -->
<div class="content">
    <img class="technology-img wow pulse animated" src="<?= img("technology-img.jpg") ?>">
    <div class="crumbs">
        <div class="main-crumbs">
            <span>您的位置:</span>
            <?= Breadcrumbs::widget(['itemTemplate' => "<li>{link}＞</li>\n", 'links' => $this->context->links]) ?>
        </div>
    </div>
    <img class="filed-title wow bounceInLeft animated" src="<?= img('filed-title.jpg') ?>">
    <div class="filed" id="certify">
        <div class="swiper-container wow swing animated">
            <div class="swiper-wrapper">
                <div class="swiper-slide"><img src="<?= img('filed3-active.jpg') ?>" alt="网站"></div>
                <div class="swiper-slide"><img src="<?= img('filed2.jpg') ?>" alt="微信"></div>
                <div class="swiper-slide"><img src="<?= img('filed1.jpg') ?>" alt="接口"></div>
                <div class="swiper-slide"><img src="<?= img('filed4.jpg') ?>" alt="金融"></div>
            </div>
        </div>
        <div class="swiper-button-next"></div>
    </div>
    <div class="area1 area">
        <img class="technology-area wow pulse animated" src="<?= img('technology1.jpg') ?>" alt="<?= config('web_name') . '-PC/网站' ?>">
        <a class="case-link" href="<?= url(['case']) ?>">查看案例</a>
    </div>
    <div class="area2 area">
        <img class="technology-area wow pulse animated" src="<?= img('technology2.jpg') ?>" alt="<?= config('web_name') . '-微信小程序' ?>">
        <a class="case-link" href="<?= url(['case']) ?>">查看案例</a>
    </div>
    <div class="area3 area">
        <img class="technology-area wow pulse animated" src="<?= img('technology3.jpg') ?>" alt="<?= config('web_name') . '-支付系统' ?>">
        <a class="case-link" href="<?= url(['case']) ?>">查看案例</a>
    </div>
    <div class="area4 area">
        <img class="technology-area wow pulse animated" src="<?= img('technology4.jpg') ?>" alt="<?= config('web_name') . '-金融系统' ?>">
        <a class="case-link" href="<?= url(['case']) ?>">查看案例</a>
    </div>
</div>
<script>
$(function () {
    var certifySwiper = new Swiper('#certify .swiper-container', {
        watchSlidesProgress: true,
        slidesPerView: 'auto',
        centeredSlides: true,
        loop: true,
        loopedSlides: 5,
        autoplay: false,
        observer:true,
        observeParents:true,
        navigation: {
            nextEl: '.swiper-button-next',
        },
        pagination: {
            el: '.swiper-pagination',
        },
        on: {
            progress: function(progress) {
                for (i = 0; i < this.slides.length; i++) {
                    var slide = this.slides.eq(i);
                    var slideProgress = this.slides[i].progress;
                    modify = 1;
                    if (Math.abs(slideProgress) > 1) {
                        modify = (Math.abs(slideProgress) - 1) * 0.3 + 1;
                    }
                    translate = slideProgress * modify * 260 / 100 + 'rem';
                    scale = 1 - Math.abs(slideProgress) / 5;
                    zIndex = 999 - Math.abs(Math.round(10 * slideProgress));
                    slide.transform('translateX(' + translate + ') scale(' + scale + ')');
                    slide.css('zIndex', zIndex);
                    slide.css('opacity', 1);
                    if (Math.abs(slideProgress) > 3) {
                        slide.css('opacity', 0);
                    }
                }
            },
            setTransition: function(transition) {
                for (var i = 0; i < this.slides.length; i++) {
                    var slide = this.slides.eq(i)
                    slide.transition(transition);
                }
            }
        }
    })

    $(".swiper-button-next").click(function() {
        var prevStr = $(".swiper-slide-prev").find("img").attr('src');
        var nextStr = $(".swiper-slide-next").find("img").attr('src');
        if ((prevStr.indexOf('active') != -1) || (nextStr.indexOf('active') != -1)) {
            var newPrevStr = prevStr.replace('-active', '');
            var newNextStr = nextStr.replace('-active', '');
        } else {
            var newPrevStr = prevStr;
            var newNextStr = nextStr;
        }
        $(".swiper-slide-prev").find("img").attr('src', newPrevStr);
        $(".swiper-slide-next").find("img").attr('src', newNextStr);

        var src = $(".swiper-slide-active").find('img').attr('src');
        if (src.indexOf('active') != -1) {
            var newStr = src;
        } else {
            var newStr = src.substr(0, src.length - 4) + '-active.jpg';
        }
        $(".swiper-slide-active").find("img").attr('src', newStr);
    });
});
</script>