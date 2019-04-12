<?php

namespace php\controllers;

use Yii;
use php\models\Obfuscate;
use common\helpers\FileHelper;

class EncryptController extends \php\components\Controller
{
    public function actionIndex()
    {
        $obfuscate = new Obfuscate;

        if ($obfuscate->load()) {
            if ($obfuscate->validate()) {
                 if ($obfuscate->file->move()) {
                    session('obfuscateConfig', $obfuscate->config);
                    session('obfuscateFilePath', $obfuscate->file->filePath);
                    session('obfuscateFileName', $obfuscate->file->name);
                    return success();
                } else {
                    return error(t('Upload failed.'));
                }
            } else {
                return error($obfuscate);
            }
        }

        return $this->render('index', compact('obfuscate'));
    }

    public function actionObfuscate()
    {
        $filePath = session('obfuscateFilePath') ?: '';
        $fileName = session('obfuscateFileName') ?: 'output.php';
        $config = session('obfuscateConfig') ?: [];
        $input = Yii::getAlias('@webroot' . $filePath);
        if (!file_exists($input)) {
            return error(t('Obfuscate failed.'));
        }
        $obfuscate = new Obfuscate($input);
        $obfuscate->config = $config;
        $file = $obfuscate->run();
        if ($file !== false) {
            session('obfuscateOutputPath', $obfuscate->outputPath);
            session('obfuscateDownloadFile', $file);
            return success();
        } else {
            return error(t('Obfuscate failed.'));
        }
    }

    public function actionDownload()
    {
        try {
            $file = session('obfuscateDownloadFile');
            $fileName = session('obfuscateFileName');
            $outputPath = session('obfuscateOutputPath');
            session('obfuscateConfig', null);
            session('obfuscateFileName', null);
            session('obfuscateFilePath', null);
            session('obfuscateDownloadFile', null);
            session('obfuscateOutputPath', null);
            $response = $this->download($file, $fileName)->send();
            FileHelper::removeDirectory($outputPath);
            return $response;
        } catch (\Exception $e) {
            throwex(t('Download failed.'));
        }
    }
}
