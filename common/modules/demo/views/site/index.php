<!-- 视图层可直接使用各种类库 -->
<?php use common\helpers\Html; ?>

<div class="layui-row">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>基本概念简介</legend>
    </fieldset>
    <!-- 视图层使用如下语法形式表达`foreach` -->
    <?php foreach (explode("\n", $summary) as $key => $content): ?>
        <!-- `if`的格式 -->
        <?php if ($key % 2 == 0): ?>
            <blockquote class="layui-elem-quote"><?= $content ?></blockquote>
        <?php else: ?>
            <blockquote class="layui-elem-quote layui-quote-nm"><?= $content ?></blockquote>
        <?php endif ?>
    <?php endforeach ?>
</div>
<fieldset class="layui-elem-field">
    <legend>Notice</legend>
    <div class="layui-field-box">
        <!-- 直接输出内容使用如下语法形式 -->
        <?= Html::errorSpan('为了使示例表现效果更好，本文档使用 Layui 作为前端样式插件，阅读前端源码时，要习惯忽略那些呈现视觉效果的元素') ?>
    </div>
    <div class="layui-field-box"><?= Html::errorSpan('将注意力集中在代码间的注释') ?></div>
</fieldset>
<!-- 其他视图层内容参看`layouts`文件夹 -->