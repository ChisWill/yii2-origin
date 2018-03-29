<?php

namespace php\components;

use Yii;

/**
 * php 控制器的基类
 */
class Controller extends \common\components\WebController
{
    public $layout = 'main';
    public $menu;
    public $route;

    public function init()
    {
        parent::init();
    }
    
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        } else {
            $this->menu = [
                'site/index' => t('Index'),
                'encrypt/index' => t('Encrypt'),
                'account/index' => t('Account'),
            ];
            $this->route = $action->controller->id . '/' . $action->id;
            return true;
        }
    }
}
