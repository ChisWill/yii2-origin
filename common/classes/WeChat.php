<?php

namespace common\classes;

use Yii;
use common\helpers\Curl;

class WeChat extends \yii\base\Object
{
    protected $_accessToken = null;

    public function getAccessToken()
    {
        $accessToken = $this->getTokenCache();
        if (!empty($accessToken)) {
            $this->_accessToken = $accessToken;
        } else {
            $url = sprintf('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s', config('wx_appid'), config('wx_appsecret'));
            $result = $this->curlRequest($url);
            if (!empty($result->access_token)) {
                $this->_accessToken = $result->access_token;
                $this->setTokenCache(6000);
            } else {
                l('accessToken 获取失败!' . PHP_EOL . serialize($result), 'wx');
            }
        }
        return $this->_accessToken;
    }

    public function getUserInfo()
    {
        $url = sprintf('https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_base&state=1#wechat_redirect', config('wx_appid'), urlencode('http://origin.chiswill.cc'));
        return $url;
    }

    protected function curlRequest($url, $data = null)
    {
        $options = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ];
        if ($data === null) {
            $ret = Curl::get($url, $options);
        } else {
            $ret = Curl::post($url, $data, $options);
        }
        return json_decode($ret);
    }

    protected function getTokenCache()
    {
        $now = time();
        $option = option('wxAccessToken');
        if (!$option) {
            $option = ['token' => null, 'time' => $now, 'limit' => 0];
        }

        if ($option['limit'] === 0) {
            return $option['token'];
        } elseif ($now - $option['time'] > $option['limit']) {
            return null;
        } else {
            return $option['token'];
        }
    }

    protected function setTokenCache($limit = 0)
    {
        $data = [
            'token' => $this->_accessToken,
            'time' => time(),
            'limit' => $limit
        ];
        $option = option('wxAccessToken', $data);
    }
}
