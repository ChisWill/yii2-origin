<?php

namespace common\components;

use Yii;

/**
 * 模块的基类，增加默认路由，并且增加是否需要登录才能访问的功能
 *
 * @author ChisWill
 */
class Module extends \yii\base\Module
{
    use \common\traits\ChisWill;
    
    /**
     * @var string 默认路由
     */
    public $defaultRoute = 'site';
    /**
     * @var boolean 是否必须登录
     */
    public $loginRequired = true;
    /**
     * @var string 允许访问的action
     */
    public $allowActions = ['login', 'captcha'];

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        } elseif (YII_ENV_PROD && !in_array($this->id, config('accessModule'))) {
            return false;
        }

        $nowAction = $action->id;
        if ($this->loginRequired === true && user()->isGuest && !in_array($nowAction, $this->allowActions)) {
            user()->loginRequired();
            return false;
        } else {
            return true;
        }
    }
}
