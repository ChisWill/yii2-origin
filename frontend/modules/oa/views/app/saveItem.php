<?php $form = self::beginForm() ?>
<?= $model->title('物品分类') ?>
<?= $form->field($model, 'pid')->dropDownList() ?>
<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'amount') ?>
<?= $form->submit($model) ?>
<?php self::endForm() ?>

<script>
    $(function() {
        $("#submitBtn").click(function() {
            $("form").ajaxSubmit($.config('ajaxSubmit', {
                success: function(msg) {
                    if (msg.state) {
                        $.alert(msg.info || '添加成功', function() {
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