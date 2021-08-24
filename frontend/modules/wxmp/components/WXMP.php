<?php

namespace wxmp\components;

use common\helpers\Curl;
use Exception;
use yii\base\Object;

class WXMP extends Object
{
    const TOKEN = 'XuanLingYe1314';
    const APP_ID =  'wxde84060cda0df020';
    const APP_SECRET =  '8e36e486ad72a1a118251944b63e6abc';

    public static function getAccessToken()
    {
        $keyName = self::TOKEN . '-ACCESS_TOKEN';
        $token = redis()->get($keyName);
        if (!$token) {
            $url = sprintf('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s', self::APP_ID, self::APP_SECRET);
            $result = json_decode(Curl::get($url), true);
            $token = $result['access_token'] ?? '';
            $expire = $result['expires_in'] ?? 0;
            if (!$token) {
                $errmsg = $result['errmsg'] ?? 'unknown error on getAccessToken';
                $errcode = $result['errcode'] ?? -2;
                throw new Exception($errmsg, $errcode);
            }
            redis()->set($keyName, $token, $expire);
        }
        return $token;
    }
}
