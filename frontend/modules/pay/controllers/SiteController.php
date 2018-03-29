<?php

namespace pay\controllers;

use Yii;
use pay\components\Pay;

class SiteController extends \pay\components\Controller
{
    public $layout = 'main';

    public function actionIndex()
    {
        $model = new Pay;

        if ($model->load()) {
            if ($model->validate()) {
                return success($model->ready(), $model->code);
            } else {
                return error($model);
            }
        }

        return $this->render('index', compact('model'));
    }

    public function actionReturn($type)
    {
        Pay::notify($type);

        return $this->redirect(['/']);
    }

    public function actionNotify($type)
    {
        echo Pay::notify($type);
    }
}
