<?php

namespace common\modules\manual\assets;

/**
 * 手册工具的静态资源
 *
 * @author ChisWill
 */
class ManualAsset extends \common\components\AssetBundle
{
    public $sourcePath = '@common/modules/manual/static';
    public $css = [
        'manual.css'
    ];
    public $js = [
        'manual.js'
    ];
    public $depends = [
        'common\assets\JqueryFormAsset',
        'common\assets\FancyBoxAsset',
        'common\assets\LayerAsset',
        'common\assets\MarkedAsset'
    ];
}
