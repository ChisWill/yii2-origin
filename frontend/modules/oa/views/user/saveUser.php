<?php $form = self::beginForm() ?>
<?= $model->title('客户') ?>
<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'type')->dropDownList()->label('客户类型') ?>
<?= $form->field($model, 'product_id')->dropDownList()->label('项目类型') ?>
<?= $form->field($model, 'tel') ?>
<?= $form->field($model, 'wechat_id') ?>
<?= $form->field($model, 'qq') ?>
<?= $form->field($model, 'requirement')->textArea() ?>
<?= $form->field($model, 'amount') ?>
<?= $form->field($model, 'source')->dropDownList() ?>
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