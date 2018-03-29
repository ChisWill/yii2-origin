<?php use common\helpers\Html; ?>

<?php if (user()->isGuest): ?>
<?= Html::tag('p', '当前状态：未登录') ?>
<?= Html::tag('p', Html::a('登录', ['site/login']) . str_repeat('&nbsp;', 4) . Html::a('注册', ['site/register'])) ?>
<?php else: ?>
<?= Html::a('登出', ['site/logout']) ?><br>
ID：<?= u('id') ?><br>
Username：<?= u('username', '无') ?><br>
Mobile：<?= u('mobile', '无') ?><br>
Nickname：<?= u('nickname', '无') ?><br>
<?php endif ?>
