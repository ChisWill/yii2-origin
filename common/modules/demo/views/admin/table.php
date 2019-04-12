<fieldset class="layui-elem-field layui-field-title">
    <legend>包含搜索、排序、分页等功能的列表</legend>
</fieldset>

<style>
.list-view .r {
    float: right;
}
</style>

<!-- 是的，列表的所有Html代码都在一个变量里，所有 JS 事件代码都是已经内置并自动加载的 -->
<?= $html ?>

<script>
$(function () {
    // 这里可以自定义额外的事件方法
});
</script>