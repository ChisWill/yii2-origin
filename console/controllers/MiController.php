<?php

namespace console\controllers;

use Yii;
use common\helpers\FileHelper;

/**
 * Custom migration application.
 */
class MiController extends \common\components\ConsoleController
{
    public function actionIndex($appName = null)
    {
        if ($appName) {
            $this->migrate($appName);
            return;
        }

        echo 'This is a customized Migration Tool.' . "\n\n";

        echo 'Here are all application: ' . implode(', ', FileHelper::getApps(['common', 'console', 'api'])) . ".\n\n";

        echo 'You could use command `yii mi frontend` to execute.' . "\n";
    }

    public function actionData($appName = null)
    {
        $generator = new \common\modules\wizard\generators\migrate\Generator;
        $generator->appName = $appName;
        try {
            $files = $generator->getDataFiles();

            $count = count($files);

            if ($count > 0) {
                fwrite(STDOUT, sprintf('Total %d files, update? [y/n]' . "\n", $count));
                $answer = fgets(STDIN);
                if (strpos($answer, 'y') === 0) {
                    $generator->syncData($files);
                    echo 'Update ' . $count . ' Data Success.';
                } else {
                    echo 'Over.';
                }
            } else {
                echo 'No Data.';
            }
            echo "\n";
        } catch (\Exception $e) {
            die($e->getMessage() . "\n");
        }
    }

    /**
     * Upgrades all versions.
     */
    public function migrate($appName)
    {
        $generator = new \common\modules\wizard\generators\migrate\Generator;
        try {
            $basePath = Yii::getAlias('@' . $appName);
        } catch (\yii\base\InvalidParamException $e) {
            die($e->getMessage() . "\n");
        }
        if (!file_exists($basePath . '/' . $generator->saveFile)) {
            die('This application has no migration.' . "\n");
        } else {
            $generator->appName = $appName;
            list($successNum, $err) = $generator->syncAll();
            $successInfo = $successNum === 0 ? 'Nothing is Upgraded.' : "upgrade {$successNum} version.";
            $errInfo = $err ? "\nErrors:\n" . implode("\n", $err) : '';
            if ($successNum) {
                echo $successInfo . "\n";
            } else {
                echo $successInfo, $errInfo . "\n";
            }
        }
    }
}
