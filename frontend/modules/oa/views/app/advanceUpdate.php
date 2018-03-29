<?php $form = self::beginForm() ?>
<h2 class="text-c"><?= $app->code ?> - <?= $app->name ?></h2>
<?= $form->field($app, $field)->textArea(['style' => ['height' => '300px'], 'value' => $value['text']]) ?>
<?= $form->submit('更新') ?>
<?php self::endForm() ?>

<script>
$(function () {
    $("#submitBtn").click(function () {
        $("form").ajaxSubmit($.config('ajaxSubmit', {
            success: function (msg) {
                if (msg.state) {
                    $.alert('更新成功', function () {
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