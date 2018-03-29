<?php

namespace common\helpers;

class Url extends \yii\helpers\BaseUrl
{
    /**
     * 生成一个带参数的URL地址
     *
     * @param  string $url    要生成的网址
     * @param  array  $params URL参数
     * @return string
     */
    public static function create($url, $params = [])
    {
        $d = '?';
        foreach ($params as $key => $value) {
            $url .= $d . $key . '=' . $value;
            $d = '&';
        }
        return $url;
    }

    /**
     * url安全的base64编码
     * 
     * @param  string $string
     * @return string
     */
    public static function base64encode($string)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($string));
    }

    /**
     * url安全的base64解码
     * 
     * @param  string $string
     * @return string
     */
    public static function base64decode($string)
    {
        $string = str_replace(['-', '_'], ['+', '/'], $string);
        $mod4 = strlen($string) % 4;
        if ($mod4) {
            $string .= substr('====', $mod4);
        }
        return base64_decode($string);
    }
}
