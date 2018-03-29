<?php $form = self::beginForm(['showLabel' => true]) ?>
<?= $form->field($model, 'mobile') ?>
<?= $form->field($model, 'captcha')->captcha() ?>
<?= $form->field($model, 'verifyCode')->verifyCode() ?>
<?= $form->field($model, 'password')->passwordInput() ?>
<?= $form->field($model, 'cfmPassword')->passwordInput() ?>
<?= $form->submit('注册') ?>
<?php self::endForm() ?>

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