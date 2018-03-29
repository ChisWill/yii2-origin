<?php

namespace oa\assets;

/**
 * @author ChisWill
 */
class MainAsset extends \common\components\AssetBundle
{
    public $sourcePath = '@oa/static/main';
    public $js = [
        'main.js'
    ];
    public $css = [
        'main.css'
    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];
    public $depends = [
        'admin\assets\MainAsset',
        'common\assets\SocketIOAsset',
        'common\assets\INotifyAsset'
    ];
}
