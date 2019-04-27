<?php use frontend\models\Article; ?>
<?php use yii\widgets\Breadcrumbs; ?>
<?php $this->regCss('server') ?>
<?php $this->regCss('contactus') ?>
<!-- 内容 -->
<div class="content">
    <img class="server-img wow pulse animated" src="<?= img("server-img.png") ?>" alt="常见问题解答">
    <div class="crumbs">
        <div class="main-crumbs">
            <span>您的位置:</span>
            <?= Breadcrumbs::widget(['itemTemplate' => "<li>{link}＞</li>\n", 'links' => $this->context->links]) ?>
        </div>
    </div>
    <!-- 常见问题解答 -->
    <div class="question">
        <div class="question-title wow fadeInDownBig animated">
             <img src="<?= img('question-title.png') ?>">
        </div>
        <img class="question-img wow bounceIn animated" src="<?= img('question-img.png') ?>" alt="模板建站，快速无忧">
        <div class="question-content clearfix mainmenu">
        <?php foreach (Article::getArticleQuery('service-qa')->asArray()->all() as $article): ?>
            <div class="question-item wow lightSpeedIn animated">
                <div class="item-title">
                    <span class="add-icon"></span>
                    <span><?= $article['title'] ?></span>
                </div>
                <div class="item-content submenu"><?= strip_tags($article['content']) ?></div>
            </div>
        <?php endforeach ?>
        </div>
        <a class="contact-btn" target="_blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=<?= config('server_qq', '1205763462') ?>&amp;site=qq&amp;menu=yes">解决不了联系我们</a>
    </div>
    <!-- 在线咨询 -->
    <div class="consult">
        <div class="sub-title wow fadeInDownBig animated">
             <img src="<?= img('consult-title.png') ?>">
        </div>
        <div class="consult-content">
            <div class="wx-server clearfix">
                <div class="fl server-left wow bounceInLeft animated">
                    <div class="consult-title"><img class="consult-icon" src="<?= img('phone-icon.png') ?>">移动客服</div>
                    <ul class="consult-list">
                        <li>服务时间，周一至周日 9:30-19:00</li>
                        <li>关注微信， 不但可以快速联系客服， 还可以更便捷的管理账号和订单。</li>
                        <li>咨询服务时间，周一至周日 9:30-19:00 <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=<?= config('server_qq', '1205763462') ?>&amp;site=qq&amp;menu=yes">点击咨询</a></li>
                        <li>温馨提示：有账号或订单的客户，在咨询前若能提前准备一下账号或订单信息，客户帮助客服更快的定位和解决问题</li>
                    </ul>
                </div>
                <div class="fr wx-code wow bounceInRight animated">
                    <img src="<?= config('web_wx') ?>">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(function () {
    var $submenu = $('.submenu');
    var $mainmenu = $('.mainmenu');
    $mainmenu.on('click', '.item-title', function() {
        $(this).next('.submenu').slideToggle().parent().siblings().find('.submenu').slideUp();
    });
});
</script>