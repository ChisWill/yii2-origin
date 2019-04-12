<?php use common\helpers\Html; ?>
<h2 class="text-c pd-10 mb-10"><?= $title ?></h2>
<?php
if ($processes) {
    foreach ($processes as $row) {
        echo "<pre><code>{$row['desc']} (" . Html::likeSpan($row['adminUser']['realname']) . ' ' . Html::successSpan($row['created_at']) . ")</code></pre>";
    } 
} else {
    echo '<pre><code>还没有记录</code></pre>';
}
?>