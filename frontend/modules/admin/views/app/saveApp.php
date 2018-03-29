<?php $form = self::beginForm() ?>
<?= $model->title('应用') ?>
<?= $form->field($model, 'app_name') ?>
<?= $form->field($model, 'user_id')->dropDownList() ?>
<?= $form->field($model, 'rate_limit') ?>
<?= $form->field($model, 'total') ?>
<?= $form->field($model, 'ip') ?>
<?= $form->field($model, 'auth_date')->datepicker() ?>
<?= $form->submit($model) ?>
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
});
</script>