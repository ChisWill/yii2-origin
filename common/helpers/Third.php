<?php

namespace common\helpers;

class Third
{
    /**
     * 获取短链接
     * 
     * @param  string $url 原始链接
     * @return string
     */
    public static function getShortUrl($url)
    {
        try {
            return json_decode(Curl::get('http://api.t.sina.com.cn/short_url/shorten.json?source=3271760578&url_long=' . $url), true)[0]['url_short'];
        } catch (\Exception $e) {
            throwex('短链接获取失败');
        }
    }

    /**
     * 获取美元汇率
     * 
     * @param  float $default 默认汇率，必须填写，接口有时可能会获取不到
     * @return float
     */
    public static function getUsdRate($default)
    {
        try {
            return json_decode(Curl::get('http://api.k780.com:88?app=finance.rate&scur=USD&tcur=CNY&appkey=10003&sign=b59bc3ef6191eb9f747dd4e83c99f2a4&format=json'), true)['result']['rate'];
        } catch (\Exception $e) {
            return $default;
        }
    }

    /**
     * 发送短信
     * 
     * @param  integer $mobile  目标手机号
     * @param  string  $content 发送信息
     * @return boolean
     */
    public static function sendsms($mobile, $content)
    {
        $argv = [
            'ac' => 'send',
            'uid' => SMS_UID,
            'pwd' => SMS_PWD,
            'template' => '399569',
            'mobile' => $mobile,
            'content' => urlencode('{"code":"' . $content . '"}'),
        ];
        $ret = Curl::post('http://api.sms.cn/sms/?', $argv);
        return strpos($ret, '"stat":"100"') !== false;
    }

    /**
     * 查询短信剩余条数
     */
    public static function smsnumber()
    {
        $argv = [
            'ac' => 'number',
            'uid' => SMS_UID,
            'pwd' => SMS_PWD
        ];
        $ret = Curl::post('http://api.sms.cn/sms/?', $argv);
        return json_decode($ret, true);
    }
}