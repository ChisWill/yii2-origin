<?php use admin\models\ArticleMenu; ?>

<?php $form = self::beginForm() ?>
<?= $model->title('文章') ?>
<?= $form->field($model, 'title') ?>
<div class="row cl">
    <label class="form-label col-sm-2">选择分类</label>
    <div class="formControls col-sm-9">
<?php
$parents = ArticleMenu::find()->where(['pid' => 0, 'state' => ArticleMenu::STATE_VALID])->asArray()->orderBy('sort')->all();
$children = ArticleMenu::find()->where(['>', 'pid', 0])->andWhere(['state' => ArticleMenu::STATE_VALID])->asArray()->orderBy('sort')->all();
$html = '<select id="select" name="Article[menu_id]" class="input-text">';
foreach ($parents as $parent) {
    $html .= '<option disabled="disabled">' . $parent['name'] . '</option>';
    foreach ($children as $child) {
        if ($child['pid'] == $parent['id']) {
            if ($child['id'] == $model->menu_id) {
                $selected = 'selected="selected"';
            } else {
                $selected = '';
            }
            $html .= '<option data-category="' . $child['category'] . '" ' . $selected . ' value="' . $child['id'] . '">&nbsp;&nbsp;┕' . $child['name'] . '</option>';
        }
    }
}
$html .= '</select>';
?>
    <?= $html ?>
    </div>
    <div class="help-block"></div>
</div>
<?= $form->field($model, 'file', ['options' => ['id' => 'img', 'class' => 'row cl', 'style' => ['display' => 'none']]])->upload() ?>
<?= $form->field($model, 'content')->editor() ?>
<input type="hidden" value="" id="categoryType" name="categoryType">
<?= $form->submit($model) ?>
<?php self::endForm() ?>

<script>
$(function () {
    $("#select").change(function () {
        selectChangeEvent(this);
    });

    var selectChangeEvent = function (dom) {
        var category = $(dom).find('option:selected').data('category');
        if (category == 1) {
            $("#img").show();
        } else {
            $("#img").hide();
        }
        $("#categoryType").val(category);
    };

    selectChangeEvent($("#select")[0]);

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