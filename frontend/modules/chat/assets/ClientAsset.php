<?php

namespace chat\assets;

/**
 * chat 静态资源
 */
class ClientAsset extends \common\components\AssetBundle
{
    public $sourcePath = '@chat/static';
    public $js = [
        'js/random.js',
        'js/md-avatar.js',
        'js/chat.js',
        'js/client.js'
    ];
    public $css = [
        'css/reset.css',
        'css/chat.css'
    ];
    public $depends = [
        'common\assets\CommonAsset',
        'common\assets\LayerAsset',
    ];
}
