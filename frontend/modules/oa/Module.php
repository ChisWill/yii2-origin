<?php

namespace oa;

use Yii;
use common\helpers\Html;

/**
 * OA模块启动文件
 *
 * @author ChisWill
 */
class Module extends \common\components\Module
{
    public function init()
    {
        parent::init();

        \admin\Module::moduleInit();
    }
}
