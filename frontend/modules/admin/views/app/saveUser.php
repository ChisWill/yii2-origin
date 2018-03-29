<?php $form = self::beginForm() ?>
<?= $model->title('客户') ?>
<?= $form->field($model, 'username') ?>
<?= $form->field($model, 'password') ?>
<?= $form->field($model, 'nickname') ?>
<?= $form->field($model, 'comment')->textarea() ?>
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