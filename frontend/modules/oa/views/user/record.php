<?= $this->render('recordList', compact('list', 'user')) ?>

<?php $form = self::beginForm() ?>
<h2 class="text-c">录入联系记录</h2>
<?= $form->field($model, 'content')->textArea() ?>
<?= $form->field($model, 'user_id')->hiddenInput()->label('') ?>
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