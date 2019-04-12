<?php admin\assets\SettingAsset::register($this) ?>

<div class="setting-container">
    <ul class="tabBar cl" id="topParentUl">
        <?php
        $nowTopId = '';
        $selected = ' class="current"';
        foreach ($settings as $setting) {
            if ($setting['pid'] == 0) {
                if ($setting['name'] === '调试设置' && !u()->isMe) {
                    continue;
                }
                $nowTopId = $nowTopId ?: $setting['id'];
                echo '
        <li' . $selected . '>
            <a href="javascript:;" class="topMenuList" data-id="' . $setting['id'] . '">
            ' . $setting['name'] . '
            </a>
        </li>';
                $selected = '';
            }
        }
        ?>
        <li id="addTopParentLi" class="showMode">
            <span id="addTopParentLink" class="Hui-iconfont icon add-icon" href="<?= self::createUrl('addSetting') ?>">&#xe600;</span>
        </li>
        <li class="change-edit-li" <?php if (YII_ENV_PROD && !u()->isMe): ?>style="display:none;"<?php endif ?>>
            <a href="javascript:;" id="changeEditBtn" mode="1">切换到编辑模式</a>
        </li>
    </ul>
    
    <div id="settingContent">
        <?= $this->render('_setting', compact('settings', 'nowTopId')) ?>
        <div style="display:none;" id="settingData"><?= json_encode($settings) ?></div>
    </div>
</div>