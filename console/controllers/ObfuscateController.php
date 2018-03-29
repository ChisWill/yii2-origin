<?php

namespace console\controllers;

use Yii;
use common\models\Obfuscate;

class ObfuscateController extends \common\components\ConsoleController
{
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        } else {
            return true;
        }
    }

    public function actionIndex($file)
    {
        foreach (func_get_args() as $file) {
            $obfuscate = new Obfuscate($file);
            $path = $obfuscate->run();
        }
    }
}
