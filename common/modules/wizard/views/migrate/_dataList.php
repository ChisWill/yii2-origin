<?php use common\helpers\Html; ?>
<table id="migration-history-table">
    <tr>
        <th width="10%">序号</th>
        <th>表名</th>
        <th width="15%">提交者</th>
        <th width="20%">提交时间</th>
        <th width="10%">操作</th>
    </tr>
    <?php foreach ($files as $index => $file): ?>
    <tr>
        <td><?= $index + 1 ?></td>
        <td><?= basename($file, '.list') ?></td>
        <td><?= $model->dumpInfo($file, 'user') ?></td>
        <td><?= $model->dumpInfo($file, 'time') ?></td>
        <td data-desc="<?= basename($file, '.list') ?>"><?= Html::a('删除', ['deleteData', 'file' => basename($file)], ['class' => 'delete-migration']) ?></td>
    </tr>
    <?php endforeach ?>
</table>