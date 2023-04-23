<?php

namespace common\classes;

use common\helpers\Curl;
use common\helpers\StringHelper;
use Exception;

class YYWeChat
{
    private $appId;
    private $appKey;

    public function __construct()
    {
        $this->appId = config('yy_webapp_id');
        $this->appKey = config('yy_websecret_key');
    }

    public function getAccessToken()
    {
        $accessToken = $this->getTokenCache();
        if (!$accessToken) {
            $url = sprintf('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s', $this->appId, $this->appKey);
            $result = $this->curlRequest($url);
            if (!empty($result['access_token'])) {
                $accessToken = $result['access_token'];
                $this->setTokenCache($result['access_token'], 6000);
            } else {
                $message = 'accessToken 获取失败!' . PHP_EOL . serialize($result);
                l($message, 'wx');
                throw new Exception($message);
            }
        }
        return $accessToken;
    }

    public function getTicket($accessToken)
    {
        $ticket = $this->getTicketCache();
        if (!$ticket) {
            $url = sprintf('https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=%s&type=jsapi', $accessToken);
            $result = $this->curlRequest($url);
            if (!empty($result['ticket'])) {
                $ticket = $result['ticket'];
                $this->setTicketCache($ticket, 6000);
            } else {
                $message = 'JsapiTicket 获取失败!' . PHP_EOL . serialize($result);
                l($message, 'wx');
                throw new Exception($message);
            }
        }
        return $ticket;
    }

    public function getConfigParams($url, $ticket)
    {
        $params = [
            'noncestr' => StringHelper::random(16, 'w'),
            'jsapi_ticket' => $ticket,
            'timestamp' => time(),
            'url' => $url,
        ];
        ksort($params);
        $string = $d = '';
        foreach ($params as $k => $v) {
            $string .= $d . $k . '=' . $v;
            $d = '&';
        }
        $params['appId'] = $this->appId;
        $params['signature'] = sha1($string);
        return $params;
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
        return json_decode($ret, true);
    }

    protected function getTokenCache()
    {
        $now = time();
        $option = option('yywxAccessToken');
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

    protected function setTokenCache($token, $limit = 0)
    {
        option('yywxAccessToken', [
            'token' => $token,
            'time' => time(),
            'limit' => $limit
        ]);
    }

    protected function getTicketCache()
    {
        $now = time();
        $option = option('yywxJsTicket');
        if (!$option) {
            $option = ['ticket' => null, 'time' => $now, 'limit' => 0];
        }

        if ($option['limit'] === 0) {
            return $option['ticket'];
        } elseif ($now - $option['time'] > $option['limit']) {
            return null;
        } else {
            return $option['ticket'];
        }
    }

    protected function setTicketCache($ticket, $limit = 0)
    {
        option('yywxJsTicket', [
            'ticket' => $ticket,
            'time' => time(),
            'limit' => $limit
        ]);
    }
}
