<?php

namespace common\assets;

use Yii;

/**
 * 引入 Jquery-ui-datepicker 插件
 *
 * @author ChisWill
 */
class DatePickerAsset extends \common\components\AssetBundle
{
    public $sourcePath = '@bower/jquery-ui';
    public $js = [
        'ui/minified/datepicker.min.js'
    ];
    public $css = [
        'themes/smoothness/jquery-ui.min.css'
    ];
    public $depends = [
        'common\assets\CommonAsset'
    ];

    public function init()
    {
        parent::init();

        $view = Yii::$app->getView();

        $view->registerJs('$.listen("datepicker");');
    }
}
