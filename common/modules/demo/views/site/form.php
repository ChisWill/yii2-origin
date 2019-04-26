<!-- 本页面代码，是表单提交的最简易形式。万变不离其宗，切记 -->
<?php $form = self::beginForm(['showLabel' => true]) ?>
<?= $model->title('标题') ?>
<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'age') ?>
<?= $form->submit($model) ?>
<?php self::endForm() ?>

<script>
$(function () {
    // 绑定按钮点击事件，采用Ajax方式提交表单。
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