<?php use common\helpers\Html; ?>

<?php foreach ($subMenus as $key => $sub): ?>
    <?php if ($sub['id'] == $child['id']): ?>
        <?= Html::a('x' . $sub['name'], [$parent['url'], 'id' => $sub['id']]) ?>
    <?php else: ?>
        <?= Html::a($sub['name'], [$parent['url'], 'id' => $sub['id']]) ?>
    <?php endif ?>
<?php endforeach ?>