<?php use common\helpers\Html; ?>
<h2 class="text-c pd-10"><?= $user->name ?> - 联系记录</h2>
<?php
foreach ($list as $row) {
    echo "<pre><code>{$row['content']} (" . Html::likeSpan($row['adminUser']['realname']) . ' ' . Html::successSpan($row['created_at']) . ")</code></pre>";
}
?>