<?php

namespace common\modules\manual\assets;

/**
 * 手册工具的编辑页面的静态资源
 *
 * @author ChisWill
 */
class ManualEditAsset extends \common\components\AssetBundle
{
    public $sourcePath = '@common/modules/manual/static';
    public $js = [
        'edit.js'
    ];
    public $depends = [
        'common\modules\manual\assets\ManualAsset',
        'common\assets\SortableAsset'
    ];
}
