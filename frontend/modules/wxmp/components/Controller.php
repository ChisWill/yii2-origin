<?php

namespace wxmp\components;

use Yii;

/**
 * wxmp 控制器的基类
 */
class Controller extends \common\components\WebController
{
    public function init()
    {
        parent::init();
    }
    
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        } else {
            return true;
        }
    }
}
