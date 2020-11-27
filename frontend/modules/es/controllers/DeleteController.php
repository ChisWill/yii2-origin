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
            'byid' => '根据id删除',
        ];
        return $this->render('index', compact('list'));
    }

    public function actionByid()
    {
        $params = [
            'index' => '',
            'id' => '',
        ];
        $r = $this->client->delete($params);
        $this->response($r);
    }
}
