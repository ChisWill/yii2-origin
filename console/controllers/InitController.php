<?php

namespace console\controllers;

use Yii;
use common\helpers\FileHelper;

class InitController extends \common\components\ConsoleController
{
    public function actionApp()
    {
        $dirs = [
            'frontend' => [
                'runtime',
                'web/assets',
                'web/uploadfile'
            ],
            'console' => [
                'runtime'
            ],
            'api' => [
                'runtime'
            ]
        ];
        foreach ($dirs as $app => $items) {
            $bathPath = Yii::getAlias('@' . $app);
            foreach ($items as $dir) {
                $path = $bathPath . '/' . $dir;
                FileHelper::mkdir($path);
                exec('chmod -R 777 ' . $path);
            }
        }
    }
}
