<?php

namespace wxmp\assets;

/**
 * wxmp 静态资源
 */
class Asset extends \common\components\AssetBundle
{
    public $sourcePath = '@wxmp/static';
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
