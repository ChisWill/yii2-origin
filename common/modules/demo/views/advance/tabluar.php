<?php use common\helpers\Html; ?>

<fieldset class="layui-elem-field layui-field-title">
    <legend>列表模型的提交</legend>
</fieldset>

<style>
.form-row {
    width: 30%;
    float: left;
}
</style>

<?php $form = self::beginForm(['id' => 'testForm']) ?>
<?php foreach ($models as $key => $model): ?>
<div class="layui-form-item modelItem">
    <div class="form-row">
        <label class="layui-form-label">名字</label>
        <div class="layui-input-block">
            <?= $form->field($model, "[$key]name")->textInput(['placeholder' => '请输入名字', 'class' => 'layui-input']) ?>
        </div>
    </div>
    <div class="form-row">
        <label class="layui-form-label">年龄</label>
        <div class="layui-input-block">
            <?= $form->field($model, "[$key]age")->textInput(['placeholder' => '请输入年龄', 'class' => 'layui-input']) ?>
        </div>
    </div>
    <div class="form-row" align="center">
        <input type="button" class="layui-btn removeBtn layui-btn-danger" value="移除" data-id="<?= $model->id ?>">
    </div>
</div>
<?php endforeach ?>
<div class="layui-form-item">
    <div class="layui-input-block">
        <input type="button" id="addBtn" class="layui-btn" value="新增">
    </div>
</div>
<div class="layui-form-item">
    <div style="text-align: center">
        <input type="submit" id="submitBtn" class="layui-btn layui-btn-lg layui-btn-warm" value="保存">
    </div>
</div>
<?php self::endForm() ?>

<script>
$(function () {
    // 新增按钮
    $("#addBtn").click(function () {
        // 复制第一个元素
        $newItem = $(".modelItem").eq(0).clone();
        // 初始化表单的值
        $newItem.find('input[name]').val('');
        $newItem.find('.removeBtn').data('id', '');
        // 添加到页面上
        $(this).parents('.layui-form-item').before($newItem);
    });
    // 移除按钮
    $("#testForm").on('click', '.removeBtn', function () {
        if ($(".modelItem").length === 1) {
            $.alert('不能删除最后一个');
            return false;
        } else {
            var $this = $(this),
                id = $this.data('id');
            // 如果存在`id`值，则是删除一个已经保存到数据库中的项目
            if (id) {
                $.confirm('确认删除？', function () {
                    $this.parents('.modelItem').remove(); // 一般不太可能删除失败，所以前端先行移除，如此可使整个操作显得更为流畅
                    $.post('delete-tabular', {
                        id: id
                    }, function (msg) {
                        if (!msg.state) {
                            $.alert(msg.info);
                        }
                    }, 'json');
                });
            } else {
                $this.parents('.modelItem').remove();
            }
        }
    });
    // 提交按钮
    $("#submitBtn").click(function () {
        // 调整每行的[name]属性
        $(".modelItem").each(function (index) {
            $(this).find('input[name]').each(function () {
                var pieces = $(this).attr('name').split(/\[\d\]/);
                $(this).attr('name', pieces[0] + '[' + index + ']' + pieces[1]);
            });
        });
        $("#testForm").ajaxSubmit($.config('ajaxSubmit', {
            success: function (msg) {
                if (msg.state) {
                    $.alert(msg.info || '操作成功', function () {
                        window.location.reload();
                    });
                } else {
                    $.alert(msg.info);
                }
            }
        }));
        return false;
    });
});
</script>