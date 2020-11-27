<?php

namespace es\controllers;

use Yii;

class SelectController extends \es\components\Controller
{
    public $layout = 'main';

    public function init()
    {
        parent::init();

        $this->view->title = '搜索 - ' . $this->view->title;
    }

    public function actionIndex()
    {
        $list = [
            'all' => '所有',
        ];
        return $this->render('index', compact('list'));
    }

    public function actionAll()
    {
        $r = $this->curlSearch('megacorp');
        return $this->response($r);
    }
}
