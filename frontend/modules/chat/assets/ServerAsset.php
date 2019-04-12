<?php

namespace chat\assets;

class ServerAsset extends \common\components\AssetBundle
{
    public $sourcePath = '@chat/static';
    public $js = [
        'js/server.js'
    ];
    public $depends = [
        'common\assets\CommonAsset'
    ];
}
