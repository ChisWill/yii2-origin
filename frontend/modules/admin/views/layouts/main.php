<?php use common\helpers\Html; ?>
<?php admin\assets\MainAsset::register($this) ?>
<?php self::offEvent(['debug']) ?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<header class="navbar-wrapper">
    <div class="navbar navbar-fixed-top">
        <div class="container-fluid cl hidden-xs">
            <a class="logo navbar-logo f-l mr-10" href=""><?= config('web_name') ?>后台管理系统</a>
            <ul class="shortcut-menu mr-10 fr mr-10 hidden-xs">
                <li><a href="<?= url(['/']) ?>" data-title="网站首页" target="_blank"><i class="Hui-iconfont">&#xe67f;</i></a></li>
                <li><a href="javascript:;" onclick="Hui_admin_tab(this)" _href="<?= url(['system/setting']) ?>" data-title="系统设置"><i class="Hui-iconfont">&#xe61d;</i></a></li>
                <li><a href="<?= url(['site/logout']) ?>"><img src="/images/logout.png">退出登录</a></li>
            </ul>
        </div>
        <div class="container-fluid cl visible-xs">
            <a aria-hidden="false" class="nav-toggle Hui-iconfont f-l" href="javascript:;"></a>
            <a class="web-name-m fl" href="javascript:;"><?= config('web_name') ?></a>
            <a class="f-l logout-icon" href="<?= url(['site/logout']) ?>"></a>
        </div>
    </div>
</header>
<aside class="Hui-aside menu-aside">
    <div class="admin-logo clearfix">
        <a class="logo-set" href="javascript:;" onclick="Hui_admin_tab(this)" _href="<?= url(['system/setting']) ?>" data-title="系统设置"><img class="fl logo-extra" src="<?= config('back_logo') ? :  '/images/default-logo.png' ?>"></a>
        <div class="fl logo-right">
            <div><?= u()->username ?></div>
            <a href="javascript:;" data-title="个人信息" onclick="Hui_admin_tab(this)" _href="<?= url(['site/profile']) ?>">编辑资料</a>
        </div>
    </div>
    <input runat="server" id="divScrollValue" type="hidden" value="" />
    <div class="menu_dropdown bk_2 menuList">
    <?php $menuData = admin\models\AdminMenu::showMenu() ?>
    <?php foreach ($menuData as $parent): ?>
        <?php if ($parent['pid'] == 0): ?>
            <?php
                $html = '';
                foreach ($menuData as $child) {
                    if ($child['pid'] == $parent['id'] && u()->can($child['url'])) {
                        $html .= '<li><a _href="' . url($child['url']) . '" data-title="' . $child['name'] . '" href="javascript:;">' . $child['name'] . '</a></li>';
                    }
                }
                if (!$html) {
                    continue;
                }
            ?>
        <dl>
            <dt><?= $parent['icon'] ?> <span><?= $parent['name'] ?></span><i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
            <dd>
                <ul><?= $html ?></ul>
            </dd>
        </dl>
        <?php endif ?>
    <?php endforeach ?>
    </div>
</aside>

<div class="dislpayArrow hidden-xs"><a class="pngfix" href="javascript:;" onClick="displaynavbar(this)"></a></div>

<section class="Hui-article-box">
    <div id="Hui-tabNav" class="Hui-tabNav hidden-xs">
        <div class="Hui-tabNav-wp">
            <ul id="min_title_list" class="acrossTab cl">
                <li class="active"><span title="我的桌面" data-href="<?= url(['welcome']) ?>">我的桌面</span><em></em></li>
            </ul>
        </div>
        <div class="Hui-tabNav-more btn-group">
            <a id="js-tabNav-prev" class="btn radius btn-default size-S" href="javascript:;">
                <i class="Hui-iconfont">&#xe6d4;</i>
            </a>
            <a id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;">
                <i class="Hui-iconfont">&#xe6d7;</i>
            </a>
        </div>
    </div>
    <div id="iframe_box" class="Hui-article">
        <div class="show_iframe">
            <div style="display:none" class="loading"></div>
            <iframe scrolling="yes" frameborder="0" src="<?= url(['welcome']) ?>" data-maintitle="控制面板" data-subtitle="我的桌面"></iframe>
        </div>
    </div>
</section>
<?php $this->endBody() ?>
<script>
$(function () {
    var selectMenu = function () {
        var title = $(this).children("span").html() || $(this).find('a').data('title') || $(this).data('title');
        var now = $(".Hui-aside .menu_dropdown dd .active").parent("ul").children('li').children("a[data-title=" + title + "]").html();
        if (!now) {
            $(".Hui-aside .menu_dropdown dd .active").parent("ul").parent("dd").css("display", "none");
            $(".Hui-aside .menu_dropdown dd .active").parent("ul").parent("dd").prev().removeClass('selected');
            $(".Hui-aside .menu_dropdown dd li a[data-title=" + title + "]").parent("li").parent("ul").parent("dd").css("display", "block");
            $(".Hui-aside .menu_dropdown dd li a[data-title=" + title + "]").parent("li").parent("ul").parent("dd").prev().addClass('selected');
            $(".Hui-aside .menu_dropdown dl").removeClass("open active");
            $(".Hui-aside .menu_dropdown dd li a[data-title=" + title + "]").parent("li").parent("ul").parent("dd").parent("dl").addClass("open active");
        }
        $(".Hui-aside .menu_dropdown dd ul li").removeClass('active');
        $(".Hui-aside .menu_dropdown dd li a[data-title=" + title + "]").parent("li").addClass("active");
        // 快捷键
        $(".shortcut-menu li a").click(function() {
            Hui_admin_tab(this);
        });
    }
    // 左边子元素
    $(".Hui-aside .menu_dropdown dd ul li").click(function () {
        $(".Hui-aside .menu_dropdown dd ul li").removeClass("active");
        $(".Hui-aside .menu_dropdown dl").removeClass("active");
        $(this).addClass('active');
        $(this).parent("ul").parent("dd").parent("dl").addClass('active');
    })
    // tab
    $("#Hui-tabNav").on("click", "#min_title_list li", selectMenu);
    $(".shortcut-menu").on("click", "li", selectMenu);
    $(".admin-logo").on('click', 'a', selectMenu);
});
</script>
</body>
</html>
<?php $this->endPage() ?>