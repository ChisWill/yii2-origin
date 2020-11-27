<?php

namespace es\controllers;

use Yii;

class SiteController extends \es\components\Controller
{
    public $layout = 'main';

    public function init()
    {
        parent::init();

        $this->view->title = '首页 - ' . $this->view->title;
    }

    public function actionIndex()
    {
        $modules = [
            'select' => '查询',
            'insert' => '插入',
            'update' => '更新',
            'delete' => '删除',
        ];
        return $this->render('index', compact('modules'));
    }
}
