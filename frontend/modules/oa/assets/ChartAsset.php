<?php

namespace oa\assets;

/**
 * @author ChisWill
 */
class ChartAsset extends \common\components\AssetBundle
{
    public $sourcePath = '@oa/static/chart';
    public $js = [
        'chart.js'
    ];
    public $depends = [
        'common\assets\EChartsAsset'
    ];
}
