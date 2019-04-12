<?= $this->regCss('register') ?>
<?= $this->regJs('rem') ?>
<div class="register-box">
    <?php $form = self::beginForm(['showLabel' => false]) ?>
    <?= $form->field($model, 'nickname')->textInput(['placeholder' => '请输入您的昵称']) ?>
    <?= $form->field($model, 'mobile')->textInput(['placeholder' => '请输入您的手机号']) ?>
    <?= $form->field($model, 'captcha')->captcha(['options' => ['class' => 'captcha', 'placeholder' => '请输入验证码']]) ?>
    <?= $form->field($model, 'verifyCode')->verifyCode([], ['class' => 'verify-code', 'placeholder' => '请输入验证码']) ?>
    <?= $form->field($model, 'password')->passwordInput(['placeholder' => '请输入您的6-12位密码']) ?>
    <?= $form->field($model, 'cfmPassword')->passwordInput(['placeholder' => '请再次输入您的密码']) ?>
    <a class="login-btn" href="<?= url(['site/login']) ?>">已有账号？登录</a>
    <?= $form->submit('注册', ['class' => 'register-btn']) ?>
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