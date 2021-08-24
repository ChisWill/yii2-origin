<?php

namespace wxmp\controllers;

use admin\models\User;
use common\helpers\ArrayHelper;

class MessageController
{
    private $msgType;
    private $body;

    public function __construct()
    {
        $xml = file_get_contents("php://input");
        $params = ArrayHelper::fromXml($xml);
        $this->msgType = $params['MsgType'];
        $this->body = $params;
    }

    public function do()
    {
        switch ($this->msgType) {
            case 'text';
                $content = [
                    'ToUserName' => $this->body['FromUserName'],
                    'FromUserName' => $this->body['ToUserName'],
                    'CreateTime' => time(),
                    'MsgType' => 'text',
                    'Content' => sprintf('随机1-100字符串：%d', mt_rand(1, 100)),
                ];
                echo ArrayHelper::toXml($content);
                break;
            default:
                echo 'success';
        }
    }
}
