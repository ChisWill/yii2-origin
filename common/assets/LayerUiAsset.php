<?php

namespace common\assets;

use Yii;

/**
 * 引入 LayerUi 插件
 *
 * @author ChisWill
 */
class LayerUiAsset extends \common\components\AssetBundle
{
    public $sourcePath = '@bower/layui/dist';
    public $css = [
        'css/layui.css'
    ];
    public $depends = [
        'common\assets\CommonAsset'
    ];
}
