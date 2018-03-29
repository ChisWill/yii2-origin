<?= $this->title = '支付' ?>

<?php $form = self::beginForm() ?>
<?= $form->field($model, 'amount') ?>
<?= $form->field($model, 'charge_type')->dropDownList() ?>
<?= $form->submit($model) ?>
<?php self::endForm() ?>

<script>
$(function () {
    $("#submitBtn").click(function () {
        $("form").ajaxSubmit($.config('ajaxSubmit', {
            success: function (msg) {
                if (msg.state) {
                    eval(msg.data);
                } else {
                    $.alert(msg.info);
                }
            }
        }));
        return false;
    });
});
</script>