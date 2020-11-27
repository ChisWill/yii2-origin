<?php

namespace es\controllers;

use Yii;

class DeleteController extends \es\components\Controller
{
    public $layout = 'main';

    public function init()
    {
        parent::init();

        $this->view->title = '删除 - ' . $this->view->title;
    }

    public function actionIndex()
    {
        $list = [
            // 'simple' => '简单查询',
        ];
        return $this->render('index', compact('list'));
    }
}
