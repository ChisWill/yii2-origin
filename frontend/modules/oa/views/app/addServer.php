<?php $form = self::beginForm() ?>
<?= $model->title('服务器') ?>
<?= $form->field($model, 'server_name') ?>
<?= $form->field($model, 'server_ip') ?>
<?= $form->field($model, 'quoted_price') ?>
<?= $form->field($model, 'discount_price') ?>
<?= $form->field($model, 'platform_id')->dropDownList() ?>
<?= $form->field($model, 'account_id')->dropDownList() ?>
<?= $form->field($model, 'remark')->textArea() ?>
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