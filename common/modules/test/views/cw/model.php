<?php use yii\widgets\Pjax; ?>
<?php \common\assets\JqueryFormAsset::register($this);?>
<div id="loading"></div>
<?php Pjax::begin(['id' => 'timeArea']); ?>
<a href="<?= url(['cw/model']) ?>"><?= self::$time ?></a>
<?php Pjax::end(); ?>

<?php Pjax::begin(['id' => 'testForm']); ?>
<?php $form = self::beginForm(['data-pjax' => true]) ?>
<?= $form->field($model, 'name') ?>
<?= $form->submit($model) ?>
<?php self::endForm() ?>
<?php Pjax::end(); ?>

<script>
$(function () {
    $("#testForm").on("pjax:end", function () {
        $.pjax.reload({
            container: "#timeArea"
        });
    });
});
</script>