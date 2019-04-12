<?php $form = self::beginForm(['id' => 'userInfoForm', 'action' => self::createUrl(['userInfo'])]) ?>
<?= $form->field($model, 'username')->textInput() ?>
<?= $form->submit('确认修改', ['id' => 'userInfoBtn']) ?>
<?php self::endForm() ?>

<script>
$(function () {
    $("#userInfoBtn").click(function () {
        $("#userInfoForm").ajaxSubmit($.config('ajaxSubmit', {
            success: function (msg) {
                if (msg.state) {
                    $.alert(msg.info || '修改成功', function () {
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