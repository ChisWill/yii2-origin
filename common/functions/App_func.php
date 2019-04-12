<?php

use common\helpers\ArrayHelper;

function phpEncipher($input, $output, $options = [])
{
    require_once path('@common/classes/Encipher.php');
    $encipher = new Encipher($input, $output);
    $encipher->mode = ArrayHelper::getValue($options, 'mode', 1);
    $encipher->isRecursion = ArrayHelper::getValue($options, 'isRecursion', true);
    $encipher->encode();
}

function uploadCloud($filePath, $fileName, $bucket = 'senluokeji')
{
    require_once Yii::getAlias('@vendor/qiniu') . '/autoload.php';

    $ak = config('cloudAccessKey');
    $sk = config('cloudSecretKey');
    $auth = new \Qiniu\Auth($ak, $sk);
    $bucket = $bucket;
    $token = $auth->uploadToken($bucket);
    $upload = new \Qiniu\Storage\UploadManager;
    return $upload->putFile($token, $fileName, $filePath);
}