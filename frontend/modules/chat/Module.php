<?php

namespace chat;

use Yii;

/**
 * 客户聊天模块
 */
class Module extends \common\components\Module implements \yii\base\BootstrapInterface
{
    public $loginRequired = false;

    public function bootstrap($app)
    {
        if ($app instanceof \yii\web\Application) {
            $app->on($app::EVENT_AFTER_REQUEST, function () use ($app) {
                $view = $app->getView();
                $view->on($view::EVENT_END_BODY, [$this, 'renderChatBar']);
            });
        }
    }

    public function renderChatBar($event)
    {
        $view = $event->sender;

        echo $view->render('@chat/views/site/index');
    }
}
