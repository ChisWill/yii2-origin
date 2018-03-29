<?php

namespace common\modules\game\assets;

/**
 * 游戏的静态资源
 *
 * @author ChisWill
 */
class GameAsset extends \common\components\AssetBundle
{
    public $sourcePath = '@common/modules/game/static';
    public $css = [
        'game.css'
    ];
    public $js = [
        'game.js'
    ];
    public $depends = [
        'common\assets\CommonAsset'
    ];
}