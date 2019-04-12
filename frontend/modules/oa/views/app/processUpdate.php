<?= $this->render('processView', compact('title', 'processes')) ?>

<?php $form = self::beginForm(['class' => 'mt-40']) ?>
<?= $form->field($process, 'desc')->textArea(['style' => ['height' => '100px']]) ?>
<?= $form->submit('添加') ?>
<?php self::endForm() ?>

<script>
$(function () {
    $("#submitBtn").click(function () {
        $("form").ajaxSubmit($.config('ajaxSubmit', {
            success: function (msg) {
                if (msg.state) {
                    $.alert('进度更新成功', function () {
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