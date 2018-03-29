<?php

namespace common\modules\manual\assets;

/**
 * 手册工具的查看页面的静态资源
 *
 * @author ChisWill
 */
class ManualViewAsset extends \common\components\AssetBundle
{
    public $sourcePath = '@common/modules/manual/static';
    public $js = [
        'view.js'
    ];
    public $depends = [
        'common\modules\manual\assets\ManualAsset'
    ];
}
