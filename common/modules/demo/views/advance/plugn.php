<?php common\assets\SortableAsset::register($this) ?>
<?php common\assets\ICheckAsset::register($this) ?>

<fieldset class="layui-elem-field">
    <legend>Sortable - 拖拽排序</legend>
    <div class="layui-field-box">更多使用方式查看官方DEMO：http://sortablejs.github.io/Sortable</div>
</fieldset>

<style>
.hint-div .selected {
    background: gold;
}
.layui-input-block {
    line-height: 36px;
}
.layui-input-block span {
    margin-left: 8px;
}
</style>

<ul id="testUl" class="layui-nav layui-nav-tree layui-inline">
    <?php for ($i = 0; $i <= 5; $i++): ?>
    <li class="layui-nav-item" style="text-align: center; border-bottom: 1px solid #931">
        <span style="cursor: move;">人造人<?= $i ?>号</span>
    </li>
    <?php endfor ?>
</ul>

<fieldset class="layui-elem-field">
    <legend>输入框下拉提示</legend>
    <div class="layui-field-box">在输入框中输入 `user` 表的 `username` 测试效果</div>
</fieldset>

<div class="layui-form-item">
    <!-- 主要用于表单提交，所以必须有[name]属性，[url]属性规定了数据接口的地址 -->
    <input type="text" name="testInput" id="testInput" class="layui-input" url="<?= url(['plugn', 'type' => 'bindHint']) ?>">
</div>
<div class="layui-form-item">
    <div class="layui-input-block">
        <input type="button" id="testSubmitBtn" class="layui-btn" value="查看输入框的值">
    </div>
</div>

<fieldset class="layui-elem-field layui-field-title">
    <legend>Icheck - 单选框和复选框美化</legend>
</fieldset>

<div class="layui-form-item">
    <label class="layui-form-label">单选框</label>
    <div class="layui-input-block">
        <label><input type="radio" name="sex"><span>男</span></label>
        <label><input type="radio" name="sex"><span>女</span></label>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">复选框</label>
    <div class="layui-input-block">
        <label><input type="checkbox" name="like[]"><span>写作</span></label>
        <label><input type="checkbox" name="like[]"><span>阅读</span></label>
        <label><input type="checkbox" name="like[]"><span>运动</span></label>
    </div>
</div>

<script>
$(function () {
    // 拖拽排序
    $("#testUl").dragSort();
    // 输入框下拉提示
    $("#testInput").bindHint();
    $("#testSubmitBtn").click(function () {
        // 实际获取的是`bindHint()`自动添加的隐藏框的[name]值
        $.alert($("[name='testInput']").val());
    });
    // 单选框和复选框的美化，需要指定元素进行美化
    $("input[type=radio],input[type=checkbox]").iCheck($.config('iCheck'));
});
</script>