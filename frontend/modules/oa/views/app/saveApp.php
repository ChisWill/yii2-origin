<?php $form = self::beginForm() ?>
<?= $model->title('项目') ?>
<?= $form->field($model, 'code') ?>
<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'domain') ?>
<?= $form->field($model, 'server_id')->dropDownList() ?>
<?= $form->field($model, 'type')->dropDownList() ?>
<?= $form->field($model, 'total_amount') ?>
<?= $form->field($model, 'server_info')->textArea() ?>
<?= $form->field($model, 'wechat_info')->textArea() ?>
<?= $form->field($model, 'pay_info')->textArea() ?>
<?= $form->field($model, 'sms_info')->textArea() ?>
<?= $form->field($model, 'requirement_info')->textArea() ?>
<?= $form->submit($model) ?>
<?php self::endForm() ?>

<script>
$(function () {
    $("#submitBtn").click(function () {
        $("form").ajaxSubmit($.config('ajaxSubmit', {
            success: function (msg) {
                if (msg.state) {
                    $.alert('创建项目成功', function () {
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