<?php

namespace common\helpers;

use common\helpers\ArrayHelper;
use Exception;

/**
 * RESTful Api 的快捷调用助手类
 *
 * [options]:
 * -format: 返回数据类型，可选范围：json|xml
 * -timeout: 执行最大时间，默认为1秒，可输入小数
 * -header: 设置头信息参数
 * 
 * ps.其他 curl 的参数，通过 $options 参数直接按键值对方式传入即可
 * 
 * @author ChisWill
 */
class Curl
{
    public static function get($url, $options = [])
    {
        $handle = new CurlHandle($options);

        curl_setopt($handle->ch, CURLOPT_URL, $url);
        curl_setopt($handle->ch, CURLOPT_CUSTOMREQUEST, 'GET');

        $result = $handle->exec();

        $handle->close();

        return $result;
    }

    public static function post($url, $data, $options = [])
    {
        $handle = new CurlHandle($options);

        curl_setopt($handle->ch, CURLOPT_URL, $url);
        curl_setopt($handle->ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($handle->ch, CURLOPT_POSTFIELDS, $data);

        $result = $handle->exec();

        $handle->close();

        return $result;
    }

    public static function put($url, $data, $options = [])
    {
        $handle = new CurlHandle($options);

        curl_setopt($handle->ch, CURLOPT_URL, $url);
        curl_setopt($handle->ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($handle->ch, CURLOPT_POSTFIELDS, $data);

        $result = $handle->exec();

        $handle->close();

        return $result;
    }

    public static function delete($url, $options = [])
    {
        $handle = new CurlHandle($options);

        curl_setopt($handle->ch, CURLOPT_URL, $url);
        curl_setopt($handle->ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

        $result = $handle->exec();

        $handle->close();

        return $result;
    }

    protected static function initParams($urls, $data, $options, $batch, $callback)
    {
        $params = [];
        if (is_string($urls) && $batch > 0) {
            for ($i = 0; $i < $batch; $i++) {
                $params[$i] = call_user_func($callback, $options, $urls, $data);
            }
        } elseif (is_array($urls)) {
            if ($batch !== false) {
                foreach ($urls as $k => $url) {
                    $params[$k] = call_user_func($callback, $options, $url, $data);
                }
            } else {
                foreach ($urls as $k => $url) {
                    $params[$k] = call_user_func($callback, $options[$k], $url, ArrayHelper::getValue($data, $k));
                }
            }
        } else {
            throw new Exception('参数格式错误');
        }
        return $params;
    }

    public static function postMulti($urls, $data, $options = [], $batch = 1)
    {
        $params = static::initParams($urls, $data, $options, $batch, function ($option, $url, $data) {
            $option[CURLOPT_URL] = $url;
            $option[CURLOPT_CUSTOMREQUEST] = 'POST';
            $option[CURLOPT_POSTFIELDS] = $data;
            return $option;
        });

        $handle = new CurlHandle($params, true);

        $handle->execMulti();

        $results = $handle->getResults();

        $handle->closeMulti();

        return $results;
    }

    public static function getMulti($urls, $options = [], $batch = 1)
    {
        $params = static::initParams($urls, [], $options, $batch, function ($option, $url) {
            $option[CURLOPT_URL] = $url;
            $option[CURLOPT_CUSTOMREQUEST] = 'GET';
            return $option;
        });

        $handle = new CurlHandle($params, true);

        $handle->execMulti();

        $results = $handle->getResults();

        $handle->closeMulti();

        return $results;
    }
}

class CurlHandle
{
    public $ch;
    public $mch;
    protected $handles = [];

    //-------------------------------------------------
    //                以下为标准 Curl 方法
    //-------------------------------------------------
    public function __construct($options, $isMulti = false)
    {
        if ($isMulti === false) {
            $this->initCurl($options);
        } else {
            $this->initMultiCurl($options);
        }
    }

    protected function initCurl($options)
    {
        $this->ch = curl_init();
        $format = ArrayHelper::remove($options, 'format', 'json');
        $header = ['Accept:application/' . $format];
        $header = array_merge($header, (array) ArrayHelper::remove($options, 'header', []));
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $header);

        $timeout = ArrayHelper::remove($options, 'timeout', 10);
        if ($timeout >= 1) {
            curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout);
        } else {
            curl_setopt($this->ch, CURLOPT_TIMEOUT_MS, $timeout * 1000);
        }

        curl_setopt($this->ch, CURLOPT_HEADER, false);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($this->ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Trident/7.0; rv:11.0) like Gecko');
        curl_setopt($this->ch, CURLOPT_ENCODING, "gzip");

        curl_setopt_array($this->ch, $options);
    }

    public function close()
    {
        curl_close($this->ch);
    }

    public function exec()
    {
        return curl_exec($this->ch);
    }

    //-------------------------------------------------
    //                以下为 Curl_multi 相关方法
    //-------------------------------------------------
    protected function initMultiCurl($params)
    {
        $this->mch = curl_multi_init();

        for ($i = 0; $i < count($params); $i++) {
            $handle = new CurlHandle($params[$i]);
            curl_multi_add_handle($this->mch, $handle->ch);
            $this->handles[] = $handle;
        }
    }

    public function closeMulti()
    {
        curl_multi_close($this->mch);
    }

    public function execMulti()
    {
        $active = false;
        do {
            $mrc = curl_multi_exec($this->mch, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active && $mrc == CURLM_OK) {
            if (curl_multi_select($this->mch) != -1) {
                usleep(50);
            }
            do {
                $mrc = curl_multi_exec($this->mch, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }
    }

    public function getResults()
    {
        $results = [];
        foreach ($this->handles as $k => $handle) {
            if (curl_error($handle->ch) == '') {
                $results[$k] = (string) curl_multi_getcontent($handle->ch);
            }
            curl_multi_remove_handle($this->mch, $handle->ch);
            $handle->close();
        }
        return $results;
    }
}