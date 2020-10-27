<?php

namespace console\controllers;

use common\helpers\StringHelper;
use console\components\Worker;

/**
 * Http Server base on Workerman.
 * 
 * @author ChisWill
 */
class HttpController extends \common\components\ConsoleController
{
    public function actionIndex()
    {
        $worker = new Worker('http://0.0.0.0:9501');

        $worker->onMessage = function($connection, $request) {
            $path = StringHelper::explode('/', $request->path());
            $action = array_shift($path) ?: 'index';
            if (method_exists($this, $action)) {
                $connection->send(call_user_func([$this, $action]));
            }
        };

        // 运行worker
        Worker::runAll();
    }

    public function actionTest()
    {
        echo 'ok';
    }

    public function index()
    {
        $name = 'mary';

        return $this->render('index', compact('name'));
    }
}
