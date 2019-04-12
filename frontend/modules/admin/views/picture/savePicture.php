<?php use admin\models\Picture; ?>

<?php $form = self::beginForm() ?>
<?= $model->title('图片') ?>
<?= $form->field($model, 'title')->textInput(['placeholder' => '标题']) ?>
<?= $form->field($model, 'file')->upload() ?>
<?= $form->field($model, 'url')->textInput(['placeholder' => '不填则点击不跳转']) ?>
<?= $form->field($model, 'type')->radioList(get('id') ? null : Picture::TYPE_SWIPER) ?>
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