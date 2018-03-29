<?php use common\modules\manual\models\Menu; ?>
<?php \common\modules\manual\assets\ManualViewAsset::register($this) ?>

<div class="main-contanier">
    <div class="relative-container">
        <div class="manual-head">
            <div class="left">
                <a data-hint="进入编辑模式" href="<?= self::createUrl(['site/edit']) ?>" class="back hint--bottom"> <i></i>
                </a>
            </div>
        </div>
        <div class="main">
            <div class="relative-container">
                <div class="content">
                    <div class="con-left">
                        <div class="relative-container">
                            <div class="cl-navg">
                                <span class="navg-item active" data-mode="index">目录</span>
                                <span class="navg-item" data-mode="search">搜索</span>
                                <span class="navg-item" data-mode="collect">收藏</span>
                            </div>
                            <div class="tab-wrap">
                                <div class="tab-item active" data-mode="index">
                                    <div class="js-tree view">
                                        <?= Menu::getManualMenu(Menu::getMenuData()) ?>
                                    </div>
                                </div>
                                <div class="tab-item" data-mode="search">
                                    <div class="search-inner">
                                        <?= $this->render('_searchForm') ?>
                                        <div class="search-result">
                                            <div class="search-empty">
                                                <i class="image"></i>
                                                <b class="text">暂无相关搜索结果！</b>
                                            </div>
                                            <ul class="search-list ul-list">
                                                
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-item" data-mode="collect">
                                    <div class="collect-inner">
                                        <div class="collect-result">
                                            <div class="collect-empty">
                                                <i class="image"></i>
                                                <b class="text">暂无相关收藏内容！</b>
                                            </div>
                                            <ul class="collect-list ul-list">
                                                
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="con-right">
                        <div class="m-article">
                            <div class="article-head">
                                <div class="head-tool">
                                    <div class="item"></div>
                                </div>
                                <div class="head-util">
                                    <a title="收藏" class="collect-item">
                                        <i class="icon icon-heart"></i>
                                        <b class="text">收藏</b>
                                    </a>
                                </div>
                                <h1 id="articleTitle"></h1>
                            </div>
                            <div class="article-wrap">
                                <div class="article-view">
                                    <div class="view-body"></div>
                                    <div class="view-foot">
                                        <div class="article-jump">
                                            <span class="jump-up">
                                                上一篇：
                                                <a class="jump-up-link" href="javascript:;"></a>
                                            </span>
                                            <span class="jump-down">
                                                下一篇：
                                                <a class="jump-up-link" href="javascript:;"></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>