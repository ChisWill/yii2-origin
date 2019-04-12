<?php use admin\models\OaNotice; ?>

<?php $form = self::beginForm() ?>
<?= $model->title('公告') ?>
<?= $form->field($model, 'title')->textInput(['placeholder' => '标题']) ?>
<?= $form->field($model, 'file')->upload() ?>
<?= $form->field($model, 'content')->editor() ?>
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