<?php

namespace frontend\assets;

/**
 * 会员中心基础静态资源
 */
class UserAsset extends \common\components\AssetBundle
{
   public $js = [
        'js/user.js'
    ];
    public $css = [
        'css/reset.css',
        'css/user.css'
    ];
    public $depends = [
        'common\assets\CommonAsset'
    ];
}
