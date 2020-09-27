<?php

namespace shudu\assets;

/**
 * shudu 静态资源
 */
class Asset extends \common\components\AssetBundle
{
    public $sourcePath = '@shudu/static';
    public $js = [
        'main.js'
    ];
    public $css = [
        'main.css'
    ];
    public $depends = [
        'common\assets\CommonAsset'
    ];
}
