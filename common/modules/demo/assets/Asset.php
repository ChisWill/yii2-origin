<?php

namespace common\modules\demo\assets;

/**
 * demo 静态资源
 */
class Asset extends \common\components\AssetBundle
{
    // 资源包所在位置
    public $sourcePath = '@common/modules/demo/static';
    // 需要的 JS 文件
    public $js = [
        'main.js'
    ];
    // 需要的 CSS 文件
    public $css = [
        'main.css'
    ];
    // 依赖的其他资源包类
    public $depends = [
        'common\assets\CommonAsset',
        'common\assets\LayerAsset',
        'common\assets\LayerUiAsset'
    ];
}
