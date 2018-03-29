<?php

namespace common\modules\game\assets;

/**
 * 数和的静态资源
 *
 * @author ChisWill
 */
class GameShuhuiAsset extends \common\components\AssetBundle
{
    public $sourcePath = '@common/modules/game/static';
    public $css = [
        'shuhui.css'
    ];
    public $js = [
        'shuhui.js'
    ];
    public $depends = [
        'common\modules\game\GameAsset',
        // 'common\assets\ZRenderAsset'
    ];
}