<?= $this->regCss('login') ?>
<?= $this->regJs('rem') ?>
<div class="login-box">
    <?php $form = self::beginForm(['showLabel' => false]) ?>
        <?= $form->field($model, 'username')->textInput(['placeholder' => '请输入您的手机号']) ?>
        <?= $form->field($model, 'password')->passwordInput(['placeholder' => '请输入您的账号密码']) ?>
        <?= null//$form->field($model, 'rememberMe')->checkbox() ?>
        <a class="register-btn" href="<?= url(['site/register']) ?>">没有账号？立即注册</a>
        <?= $form->submit('登录', ['class' => 'login-btn']) ?>
    <?php self::endForm() ?>
</div>
<script>
$(function () {
    $("#submitBtn").click(function () {
        $("form").ajaxSubmit($.config('ajaxSubmit', {
            success: function (msg) {
                if (!msg.state) {
                    $.alert(msg.info);
                }
            }
        }));
        return false;
    });
});
</script>