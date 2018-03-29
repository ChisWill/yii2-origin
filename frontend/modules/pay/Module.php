<?php

namespace pay;

use Yii;

/**
 * pay 模块
 */
class Module extends \common\components\Module
{
    public function beforeAction($action)
    {
        $this->allowActions = array_merge($this->allowActions, ['notify', 'return']);

        return parent::beforeAction($action);
    }
}
