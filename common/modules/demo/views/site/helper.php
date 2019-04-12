<?php use common\helpers\Html; ?>
<?php use common\helpers\System; ?>

<fieldset class="layui-elem-field">
    <legend>Html 助手类</legend>
    <div class="layui-field-box">使用统一的参数形式，生成各式 Html 标签</div>
</fieldset>

<div class="layui-row"><?= Html::a('生成 &lt;a&gt; 标签，跳转到当前页面，每次令参数 p 加一', ['site/helper', 'p' => get('p', 0) + 1]) ?></div>
<div class="layui-row"><?= Html::successSpan('生成 "成功" 颜色的 &lt;span&gt; 标签') ?></div>
<div class="layui-row"><?= Html::errorSpan('生成 "失败" 颜色的 &lt;span&gt; 标签') ?></div>
<div class="layui-row"><?= Html::warningSpan('生成 "警告" 颜色的 &lt;span&gt; 标签') ?></div>
<div class="layui-row"><?= Html::finishSpan('生成 "完成" 颜色的 &lt;span&gt; 标签') ?></div>
<div class="layui-row"><?= Html::otherSpan('生成 "其他" 颜色的 &lt;span&gt; 标签') ?></div>
<div class="layui-row"><?= Html::tag('div', '生成一个自定义属性的 &lt;div&gt; 标签', ['class' => ['layui-bg-red', 'layui-btn-sm'], 'style' => ['width' => '50%']]) ?></div>
<div class="layui-row"><?= Html::textInput('field', '生成一个 name 为 field 的 input 输入框', ['class' => 'layui-input']) ?></div>

<fieldset class="layui-elem-field">
    <legend>System 助手类</legend>
    <div class="layui-field-box">一般用于判断当前系统环境</div>
</fieldset>

<div class="layui-item">当前是否是 Window 操作系统：<?= System::isWindowsOs() ? Html::successSpan('是') : Html::errorSpan('否') ?></div>
<div class="layui-item">当前是否是微信浏览器：<?= System::isWeixin() ? Html::successSpan('是') : Html::errorSpan('否') ?></div>
<div class="layui-item">当前是否是手机浏览器：<?= System::isMobile() ? Html::successSpan('是') : Html::errorSpan('否') ?></div>