<?php use common\models\Test; ?>
<?php use common\helpers\Html; ?>

<fieldset class="layui-elem-field layui-field-title">
    <legend>输入ID，编辑指定内容</legend>
</fieldset>

<div class="layui-row">
    <div class="layui-col-xs2" style="margin: 0 30px;">
        <!-- 用于绑定事件的 id 或 class，驼峰法命名；用于样式的 Html 属性，用减号链接多个单词 -->
        <input type="text" class="layui-input" id="editId" value="<?= $model->id ?>">
    </div>
    <div class="layui-col-xs2">
        <button class="layui-btn" id="editBtn">跳转编辑</button>
    </div>
    <!-- 视图层 if 的格式 -->
    <?php if ($model->id && !get('iframe')): ?>
    <div class="layui-col-xs1">
        <button type="button" class="layui-btn layui-btn-primary" id="newBtn">创建新记录</button>
    </div>
    <?php endif ?>
    <div class="layui-col-xs1">
        <?= Html::errorSpan($error) ?>
    </div>
</div>

<fieldset class="layui-elem-field layui-field-title">
    <legend>包含各类基本元素的表单提交</legend>
</fieldset>

<?php $form = self::beginForm(['class' => 'testForm', 'id' => 'testForm1']) ?>
<div class="layui-form-item">
    <label class="layui-form-label">名字</label>
    <div class="layui-input-block">
        <!-- 生成 input[type=text] 框；field() 方法传入的参数为对象和字段；textInput() 方法的参数是设置 input 标签的 Html 属性 -->
        <?= $form->field($model, 'name')->textInput(['placeholder' => '请输入名字', 'class' => 'layui-input']) ?>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">年龄</label>
    <div class="layui-input-block">
        <!-- `field()`方法默认生成 input[type=text] 框，亦可通过如下方式配置 -->
        <?= $form->field($model, 'age', ['inputOptions' => ['placeholder' => '请输入年龄', 'class' => 'layui-input']]) ?>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">简介</label>
    <div class="layui-input-block">
        <!-- 生成 textarea 标签 -->
        <?= $form->field($model, 'message')->textArea(['placeholder' => '请输入简介', 'class' => 'layui-textarea']) ?>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">注册日期</label>
    <div class="layui-input-block">
        <!-- 这是新增的方法，原版没有；生成日期选择输入框，会自动绑定日期选择事件 -->
        <?= $form->field($model, 'reg_date')->datepicker(['placeholder' => '请选择', 'class' => 'layui-input']) ?>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">标签</label>
    <div class="layui-input-block">
        <!-- 生成复选框，checkBoxList() 方法第一个参数是设置选项的数组，如果在模型中定义了该字段的 map 方法，即 Test::getTagsMap()，则可省略第一个参数 -->
        <?= $form->field($model, 'tags')->checkBoxList(['1' => '谦虚', '2' => '勤奋', '3' => '包容'], ['class' => 'layui-form-radio']) ?>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">状态</label>
    <div class="layui-input-block">
        <!-- 生成下拉框，当定义了 map 方法，又要设置第二个参数时，可将第一个参数设置为 null，表示跳过 -->
        <?= $form->field($model, 'reg_state')->dropDownList(null, ['class' => 'layui-input']) ?>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">性别</label>
    <div class="layui-input-block">
        <!-- 生成单选框，当定义了该字段的 map 方法，可按如下方式设置表单该项的默认值 -->
        <?= $form->field($model, 'sex')->radioList(Test::SEX_FEMAIL) ?>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">头像</label>
    <div class="layui-input-block">
        <!-- 这是新增的方法；生成 input[type=file] 框，此处不应传入表中的字段，故传入虚拟字段，以在当前模块的 Test 模型中定义 -->
        <?= $form->field($model, 'file')->upload(['class' => 'layui-input']) ?>
    </div>
</div>
<div class="layui-form-item">
    <div class="layui-input-block">
        <input type="submit" id="submitBtn" class="layui-btn" value="立即提交">
    </div>
</div>
<?php self::endForm() ?>

<script>
$(function () {
    // 使用官方的 ajaxSubmit 方法进行 Ajax 表单提交
    $("#submitBtn").click(function () {
        // $.config() 方法返回的是指定插件的默认配置项，并能设置自定义配置项
        $("form").ajaxSubmit($.config('ajaxSubmit', {
            success: function (msg) {
                if (msg.state) {
                    $.alert(msg.info || '操作成功', function () {
                        parent.location.reload();
                    });
                } else {
                    $.alert(msg.info);
                }
            }
        }));
        return false;
    });

    $("#editBtn").click(function () {
        var id = $("#editId").val();
        if (!id) {
            $.alert('请输入ID');
            return false;
        } else {
            window.location.href = '?id=' + id;
        }
    });
    $("#newBtn").click(function () {
        window.location.href = '<?= url(['more/form']) ?>';
    });
});
</script>