<?php

namespace common\modules\manual\assets;

/**
 * 手册工具的历史版本页面的静态资源
 *
 * @author ChisWill
 */
class ManualHistoryAsset extends \common\components\AssetBundle
{
    public $sourcePath = '@common/modules/manual/static';
    public $js = [
        'history.js'
    ];
    public $css = [
        'history.css'
    ];
    public $depends = [
        'common\assets\CommonAsset',
        'common\assets\LayerAsset'
    ];
}
