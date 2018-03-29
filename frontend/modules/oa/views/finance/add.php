<?php use oa\models\OaFinance; ?>
<?php use common\helpers\Html; ?>

<?php $form = self::beginForm() ?>
<h2 class="text-c"><?= $model->getTypeValue($type) ?></h2>
<?= $form->field($model, 'category_id')->dropDownList($items) ?>
<?= $form->field($model, 'amount') ?>
<?= $form->field($model, 'remark') ?>
<?= $form->field($model, 'created_at')->datepicker() ?>
<?= $form->submit($model) ?>
<?php self::endForm() ?>

<script>
$(function () {
    $("#submitBtn").click(function () {
        $("form").ajaxSubmit($.config('ajaxSubmit', {
            success: function (msg) {
                if (msg.state) {
                    $.alert(msg.info || '录入成功', function () {
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