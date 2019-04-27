<?php

namespace common\helpers;

class Url extends \yii\helpers\BaseUrl
{
    /**
     * 根据数组参数生成URL地址，并可选择是否添加额外的防篡改参数
     *
     * @param  string  $url    要生成的网址
     * @param  array   $params URL参数
     * @param  boolean $hash   是否添加hash参数
     * @return string
     */
    public static function create($url, $params = [], $hash = false)
    {
        if ($hash === true) {
            $params['@_@'] = self::getSign($params);
        }
        $info = parse_url($url);
        $url = explode('?', $url)[0];
        if (isset($info['query'])) {
            $d = $params ? '&' : '';
            return $url . '?' . $info['query'] . $d . http_build_query($params);
        } else {
            $d = $params ? '?' : '';
            return $url . $d . http_build_query($params);
        }
    }

    /**
     * 检查URL地址是否被篡改过
     * @param  string  $url 要检查的URL地址
     * @return boolean
     */
    public static function check()
    {
        $get = get();
        if (!empty($get) && isset($get['@_@'])) {
            $old = $get['@_@']; 
            unset($get['@_@']);
            $new = self::getSign($get);
            return $old === $new;
        }
        return true;
    }

    private static function getSign($params)
    {
        $key = SECRET_KEY;
        $values = array_merge(array_values($params), [$key]);
        return substr(md5(implode('', $values)), 3, 13);
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
