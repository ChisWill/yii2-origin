<?php use frontend\models\Form; ?>
<?php use frontend\models\Picture; ?>
<?php use frontend\models\ArticleMenu; ?>
<?php use common\helpers\Html; ?>
<?php frontend\assets\SenLuoAsset::register($this) ?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
    <meta name="baidu-site-verification" content="qoxBQw8Ndq" />
    <?= Html::csrfMetaTags() ?>
    <title><?= config('seo_title') ?></title>
    <?php if (config('seo_switch')): ?>
        <meta name="keywords" content="<?= config('seo_key') ?>">
        <meta name="description" itemprop="description" content="<?= config('seo_desc') ?>">
    <?php endif ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
    <!-- 头部 -->
    <header class="header">
        <div class="header-main clearfix">
            <div class="header-left fl">
                <img src="<?= config('web_logo') ?>" title="浙江森罗网络科技有限公司" alt="森罗网络-西安专业网站建设公司">
            </div>
            <div class="header-right fr">
                <nav class="navigator">
            <?php foreach (ArticleMenu::getMenus() as $action => $menu): ?>
                <?php if (ArticleMenu::getTopUrl($this->context->url) == $action): ?>
                    <?php $class = 'active' ?>
                <?php else: ?>
                    <?php $class = null ?>
                <?php endif ?>
                <?= Html::a($menu['name'], [$action], ['class' => $class]) ?>
            <?php endforeach ?>
                </nav>
            </div>
        </div>
    </header>
    <!-- 手机头部 -->
    <header class="mobile-header clearfix">
        <div class="mobile-header-left fl">
            <img src="<?= config('web_logo') ?>">
        </div>
        <div class="mobile-header-right fr menu-img">
            <img src="<?= img('menu.png') ?>">
        </div>
    </header>
    <!-- 手机头部侧边栏 -->
    <div class="mobile-slider">
        <div class="slider-wrap"></div>
        <div class="slider-close"></div>
        <div class="slider-right">
            <ul>
                <?php foreach (ArticleMenu::getMenus() as $action => $menu): ?>
                <li><?= Html::a($menu['name'], [$action]) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
    <?= $content ?>

    <!-- 尾部 -->
    <footer class="footer">
        <div class="footer-top clearfix">
            <div class="footer-left fl">
                <img class="footer-logo" src="<?= config('web_logo') ?>">
                <div class="footer-wx"><img src="<?= config('web_wx') ?>"><span>关注微信公众号了解更多消息</span></div>
                
            </div>
            <div class="footer-middle fl">
                <ul class="footer-nav clearfix">
                    <?php foreach(ArticleMenu::getMenus() as $menu): ?>
                    <li class="fl"><?= $menu['name'] ?></li>
                    <?php endforeach ?>
                </ul>
                <div class="nav-submenu">
                    <ul class="fl">
                    <?php foreach (ArticleMenu::getSubMenus('index') as $menu): ?>
                        <li><a href="<?= url(['article/index']) ?>"><?= $menu['name'] ?></a></li>
                    <?php endforeach ?>
                    </ul>

                    <ul class="fl">
                    <?php foreach (ArticleMenu::getSubMenus('tech') as $menu): ?>
                        <li><a href="<?= url(['article/tech']) ?>"><?= $menu['name'] ?></a></li>
                    <?php endforeach ?>
                    </ul>

                    <ul class="fl">
                    <?php foreach (ArticleMenu::getSubMenus('service') as $menu): ?>
                        <li><a href="<?= url(['article/service']) ?>"><?= $menu['name'] ?></a></li>
                    <?php endforeach ?>
                    </ul>

                    <ul class="fl">
                    <?php foreach (ArticleMenu::getSubMenus('case') as $menu): ?>
                        <li><a href="<?= url(['article/case']) ?>"><?= $menu['name'] ?></a></li>
                    <?php endforeach ?>
                    </ul>

                    <ul class="fl">
                    <?php foreach (ArticleMenu::getSubMenus('about') as $menu): ?>
                        <li><a href="<?= url(['article/about']) ?>"><?= $menu['name'] ?></a></li>
                    <?php endforeach ?>
                    </ul>

                    <ul class="fl">
                    <?php foreach (ArticleMenu::getSubMenus('contact-join') as $menu): ?>
                        <li><a href="<?= url(['article/contact']) ?>"><?= $menu['name'] ?></a></li>
                    <?php endforeach ?>
                    </ul>
                </div>
                
            </div>
            <div class="footer-right fl">
                <div>免费咨询</div>
                <img class="footer-line" src="<?= img('footer-line.png') ?>" />
                <div class="footer-phone"><?= config('web_phone', '029-8885-2954') ?></div>
                <div class="footer-time">周一至周日9:00-19:00</div>
                <div class="address-item">
                    地址：<?= config('web_address', '陕西省西安市雁塔区科技五路数字生活') ?>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <span>友情链接：</span>
            <?php foreach (Picture::getLinks() as $links) : ?>
            <a href="<?= $links['url'] ?>" target="_blank"><img src="<?= $links['path'] ?: '' ?>" /><?= $links['title'] ?></a>
            <?php endforeach ?>
        </div>
        <a class="copy-right" href="http://www.miitbeian.gov.cn"><?= config('web_copyright') ?></a>
    </footer>
    <footer class="mobile-footer">
        <div class="company-name">浙江森罗网络科技有限公司</div>
        <div>电话：<?= config('web_phone') ?></div>
        <div>地址：<?= config('web_address') ?></div>
        <div class="mobile-copyright"><?= config('web_copyright', '浙ICP备18051300号-1  ©2018senluokj.com版权所有  ') ?></div>
    </footer>
