<!-- 客服列表 -->
<div class="service-list" id="serviceList">
<?php
    $list = config('chatServices', []);
    if (!user()->isGuest && array_key_exists(u()->id, $list)) {
        $isGuest = 0;
        $list = [];
    } else {
        $isGuest = 1;
    }
    $i = 1;
    if (!$isGuest) {
        $index = array_search(u()->id, array_keys(config('chatServices'))) + 1;
    }
?>
<input type="hidden" class="isGuest" value="<?= $isGuest ?>">
<input type="hidden" class="userId" value="<?= u()->id ?>">
<?php if (!$isGuest): ?>
<input type="hidden" class="fromName" value="<?= config('chatServices')[u()->id] ?>">
<input type="hidden" class="fromFace" value="<?= $this->getAssetUrl('chat\assets\ClientAsset') . '/images/face-' . $index . '.jpg' ?>">
<?php endif ?>
<input type="hidden" class="basePath" value="<?= $this->getAssetUrl('chat\assets\ClientAsset') ?>">
    <div class="info">
        <div class="user">
            <?php if (!$isGuest): ?>
                <?= config('chatServices')[u()->id] ?>
            <?php endif ?>
        </div>
        <?php if (!$isGuest): ?>
            <img class="clear-icon clearIcon" src="<?= $this->getAssetUrl('chat\assets\ClientAsset') . '/images/clear.png' ?>">
        <?php endif ?>
    </div>
    <ul class="tab-content">
        <li>
            <h5>
                <i class="icon"></i>
                <span>消息列表</span>
                <em>(<cite class="service-count">0</cite>)</em>
            </h5>
            <ul class="member-list memberList">
            <?php foreach ($list as $key => $name): ?>
                <li data-id="<?= $key ?>">
                    <img class="headicon userFace" src="<?= $this->getAssetUrl('chat\assets\ClientAsset') . '/images/face-' . $i++ . '.jpg' ?>"/>
                    <span class="username userName"><?= $name ?></span>
                    <span class="msg-time lastTime"></span>
                    <div class="msgText"></div>
                    <span class="message-num msgNum hide">
                        <i>0</i>
                    </span>
                </li>
            <?php endforeach ?>
            </ul>
        </li>
    </ul>
    <span class="setwin">
        <a class="closeList close-list" href="javascript:;"></a>
    </span>
</div>
<div class="slide-service" id="slideService">
    <div class="service-icon"></div>
    <span><?= $isGuest ? '我的客服' : '我的消息' ?></span>
    <span class="total-num hide"><i>0</i></span>
</div>