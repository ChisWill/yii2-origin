<?php

namespace common\assets;

use Yii;

/**
 * 引入 laydate 插件
 *
 * @author ChisWill
 */
class DatePickerAsset extends \common\components\AssetBundle
{
    public $sourcePath = '@bower/laydate';
    public $js = [
        'laydate.js'
    ];
    public $depends = [
        'common\assets\CommonAsset'
    ];

    public function init()
    {
        parent::init();

        $view = Yii::$app->getView();

        $view->registerJs('$.listen("datetimepicker");');
        $view->registerJs('$.listen("datepicker");');
        $view->registerJs('$.listen("timepicker");');
        $view->registerJs('$.listen("dateRange", "startdate");');
        $view->registerJs('$.listen("timeRange", "starttime");');
    }
}
