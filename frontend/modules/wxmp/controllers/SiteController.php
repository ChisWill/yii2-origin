<?php

namespace wxmp\controllers;

use wxmp\components\WXMP;
use Yii;

class SiteController extends \wxmp\components\Controller
{
    public $layout = 'main';

    public function actionIndex()
    {
        if (req()->isPost) {
            $message = new MessageController;
            $message->do();
        } else {
            $signature = $_GET["signature"];
            $timestamp = $_GET["timestamp"];
            $nonce = $_GET["nonce"];
            $echostr = $_GET["echostr"];
    
            $token = WXMP::TOKEN;
            $tmpArr = array($token, $timestamp, $nonce);
            sort($tmpArr, SORT_STRING);
            $tmpStr = implode($tmpArr);
            $tmpStr = sha1($tmpStr);
    
            if ($tmpStr == $signature) {
                echo $echostr;
            } else {
                echo "fail";
            }
        }
    }

    public function actionGetToken()
    {
        $r = WXMP::getAccessToken();
        test($r);
    }
}
