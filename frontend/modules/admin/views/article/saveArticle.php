<?php use admin\models\ArticleMenu; ?>

<?php $form = self::beginForm() ?>
<?= $model->title('文章') ?>
<?= $form->field($model, 'title') ?>
<?= $form->field($model, 'summary')->textInput(['placeholder' => '可不填']) ?>
<div class="row cl">
    <label class="form-label col-sm-2">选择分类</label>
    <div class="formControls col-sm-9">
    <?= $categorySelect ?>
    </div>
    <div class="help-block"></div>
</div>
<?= $form->field($model, 'file', ['options' => ['id' => 'img', 'class' => 'row cl']])->upload() ?>
<?= $form->field($model, 'content')->editor() ?>
<?= $form->field($model, 'template')->textInput(['placeholder' => '不填表示默认模板']) ?>
<?= $form->submit($model) ?>
<?php self::endForm() ?>

<script>
$(function () {
    $("#categorySelect").attr('name', 'Article[menu_id]');
    $("#categorySelect").children('[data-pid=0]').attr('disabled', 'disabled');
    <?php if (!$model->menu_id): ?>
    $("#categorySelect").children('[data-pid!=0]:eq(0)').attr("selected", true);
    <?php endif ?>

    $("#submitBtn").click(function () {
        $("form").ajaxSubmit($.config('ajaxSubmit', {
            success: function (msg) {
                if (msg.state) {
                    $.alert(msg.info || '操作成功', function () {
                        $parentLi = parent.$('.linkage-container .linkage-row-selected a');
                        if ($parentLi.length > 0) {
                            $parentLi.trigger('click');
                        } else {
                            parent.$('.linkage-container .linkage-header a').trigger('click');
                        }
                        parent.$.fancybox.close();
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