<?php chat\assets\ClientAsset::register($this) ?>

<?= $this->render('chat') ?>
<?= $this->render('list') ?>
<iframe name="server" src="<?= url(['/chat/site/server']) ?>" style="display: none"></iframe>