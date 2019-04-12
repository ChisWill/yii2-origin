<?php

namespace php;

use Yii;

/**
 * php 模块
 *
 * 模块复制说明
 * 1. 复制整个模块代码
 * 2. 配置路径别名和模块路由
 * 3. 复制yakpro-po类库
 */
class Module extends \common\components\Module
{
    public $loginRequired = false;

    public function init()
    {
        parent::init();
        // 路由优化
        Yii::$app->getUrlManager()->addRules([
            $this->id => $this->id . '/site/index',
            $this->id . '/encrypt' => $this->id . '/encrypt/index',
            $this->id . '/account' => $this->id . '/account/index'
        ], false);
        // 设置登录页面
        Yii::$app->user->loginUrl = ['php/site/login'];
        // 设置错误文件
        Yii::$app->log->targets['system']->logFile = Yii::getAlias('@runtime/logs/php.log');
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
