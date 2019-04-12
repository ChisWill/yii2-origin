<?php

namespace common\helpers;

use Yii;

class Third
{
    /**
     * 生成二维码
     * 
     * @param  string  $url  二维码内容
     * @param  int     $size 尺寸大小等级
     * @return string
     */
    public static function qrcode($url, $size = 6)
    {
        $filePath = config('uploadPath') . '/qrcode/' . date('Ymd') . '/';
        $dir = Yii::getAlias('@webroot' . $filePath);
        FileHelper::mkdir($dir);
        if (user()->isGuest) {
            $fileName = substr(md5($url . $size), 8, 16) . '.png';
        } else {
            $fileName = substr(md5(u()->id . $url . $size), 8, 16) . '.png';
        }
        if (!file_exists($dir . $fileName)) {
            \QRcode::png($url, $dir . $fileName, 'L', $size, 1);
        }
        return $filePath . $fileName;
    }

    /**
     * 获取 ip 信息
     * 每个IP最多尝试去获取 3 次
     *
     * @param  string ip
     * @return array
     */
    private static $_ips = null;
    public static function getIpInfo($ip)
    {
        if (self::$_ips === null) {
            self::$_ips = cache('IP_INFO_MAPS') ?: [];
        }
        if (isset(self::$_ips[$ip]) 
            && is_int(self::$_ips[$ip]) && self::$_ips[$ip] <= 3 
            || !isset(self::$_ips[$ip])) {
            $result = json_decode(Curl::get('http://opendata.baidu.com/api.php?co=&resource_id=6006&t=1433920989928&ie=utf8&oe=utf-8&format=json&query=' . $ip), true);
            $location = ArrayHelper::getValue($result, 'data.0.location', '-');
            if ($location === '-') {
                if (isset(self::$_ips[$ip])) {
                    if (is_int(self::$_ips[$ip])) {
                        self::$_ips[$ip]++;
                    }
                    if (self::$_ips[$ip] > 3) {
                        self::$_ips[$ip] = $location;
                    }
                } else {
                    self::$_ips[$ip] = 1;
                }
            }
            cache('IP_INFO_MAPS', self::$_ips);
            return $location;
        } else {
            return self::$_ips[$ip];
        }
    }

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