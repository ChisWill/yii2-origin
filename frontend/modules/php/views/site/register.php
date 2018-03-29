<?php $this->regCss(['common', 'register']) ?>
<?php $this->title = t('Register') ?>

<div class="content">
    <div class="content-wrap">
        <span><?= t('Register') ?></span>
        <?php $form = self::beginForm() ?>
        <?= $form->field($model, 'username')->textInput(['placeholder' => t('Please input an email.')]) ?>
        <?= $form->field($model, 'password')->passwordInput(['placeholder' => t('Please input password.')]) ?>
        <?= $form->field($model, 'cfmPassword')->passwordInput(['placeholder' => t('Please repeat password.')]) ?>
        <?= $form->field($model, 'captcha')->captcha(['options' => ['placeholder' => t('Please input captcha.')]]) ?>
        <div>
            <input id="registBtn" type="submit" value="立即注册">
        </div>
        <?php self::endForm() ?>
    </div>
</div>

<script>
$(function () {
    $("#registBtn").click(function () {
        $("form").ajaxSubmit($.config('ajaxSubmit', {
            success: function (msg) {
                if (msg.state) {
                    $.alert(msg.info, function () {
                        parent.location.reload();
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