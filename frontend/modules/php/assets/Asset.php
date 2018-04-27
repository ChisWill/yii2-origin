<?php

namespace php\assets;

use Yii;
/**
 * php 静态资源
 */
class Asset extends \common\components\AssetBundle
{
    public $sourcePath = '@php/static';
    public $js = [
        'js/main.js'
    ];
    public $css = [
        'css/reset.css'
    ];
    public $depends = [
        'common\assets\CommonAsset',
        'common\assets\JqueryFormAsset'
    ];

    public function init()
    {
        parent::init();

        if (Yii::$app->language === 'zh-CN') {
            $this->depends[] = 'common\assets\LayerAsset';
        }
    }
}
