<?php use common\helpers\Html; ?>
<h2 class="text-c pd-10 mb-10"><?= $title ?></h2>
<?php
if ($info) {
    foreach ($info as $row) {
        echo "<pre><code><a href='" . url(['download', 'path' => $row['filePath'], 'name' => $row['fileName']]) . "'>" . Html::successSpan($row['fileName']) . " (" . Html::likeSpan($row['username']) . ' ' . Html::successSpan($row['time']) . ")</a></code></pre>";
    } 
} else {
    echo '<pre><code>还没有记录</code></pre>';
}