</div>
<!-- 侧边栏 -->
<div class="slidebar">
    <div class="slide_list">
        <div class="slidebar-qq slidebar-item">
            <span class="qq-icon"></span>
            <span class="item-text">QQ客服</span>
            <div class="hide-qq hide-item clearfix">QQ:<?= config('server_qq', '1205763462') ?></div>
        </div>
        <div class="slidebar-phone slidebar-item">
            <span class="phone-icon"></span>
            <span class="item-text">电话客服</span>
            <div class="hide-phone hide-item clearfix">电话：<?= config('web_phone', '029-8885-2954') ?></div>
        </div>
        <div class="slidebar-wx slidebar-item">
            <span class="wx-icon"></span>
            <span class="item-text">扫码关注</span>
            <div class="hide-wx hide-item">
                <img src="<?= config('server_wx') ?>"> 
                <!-- <img src="http://img4.imgtn.bdimg.com/it/u=736890948,136012678&fm=26&gp=0.jpg"> -->
            </div>
        </div>
        <div class="slidebar-feedback slidebar-item">
            <span class="feedback-icon"></span>
            <span class="item-text">获取报价</span>
        </div>
    </div>
    <!-- 向上滚动 -->
    <div class="slidebar-top">
        <img src="<?= img('up_03.png') ?>">
    </div>
</div>
<div class="mask"></div>
<?php $form = self::beginForm(['id' => 'demandForm', 'action' => url(['submit'])]) ?>
<div class="feedback-box">
    <div class="feedback-item clearfix">
        <img class="fl" src="<?= img("feedback-user.png") ?>">
        <input class="fl" type="text" name="Form[realname]" data-title="姓名" placeholder="姓名" />
    </div>
    <div class="feedback-item clearfix">
        <img class="fl" src="<?= img("feedback-phone.png") ?>">
        <input class="fl" type="text" name="Form[tel]" data-title="电话" placeholder="电话" />
    </div>
    <div class="feedback-item clearfix">
        <img class="fl" src="<?= img("feedback-content.png") ?>">
        <textarea class="fl" name="Form[desc]" data-title="需求" placeholder="需求"></textarea>
    </div>
    <input type="hidden" name="Form[type]" value="<?= Form::TYPE_MESSAGE ?>">
    <div class="submit-btn" id="demandBtn">提交</div>
    <div class="close-icon"></div>
</div>
<!-- loading -->
<div id="loading" class="loading">
    <div class="loader">
        <div class="loader-outter"></div>
        <div class="loader-inner"></div>
    </div>
</div>
<?php self::endForm() ?>
<script>
$(function () {
    if (!(/msie [6|7|8|9]/i.test(navigator.userAgent))) {
        var wow = new WOW({
            boxClass: 'wow',
            animateClass: 'animated',
            offset: 0,
            mobile: true,
            live: true
        });
        wow.init();
    };
    // 判断页面资源加载完成
    $(window).load(function() {
        $("#loading").css('opacity', "0");//当页面加载完成后将loading页隐藏
        $("#loading").css('zIndex', "-1");
    });

    $(".slidebar-feedback").click(function() {
        $(".feedback-box").show();
        $(".mask").show();
    });
    $(".close-icon").click(function() {
        $(".feedback-box").hide();
        $(".mask").hide();
    });

    $("#demandBtn").click(function () {
        var errors = [];
        $("#demandForm").find("[name]").each(function () {
            if (!$(this).val()) {
                errors.push($(this).data('title') + "必填");
            }
        });
        if (errors.length > 0) {
            $.alert(errors);
            return false;
        }
        $("#demandForm").ajaxSubmit($.config('ajaxSubmit', {
            success: function (msg) {
                if (msg.state) {
                    $.alert(msg.info || '感谢您的留言', function () {
                        location.reload();
                    });
                } else {
                    $.alert(msg.info);
                }
            }
        }));
        return false;
    });
    // 菜单展开
    $(".menu-img").click(function() {
        $(".mobile-slider").show();
        $(".slider-wrap").show();
        $(".mobile-slider .slider-wrap").delay(0).animate({opacity: '0.9'}, 0);
        $(".mobile-slider .slider-right").animate({right: '0'});
    });

    // 点击菜单关闭
    $(".slider-close").click(function() {
        $(".mobile-slider .slider-right").animate({right: '-80%'});
        $(".mobile-slider .slider-wrap").delay(500).animate({opacity: '0'}, 0);
        setTimeout(function() {
            $(".mobile-slider").hide();
        }, 500);
    });
      // 回到顶部
    $('.slidebar-top').click(function(){
        $('html,body').animate({
            scrollTop: '0px'
        }, 800);
    });
});
</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>