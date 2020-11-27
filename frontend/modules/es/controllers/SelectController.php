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
            'all' => '所有指定索引下的所有文档',
            'mget' => '获取多个文档',
        ];
        return $this->render('index', compact('list'));
    }

    public function actionAll()
    {
        $r = $this->esSearch();
        return $this->response($r);
    }

    public function actionMget()
    {
        $params = [
            'body' => [
                'index' => 'faker',
                'id' => ''
            ]
        ];
        $r = $this->client->mget($params);
        return $this->response($r);
    }
}
