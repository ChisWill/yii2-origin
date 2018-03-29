<?php $form = self::beginForm() ?>
<?= $model->title('产品') ?>
<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'desc') ?>
<?= $form->field($model, 'version') ?>
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