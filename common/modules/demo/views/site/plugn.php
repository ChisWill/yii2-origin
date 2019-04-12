<?php common\assets\FancyBoxAsset::register($this) ?>
<?php common\assets\TimePickerAsset::register($this) ?>

<fieldset class="layui-elem-field">
    <legend>提示框</legend>
    <div class="layui-field-box">使用方法查看 JS 代码</div>
</fieldset>

<style>
.layui-row .layui-btn, .layui-row input {
    margin-bottom: 10px;
}
</style>

<div class="layui-row">
    <div class="layui-col-xs3"><button class="layui-btn layui-btn-primary" id="testAlert">普通</button></div>
    <div class="layui-col-xs3"><button class="layui-btn layui-btn-primary" id="testConfirm">确认</button></div>
    <div class="layui-col-xs3"><button class="layui-btn layui-btn-primary" id="testPrompt">输入信息</button></div>
    <div class="layui-col-xs3"><button class="layui-btn layui-btn-primary" id="testMsg">提示消息</button></div>
    <div class="layui-col-xs3"><button class="layui-btn layui-btn-primary" id="testLoading">loading框</button></div>
    <div class="layui-col-xs3"><button class="layui-btn layui-btn-primary" id="testTips">tips</button></div>
</div>

<fieldset class="layui-elem-field">
    <legend>弹出层</legend>
    <div class="layui-field-box">一般不需要主动调用插件的方法，只需添加指定 class 即可生效，以下全部示例须要加载`common\assets\FancyBoxAsset`资源包</div>
</fieldset>

<div class="layui-row">
    <!-- 'fancybox.ajax'是必填的，'view-fancybox'的名称是可选的，但必须包含'fancybox'，弹出层链接使用`href`标签的值，即可生效 -->
    <div class="layui-col-xs3"><button class="layui-btn layui-btn-primary view-fancybox fancybox.ajax" href="<?= url(['plugn', 'fancybox' => '这是 Ajax 方式']) ?>">ajax</button></div>
    <!-- 和上述区别仅在于替换为'fancybox-iframe' -->
    <div class="layui-col-xs3"><button class="layui-btn layui-btn-primary view-fancybox fancybox.iframe" href="<?= url(['plugn', 'fancybox' => '这是 iframe 方式']) ?>">iframe</button></div>
    <!-- 只需要一个包含'fancybox'的类名，`href`提供一个图片链接即可 -->
    <div class="layui-col-xs3"><button class="layui-btn layui-btn-primary view-fancybox" href="/images/default-face.jpg">图片</button></div>
</div>

<fieldset class="layui-elem-field">
    <legend>日历选择器</legend>
    <div class="layui-field-box">一般不需要主动调用插件的方法，只需添加指定 class 即可生效，以下全部示例须要加载`common\assets\TimePickerAsset`资源包</div>
</fieldset>

<div class="layui-row">
    <!-- 使用方式与弹出层一样 -->
    <div class="layui-col-xs3">日期：<input type="text" class="datepicker"></div>
    <!-- 只要`class`属性中包含有插件名称，即可自动生效。 -->
    <div class="layui-col-xs3">时间：<input type="text" class="timepicker"></div>
    <!-- 大多数前端插件都如此 -->
    <div class="layui-col-xs3">日期时间：<input type="text" class="datetimepicker"></div>
</div>
<div class="layui-row">
    <div class="layui-col-xs6">日期区间：<input type="text" class="startdate"> - <input type="text" class="enddate"></div>
    <div class="layui-col-xs6">时间区间：<input type="text" class="starttime"> - <input type="text" class="endtime"></div>
</div>

<fieldset class="layui-elem-field layui-field-title">
    <legend>辅助方法</legend>
</fieldset>

<div class="layui-row">
    <div class="layui-col-xs3"><button class="layui-btn layui-btn-primary" id="testPath">获取 Url 路径</button></div>
    <div class="layui-col-xs3"><button class="layui-btn layui-btn-primary" id="testUrl">获取 Url 参数</button></div>
</div>

<script>
$(function () {
    $("#testAlert").click(function () {
        $.alert('这是普通的弹窗方式', function () {
            $.alert('通过回调方法来执行弹窗框关闭后的事件');
        });
    });

    $("#testConfirm").click(function () {
        $.confirm('这是确认弹窗，你学会了么？', function () {
            $.alert('恭喜');
        });
    });

    $("#testPrompt").click(function () {
        $.prompt('标题文字', function (value) {
            $.alert('你输入的是：' + value);
        });
    });

    $("#testMsg").click(function () {
        $.msg('一闪而过');
    });

    $("#testLoading").click(function () {
        var index = $.loading();
        setTimeout(function () {
            layer.close(index);
        }, 1000);
    });

    $("#testTips").click(function () {
        $("#testTips").tips('我是tips');
    });

    $("#testPath").click(function () {
        $.alert($.getUrlPath(window.location.href));
    });

    $("#testUrl").click(function () {
        $.alert(JSON.stringify($.getUrlParams(window.location.href)));
    });
});
</script>