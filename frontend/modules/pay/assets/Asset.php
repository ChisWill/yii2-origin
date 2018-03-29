<?php

namespace pay\assets;

/**
 * pay 静态资源
 */
class Asset extends \common\components\AssetBundle
{
    public $sourcePath = '@pay/static';
    public $js = [
        'main.js'
    ];
    public $css = [
        'main.css'
    ];
    public $depends = [
        'common\assets\CommonAsset',
        'common\assets\JqueryFormAsset'
    ];
}
