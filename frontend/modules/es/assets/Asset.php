<?php

namespace es\assets;

use common\assets\CommonAsset;
use common\assets\FancyBoxAsset;

/**
 * es 静态资源
 */
class Asset extends \common\components\AssetBundle
{
    public $sourcePath = '@es/static';
    public $js = [
        'main.js'
    ];
    public $css = [
        'main.css'
    ];
    public $depends = [
        CommonAsset::class,
        FancyBoxAsset::class
    ];
}
