<?php

namespace common\assets;

/**
 * 引入 iNotify 插件
 *
 * @author ChisWill
 */
class INotifyAsset extends \common\components\AssetBundle
{
    public $sourcePath = '@bower/iNotify/src';
    public $js = [
        'iNotify.js',
    ];
}
