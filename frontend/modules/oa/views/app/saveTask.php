<?php $form = self::beginForm() ?>
<?= $model->title('任务') ?>
<?= $form->field($model, 'app_id')->dropDownList() ?>
<?= $form->field($model, 'content')->textArea() ?>
<?= $form->field($model, 'user_id')->dropDownList() ?>
<?= $form->field($model, 'urgency_level')->dropDownList() ?>
<?= $form->field($model, 'hour') ?>
<?= $form->submit($model) ?>
<?php self::endForm() ?>

<script>
$(function () {
    $("#submitBtn").click(function () {
        $("form").ajaxSubmit($.config('ajaxSubmit', {
            success: function (msg) {
                if (msg.state) {
                    $.alert(msg.info || '添加成功', function () {
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