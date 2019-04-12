<?php use common\helpers\Html; ?>

<?php $form = self::beginForm(['id' => 'dataForm']) ?>
<?php if ($model->commitUser !== null): ?>
<?= $model->label('commitUser') ?>：<?= $model->commitUser . $form->field($model, 'commitUser')->hiddenInput() ?>
<?php else: ?>
<?= $model->label('commitUser') ?>：<?= $form->field($model, 'commitUser')->textInput(['placeholder' => '必须填写真实姓名']) ?>
<?php endif ?>
<?= $model->label('item') ?>：<?= $form->field($model, 'item')->checkboxList() ?>
<?= $model->label('tables') ?>：<?= $form->field($model, 'tables')->checkboxList() ?>
<div>
    <input type="button" class="migrateSubmit" id="dataSubmit" value="保存表数据">
</div>
<?php self::endForm() ?>

<?php if (!empty($files)): ?>
<div id="ajax-area" class="ajax-area">
    <?= $this->render('_dataList', compact('files', 'model')) ?>
</div>
<div id="sync-all-div" style="text-align: center">
    <div>
        <?= Html::a(Html::button('同步所有表数据'), ['syncData'], ['id' => 'sync-all-data']) ?>
    </div>
</div>
<?php endif ?>