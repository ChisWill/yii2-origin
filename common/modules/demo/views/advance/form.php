<?php use common\helpers\Html; ?>

<fieldset class="layui-elem-field layui-field-title">
    <legend>输入用户ID，编辑指定用户</legend>
</fieldset>

<div class="layui-row">
    <div class="layui-col-xs2" style="margin: 0 30px;">
        <input type="text" class="layui-input" id="editId" value="<?= $user->id ?>">
    </div>
    <div class="layui-col-xs2">
        <button class="layui-btn" id="editBtn">跳转编辑</button>
    </div>
    <?php if ($user->id): ?>
    <div class="layui-col-xs1">
        <button type="button" class="layui-btn layui-btn-primary" id="newBtn">创建新内容</button>
    </div>
    <?php endif ?>
    <div class="layui-col-xs1">
        <?= Html::errorSpan($error) ?>
    </div>
</div>

<fieldset class="layui-elem-field layui-field-title">
    <legend>多表提交</legend>
</fieldset>

<?php $form = self::beginForm() ?>
<div class="layui-form-item">
    <label class="layui-form-label">名字</label>
    <div class="layui-input-block">
        <?= $form->field($user, 'username')->textInput(['placeholder' => '请输入账号', 'class' => 'layui-input']) ?>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">密码</label>
    <div class="layui-input-block">
        <?= $form->field($user, 'password')->passwordInput(['placeholder' => '请输入密码', 'class' => 'layui-input']) ?>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">介绍</label>
    <div class="layui-input-block">
        <!-- 使用富文本编辑器 -->
        <?= $form->field($user, 'area')->editor() ?>
    </div>
</div>
<div>
    <label class="layui-form-label">批量上传</label>
    <div class="layui-input-block">
        <!-- 一次上传多个文件，需要设置成'file[]'，并且在模型中的验证规则中添加'maxFiles'配置项，限制一次上传多少个文件 -->
        <?= $form->field($user, 'file[]')->upload() ?>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">注册赠金</label>
    <div class="layui-input-block">
        <?= $form->field($userCharge, 'amount')->textInput(['placeholder' => '请输入金额', 'class' => 'layui-input']) ?>
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
    $("#submitBtn").click(function () {
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
        window.location.href = '<?= url(['form']) ?>';
    });
});
</script>