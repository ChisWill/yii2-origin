<?php $form = self::beginForm() ?>
<?= $model->title('绩效记录') ?>
<?= $form->field($model, 'user_id')->dropDownList(\oa\models\AdminUser::getAllUserMap()) ?>
<?= $form->field($model, 'score')->label('绩效点') ?>
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
                        parent.window.location.reload();
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