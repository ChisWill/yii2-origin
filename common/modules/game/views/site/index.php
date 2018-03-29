<?php use \common\helpers\Html; ?>
<?php \common\modules\game\assets\GameAsset::register($this) ?>

<?php foreach ($gameList as $href => $name): ?>
    <?= Html::a($name, [$href]) ?>
<?php endforeach ?>