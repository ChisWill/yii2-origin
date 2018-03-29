<?php

namespace php\controllers;

use Yii;
use common\models\Obfuscate;
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
            throwex(t('Obfuscate failed.'));
        }
        $obfuscate = new Obfuscate($input);
        $obfuscate->config = $config;
        $file = $obfuscate->run();
        if ($file !== false) {
            try {
                session('obfuscateConfig', null);
                session('obfuscateFilePath', null);
                session('obfuscateFileName', null);
                $response = $this->download($file, $fileName)->send();
                FileHelper::removeDirectory($obfuscate->outputPath);
            } catch (\Exception $e) {
                FileHelper::removeDirectory($obfuscate->outputPath);
                throwex(t('Uploaded file can not be obfuscated.'));
            }
            return $response;
        } else {
            throwex(t('Obfuscate failed.'));
        }
    }
}
