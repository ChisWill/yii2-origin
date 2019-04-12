<?php

namespace frontend\assets;

class SenluoAsset extends \common\components\AssetBundle
{
    public $js = [
        'js/site.js',
        'js/rem.js',
        'js/wow.min.js',
        'js/swiper.min.js',
        'js/trace.js'
    ];
    public $css = [
        'css/reset.css',
        'css/animate.css',
        'css/swiper.min.css',
        'css/common.css'
    ];
    public $depends = [
        'common\assets\CommonAsset',
        'common\assets\JqueryFormAsset',
        'common\assets\LayerAsset'
    ];
}
