<?php common\assets\SocketIOAsset::register($this) ?>
<?php common\assets\INotifyAsset::register($this) ?>
<input type="hidden" id="serverUrl" value="<?= Yii::$app->params['webDomain'] . ':' . \chat\servers\SocketIO::SOCKET_PORT ?>">