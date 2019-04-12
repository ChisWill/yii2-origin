<?php

namespace chat\controllers;

use Yii;
use common\helpers\Inflector;
use console\components\Worker;

class PushController extends \chat\components\Controller
{
    public function actionPicture()
    {
        $upload = self::getUpload(['uploadName' => 'image', 'uploadPath' => 'images']);
        if ($upload->move()) {
            $path = $upload->filePath;
            return success($path);
        } else {
            return error($upload->getErrors());
        }
    }

    public function actionFile()
    {
        $upload = self::getUpload(['uploadName' => 'file', 'uploadPath' => 'files', 'extensions' => 'doc,docx,txt,rar', 'checkExtensionByMimeType' => false]);
        if ($upload->move()) {
            $path = $upload->filePath;
            $originName = $upload->originName;
            $size = filesize(Yii::getAlias('@webroot' . $path));
            $compact = compact('originName', 'size');
            return success($path);
        } else {
            return error($upload->getErrors());
        }
    }
}