<?php use frontend\models\Form; ?>
<?php use frontend\models\Article; ?>
<?php use frontend\models\ArticleMenu; ?>
<?php use yii\widgets\Breadcrumbs; ?>
<?php $this->regCss('contactus') ?>
<!-- <?php null//$this->regJs('jquery.baiduMap.min') ?> -->

<!-- 内容 -->
<div class="content">
    <div class="contact-content">
        <img class="wow pulse animated contact-img" src="<?= img('contact-img.jpg') ?>">
        <div class="crumbs">
            <div class="main-crumbs">
                <span>您的位置:</span>
                <?= Breadcrumbs::widget(['itemTemplate' => "<li>{link}＞</li>\n", 'links' => $this->context->links]) ?>
            </div>
        </div>
        <div class="contact-info clearfix">
            <div class="contact-left fl wow bounceInLeft animated">
                <img class="contact-en" src="<?= img('contact-en.jpg') ?>">
                <p>准备好开始了吗?那就联系我们吧</p>
                <div class="contact-tip">
                    有需求想和我们谈谈吗？您可以填写右侧的表格，让我们更多的了解您的需求，这是一个良好的开始，我们将会第一时间与您取得联系。
                </div>
                <div class="info-item clearfix">
                    <img class="icon fl" src="<?= img('address.jpg') ?>">
                    <span class="fr">地址：<?= config('web_address') ?></span>
                </div>
                <div class="info-item clearfix">
                    <img class="icon fl" src="<?= img('phone.jpg') ?>">
                    <span class="fr">电话：<?= config('web_phone') ?></span>
                </div>
            </div>
            <div class="contact-right fr wow bounceInRight animated">
                <h2>客户留言</h2>
                <div class="tip">提交您的业务需求，我们的客户经理将及时与您取得联系</div>
                <?php $form = self::beginForm(['id' => 'messageForm', 'action' => url(['submit'])]) ?>
                <div class="form-wrap">
                    <div class="input-text">
                        <label>姓名</label>
                        <div class="form-group">
                            <input type="text" value="" name="Form[realname]" />
                        </div>
                    </div>
                    <div class="input-text">
                        <label>公司名</label>
                        <div class="form-group">
                            <input type="text" value="" name="Form[company_name]" />
                        </div>
                    </div>
                    <div class="input-text">
                        <label>邮箱</label>
                        <div class="form-group">
                            <input type="text" value="" name="Form[email]" />
                        </div>
                    </div>
                    <div class="input-text">
                        <label>您的电话</label>
                        <div class="form-group">
                            <input type="text" value="" name="Form[tel]" />
                        </div>
                    </div>
                    <div class="input-text">
                        <label>需求</label>
                        <div class="form-group">
                            <select name="Form[requirement]">
                                <option value="网站建设">网站建设</option>
                                <option value="APP开发">APP开发</option>
                                <option value="微信开发">微信开发</option>
                                <option value="UI设计">UI设计</option>
                                <option value="微信小程序">微信小程序</option>
                            </select>
                        </div>
                    </div>
                    <div class="textarea-text clearfix">
                        <label class="fl">简述</label>
                        <div class="form-group">
                            <textarea name="Form[desc]"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="Form[type]" value="<?= Form::TYPE_MESSAGE ?>">
                    <div class="submit-btn" id="submitBtn">立即提交</div>
                </div>
                <?php self::endForm() ?>
            </div>
        </div>
        <div class="joinus-content">
            <img class="join-title" src="<?= img('joinus.jpg') ?>">
            <img class="title-img" src="<?= img('joinus-title.jpg') ?>">
            <div class="position-title">
            <?php $active = 'active' ?>
            <?php foreach (ArticleMenu::getSubMenus('contact-join') as $menu): ?>
                <span class="wow rotateIn animated <?= $active ?>" data-id="<?= $menu['id'] ?>"><?= $menu['name'] ?></span>
                <?php $active = ''; ?>
            <?php endforeach ?>
            </div>
            <div class="position-content wow swing animated">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <!-- <div class="swiper-slide">
                            <div class="position-title"></div>
                            <div class="position-text"></div>
                        </div> -->
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- 地图 -->
    <!-- <div class="location">
        <div class="location-title wow zoomIn animated"><?= null//config('web_address') ?></div>
        <div class="line"></div>
        <div class="map" id="mapContainer">
            
        </div>
    </div> -->
</div>
<script>
$(function () {
    $(".position-title span").click(function() {
        $(this).addClass("active").siblings().removeClass('active');
        var id = $(this).data("id");
        $(".position-content .swiper-wrapper").html("");
        $.get("<?= url(['article/data']) ?>", {id: id}, function (msg) {
            if (msg.state) {
                var article = msg.info;
                for(var key in article) {
                    var $slideEle = $('<div class="swiper-slide"><div class="position-title">'+ article[key]['title'] +'</div><div class="position-text">'+ article[key]['content'] +'</div></div>');
                    $(".position-content .swiper-wrapper").append($slideEle);
                }
            }
        })
    });
    $(".position-title span:first").trigger("click");
    $("#submitBtn").click(function () {
        var errors = [];
        $("#messageForm").find("[name]").each(function () {
            if (!$(this).val()) {
                errors.push($(this).parent().prev().html() + "必填");
            }
        });
        if (errors.length > 0) {
            $.alert(errors);
            return false;
        }
        $("#messageForm").ajaxSubmit($.config('ajaxSubmit', {
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

    // 轮播图设置
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        paginationClickable: '.swiper-pagination',
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        spaceBetween: 30,
        effect: 'fade',
        autoplay: false,
        observer:true,
        observeParents:true,
    });

    // new BaiduMap({
    //     id: "mapContainer",
    //     title: {
    //         text: "<?= config('web_company') ?>",
    //         className: "map-title"
    //     },
    //     content: {
    //         className: "map-content wow lightSpeedIn animated",
    //         text: ["<?= config('web_address') ?>", "电话：<?= config('service_tel') ?>"]
    //     },
    //     point: {
    //         lng: "108.8926",
    //         lat: "34.221324"
    //     },
    //     level: 15,
    //     zoom: true,
    //     type: ["地图", "卫星"],
    //     icon: {
    //         url: "<?= img('map-icon.png') ?>",
    //         width: 36,
    //         height: 36
    //     }
    // });
});
</script>