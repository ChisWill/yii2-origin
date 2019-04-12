<?php use common\modules\manual\models\Menu; ?>
<?php \common\modules\manual\assets\ManualEditAsset::register($this) ?>

<?php $form = self::beginForm(['id' => 'uploadForm', 'action' => self::createUrl(['site/uploadImage']), 'enctype' => 'multipart/form-data']) ?>
<input type="file" name="Upload[image]" id="uploadImage" style="display: none;" accept="image/png, image/jpeg">
<?php self::endForm() ?>

<div class="main-contanier">
    <div class="relative-container">
        <div class="manual-head">
            <div class="left">
                <a data-hint="返回阅读模式" href="<?= self::createUrl(['site/index']) ?>" class="back hint--bottom"> <i></i>
                </a>
            </div>
            <div class="saving-tip">文章保存中...</div>
        </div>
        <div class="main">
            <div class="relative-container">
                <div class="content">
                    <div class="con-left">
                        <div class="relative-container">
                            <div class="cl-navg">
                                <span class="navg-item active" data-mode="index">目录</span>
                            </div>
                            <div class="tab-util">
                                <span data-hint="创建目录" class="setting-edit item hint--right" id="addCatalogBtn"> <i>+</i>
                                </span>
                            </div>
                            <div class="tab-wrap">
                                <div class="tab-item active" data-mode="index">
                                    <div class="js-tree edit">
                                        <?= Menu::getManualMenu(Menu::getMenuData()) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- 自定义右击菜单 -->
                        <div class="rightkey-menu">
                            <ul>
                                <li>
                                    <a href="javascript:;" id="createChild">新建章节</a>
                                </li>
                                <li>
                                    <a href="javascript:;" id="editMenu">编辑</a>
                                </li>
                                <li>
                                    <a href="javascript:;" id="deleteMenu">删除</a>
                                </li>
                            </ul>
                        </div>
                        <!-- 自定义右击菜单 END --> 
                    </div>
                    <div class="con-right">
                        <div class="edit-con">
                            <div class="thinkeditor-tools">
                                <div class="thinkeditor-tools-group">
                                    <a class="hint--bottom save" href="javascript:;" data-hint="保存 (CTRL+S)">
                                        <i></i>
                                    </a>
                                </div>
                                <!-- <div class="thinkeditor-tools-group">
                                    <a class="hint--bottom undo" href="javascript:;" data-hint="撤销 (CTRL+Z)" disabled="disabled">
                                        <i></i>
                                    </a>
                                    <a class="hint--bottom redo" href="javascript:;" data-hint="重做 (CTRL+Y)" disabled="disabled">
                                        <i></i>
                                    </a>
                                </div> -->
                                <div class="thinkeditor-tools-group">
                                    <a class="hint--bottom h1" href="javascript:;" data-hint="标题一 (CTRL+1)">
                                        <i></i>
                                    </a>
                                    <a class="hint--bottom h2" href="javascript:;" data-hint="标题二 (CTRL+2)">
                                        <i></i>
                                    </a>
                                    <a class="hint--bottom h3" href="javascript:;" data-hint="标题三 (CTRL+3)">
                                        <i></i>
                                    </a>
                                    <a class="hint--bottom h4" href="javascript:;" data-hint="标题四 (CTRL+4)">
                                        <i></i>
                                    </a>
                                </div>
                                <div class="thinkeditor-tools-group">
                                    <a class="hint--bottom bold" href="javascript:;" data-hint="加粗 (CTRL+B)">
                                        <i></i>
                                    </a>
                                    <a class="hint--bottom italic" href="javascript:;" data-hint="斜体 (CTRL+I)">
                                        <i></i>
                                    </a>
                                </div>
                                <div class="thinkeditor-tools-group">
                                    <a class="hint--bottom ul" href="javascript:;" data-hint="无序列表 (CTRL+U)">
                                        <i></i>
                                    </a>
                                    <a class="hint--bottom ol" href="javascript:;" data-hint="有序列表 (CTRL+O)">
                                        <i></i>
                                    </a>
                                </div>
                                <div class="thinkeditor-tools-group">
                                    <!-- <a class="hint--bottom link" href="javascript:;" data-hint="链接 (CTRL+L)">
                                        <i></i>
                                    </a> -->
                                    <a class="hint--bottom image" href="javascript:;" data-hint="图片 (CTRL+G)">
                                        <i></i>
                                    </a>
                                    <a class="hint--bottom code" href="javascript:;" data-hint="代码 (CTRL+D)">
                                        <i></i>
                                    </a>
                                    <a class="hint--bottom hr" href="javascript:;" data-hint="分割线 (CTRL+H)">
                                        <i></i>
                                    </a>
                                    <a class="hint--bottom blockquote" href="javascript:;" data-hint="引用 (CTRL+Q)">
                                        <i></i>
                                    </a>
                                    <!-- <a class="hint--bottom table" href="javascript:;" data-hint="表格">
                                        <i></i>
                                    </a>
                                    <a class="hint--bottom htmlpaste" href="javascript:;" data-hint="粘贴HTML">
                                        <i></i>
                                    </a>
                                    <a class="hint--bottom toc" href="javascript:;" data-hint="章节导航">
                                        <i></i>
                                    </a> -->
                                </div>
                                <div class="thinkeditor-tools-group">
                                    <a class="hint--bottom history iframe-fancybox fancybox.iframe" href="<?= self::createUrl(['site/history']) ?>" data-href="<?= self::createUrl(['site/history']) ?>" data-hint="历史记录">
                                        <i></i>
                                    </a>
                                    <a class="hint--bottom preview active" href="javascript:;" data-hint="预览">
                                        <i></i>
                                    </a>
                                    <!-- <a class="hint--bottom sidebar active" href="javascript:;" data-hint="边栏">
                                        <i></i>
                                    </a>
                                    <a class="hint--bottom help" href="javascript:;" data-hint="帮助">
                                        <i></i>
                                    </a>
                                    <a class="hint--bottom more" href="javascript:;">
                                        <i></i>
                                    </a> -->
                                </div>
                            </div>
                            <div class="edit-box">
                                <div class="edit-container">
                                    <div class="edit-left">
                                        <div class="edit-left-inner">
                                            <textarea id="inputTextArea"></textarea>
                                        </div>
                                    </div>
                                    <div class="edit-right">
                                        <div class="edit-right-inner view-body" id="outputArea">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="tools-more" style="display: none;">
            <ul class="w-menu">
                <li class="menu-item">
                    <a href="javascript:;" class="menu-link setting">
                        <i class="icon icon-cog"></i>
                        文档配置
                    </a>
                </li>
                <li class="menu-item">
                    <a href="javascript:;" class="menu-link css">
                        <i class="icon icon-paintcan"></i>
                        样式表
                    </a>
                </li>
            </ul>
        </div> -->
    </div>
</div>