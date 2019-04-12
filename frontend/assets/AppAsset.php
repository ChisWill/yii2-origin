<?php

namespace frontend\assets;

/**
 * frontend 基础静态资源
 */
class AppAsset extends \common\components\AssetBundle
{
    public $js = [
        'js/site.js'
    ];
    public $css = [
        'css/reset.css',
        'css/site.css'
    ];
    public $depends = [
        'common\assets\CommonAsset',
        'common\assets\LayerAsset',
        'common\assets\JqueryFormAsset'
    ];
}
