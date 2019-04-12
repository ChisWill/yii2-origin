<?php

namespace chat\controllers;

use Yii;

class SiteController extends \chat\components\Controller
{
    public $layout = 'main';

    public function actionIndex()
    {
        $this->view->title = '首页 - chat';

        return $this->redirect(['debug']);
    }

    public function actionServer()
    {
        $this->layout = 'empty';
        self::offEvent();
        return $this->render('server');
    }

    public function actionDebug()
    {
        return $this->render('debug');
    }
}
