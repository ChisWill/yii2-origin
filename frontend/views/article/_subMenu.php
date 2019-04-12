<?php use common\helpers\Html; ?>

<?php foreach ($subMenus as $key => $sub): ?>
    <?php if ($sub['id'] == $subMenu->id): ?>
        <?= Html::a($sub->name . 'xxx', [$sub->parent->url, 'id' => $sub->id]) ?>
    <?php else: ?>
        <?= Html::a($sub->name, [$sub->parent->url, 'id' => $sub->id]) ?>
    <?php endif ?>
<?php endforeach ?>