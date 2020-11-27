<ul>
<?php foreach ($list as $url => $desc): ?>
    <li><a class="fancybox-list fancybox.ajax" href="<?= $url ?>"><?= $desc ?></a></li>
<?php endforeach ?>
</ul>