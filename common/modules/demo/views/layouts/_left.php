<?php $link = $this->context->id . '/' . $this->context->action->id ?>
<div class="left-aside">
    <?php foreach ($this->context->menu as $url => $title): ?>
    <!-- 多行PHP代码采用如下格式，保持缩进 -->
    <?php
        if ($url == $link) {
            $activeClass = 'active';
        } else {
            $activeClass = '';
        }
    ?>
    <div class="item <?= $activeClass ?>">
        <!-- 必须使用`url()`函数生成链接，第一个参数必须是写成数组形式，格式为：url(['site/index'])，表示访问当前模块的`site`控制器`actionIndex`方法 -->
        <a href="<?= url([$url]) ?>"><?= $title ?></a>
    </div>
    <?php endforeach ?>
</div>