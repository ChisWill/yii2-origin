<?php

namespace frontend\controllers;

use Yii;
use common\helpers\Json;
use common\helpers\ArrayHelper;

class ApiController extends \frontend\components\Controller
{
    public function actionOne()
    {
        $config = option('apiConfig') ?: [];
        return Json::encode([
            'url' => ArrayHelper::getValue($config, 'one.url'),
            'jump' => ArrayHelper::getValue($config, 'one.jump')
        ]);
    }

    public function actionTwo()
    {
        $config = option('apiConfig') ?: [];
        return Json::encode([
            'url' => ArrayHelper::getValue($config, 'two.url'),
            'jump' => ArrayHelper::getValue($config, 'two.jump')
        ]);
    }

    public function actionSet()
    {
        if (req()->isPost) {
            $config = [];
            $config['one']['url'] = post('url1');
            $config['one']['jump'] = post('jump1');
            $config['two']['url'] = post('url2');
            $config['two']['jump'] = post('jump2');
            option('apiConfig', $config);
            return success();
        }
        return $this->render('set');
    }
}