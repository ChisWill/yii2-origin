<?php $form = self::beginForm() ?>
<?= $model->title('录入员工业绩') ?>
<?= $form->field($model, 'user_id')->dropDownList() ?>
<?= $form->field($model, 'score') ?>
<?= $form->field($model, 'comment')->textArea() ?>
<?= $form->submit($model) ?>
<?php self::endForm() ?>

<script>
$(function () {
    $("#submitBtn").click(function () {
        $("form").ajaxSubmit($.config('ajaxSubmit', {
            success: function (msg) {
                if (msg.state) {
                    $.alert(msg.info || '操作成功', function () {
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