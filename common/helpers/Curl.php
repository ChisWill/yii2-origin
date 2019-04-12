<?php

namespace common\helpers;

/**
 * RESTful Api 的快捷调用助手类
 *
 * [options]:
 * -format: 返回数据类型，可选范围：json|xml
 * -timeout: 执行最大时间，默认为1秒，可输入小数
 * -header: 设置头信息参数
 * 
 * ps.其他 curl 的参数，将通过 `curl_setopt_array()` 直接设置
 * 
 * @author ChisWill
 */
class Curl
{
    protected static $curl = null;

    protected static function init($options)
    {
        self::$curl = curl_init();

        $format = ArrayHelper::remove($options, 'format', 'json');
        $header = ['Accept:application/' . $format];
        $header = array_merge($header, (array) ArrayHelper::remove($options, 'header', []));
        curl_setopt(self::$curl, CURLOPT_HTTPHEADER, $header);
        
        $timeout = ArrayHelper::remove($options, 'timeout', 3);
        if ($timeout >= 1) {
            curl_setopt(self::$curl, CURLOPT_TIMEOUT, $timeout);
        } else {
            curl_setopt(self::$curl, CURLOPT_TIMEOUT_MS, $timeout * 1000);
        }

        curl_setopt(self::$curl, CURLOPT_HEADER, false);
        curl_setopt(self::$curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(self::$curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt(self::$curl, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt_array(self::$curl, $options);
    }

    protected static function end()
    {
        curl_close(self::$curl);
    }

    public static function get($url, $options = [])
    {
        static::init($options);

        curl_setopt(self::$curl, CURLOPT_URL, $url);
        curl_setopt(self::$curl, CURLOPT_CUSTOMREQUEST, 'GET');

        $result = curl_exec(self::$curl);

        static::end();

        return $result;
    }

    public static function post($url, $data, $options = [])
    {
        static::init($options);

        curl_setopt(self::$curl, CURLOPT_URL, $url);
        curl_setopt(self::$curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt(self::$curl, CURLOPT_POSTFIELDS, $data);

        $result = curl_exec(self::$curl);

        static::end();

        return $result;
    }

    public static function put($url, $data, $options = [])
    {
        static::init($options);

        curl_setopt(self::$curl, CURLOPT_URL, $url);
        curl_setopt(self::$curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt(self::$curl, CURLOPT_POSTFIELDS, $data);

        $result = curl_exec(self::$curl);

        static::end();

        return $result;
    }

    public static function delete($url, $options = [])
    {
        static::init($options);

        curl_setopt(self::$curl, CURLOPT_URL, $url);
        curl_setopt(self::$curl, CURLOPT_CUSTOMREQUEST, 'DELETE');

        $result = curl_exec(self::$curl);

        static::end();

        return $result;
    }
}
