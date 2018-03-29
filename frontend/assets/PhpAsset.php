<?php

namespace frontend\assets;

class PhpAsset extends \common\components\AssetBundle
{
    public $js = [
        // 'js/php.js'
    ];
    public $css = [
        'css/php/reset.css',
    ];
    public $depends = [
        'common\assets\CommonAsset',
        'common\assets\JqueryFormAsset'
    ];
}
