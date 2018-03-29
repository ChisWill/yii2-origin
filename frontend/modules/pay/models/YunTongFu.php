<?php

namespace pay\models;

use Yii;

class YunTongFu extends \pay\components\Pay
{
    const URL = 'http://www.passpay.net/PayOrder/payorder';
    const PARTNER = '583650863614627';
    const USER_SELLER = '321425';
    const KEY = 'Hdngi85MPD65GaxvERkkX8DKqJfGyNt5';

    public function ready()
    {
        $params = [
            'partner' => self::PARTNER,
            'user_seller' => self::USER_SELLER,
            'out_order_no' => $this->trade_no,
            'subject' => config('web_name'),
            'total_fee' => $this->amount,
            'notify_url' => $this->notifyUrl,
            'return_url' => $this->returnUrl
        ];
        return $this->buildRequestFormShan($params, self::KEY);
    }

    public function getCode()
    {
        return <<<JS
$("form:eq(0)").after(msg.info);
JS;
    }

    public static function charge()
    {
        $shanNotify = self::md5VerifyShan($_REQUEST['out_order_no'], $_REQUEST['total_fee'], $_REQUEST['trade_status'], $_REQUEST['sign'], self::KEY, self::PARTNER);
        if ($shanNotify && $_REQUEST['trade_status'] == 'TRADE_SUCCESS') {
            self::saveCharge($_REQUEST['out_order_no']);
        }
        return 'success';
    }

    public static function md5VerifyShan($p1, $p2, $p3, $sign, $key, $pid)
    {
        $prestr = $p1 . $p2 . $p3 . $pid . $key;
        $mysgin = md5($prestr);
        if ($mysgin == $sign) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 建立请求，以表单HTML形式构造（默认）
     * @param $params 请求参数数组
     */
    public function buildRequestFormShan($params, $key)
    {
        //待请求参数数组
        $para = $this->buildRequestParaShan($params, $key);

        $sHtml = "<form id='paysubmit' name='paysubmit' action='" . self::URL . "' accept-charset='utf-8' method='POST'>";
        while (list($key, $val) = each($para)) {
            $sHtml.= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
        }
        //submit按钮控件请不要含有name属性
        $sHtml = $sHtml . "<input type='submit' value='支付进行中...' style='display:none;'></form>";
        
        $sHtml = $sHtml . "<script>document.forms['paysubmit'].submit();</script>";
        
        return $sHtml;
    }

    /**
     * 生成要请求给云通付的参数数组
     * @param $params 请求前的参数数组
     * @return 要请求的参数数组
     */
    public function buildRequestParaShan($params, $key)
    {
        //除去待签名参数数组中的空值和签名参数
        $paraFilter = $this->paraFilterShan($params);
        //对待签名参数数组排序
        $paraSort = $this->argSortShan($paraFilter);
        //生成签名结果
        $mysign = $this->buildRequestMysignShan($paraSort,$key);
        //签名结果与签名方式加入请求提交参数组中
        $paraSort['sign'] = $mysign;

        return $paraSort;
    }

    /**
     * 除去数组中的空值和签名参数
     * @param $para 签名参数组
     * return 去掉空值与签名参数后的新签名参数组
     */
    public function paraFilterShan($para)
    {
        $paraFilter = [];
        while (list($key, $val) = each($para)) {
            if ($key == 'sign' || $val == '') {
                continue;
            } else {
                $paraFilter[$key] = $para[$key];
            }
        }
        return $paraFilter;
    }

    /**
     * 对数组排序
     * @param $para 排序前的数组
     * return 排序后的数组
     */
    public function argSortShan($para)
    {
        ksort($para);
        reset($para);
        return $para;
    }

    /**
     * 生成签名结果
     * @param $paraSort 已排序要签名的数组
     * return 签名结果字符串
     */
    public function buildRequestMysignShan($paraSort, $key)
    {
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = $this->createLinkstringShan($paraSort);
        return $this->md5SignShan($prestr, $key);
    }

    public function md5SignShan($prestr, $key)
    {
        $prestr = $prestr . $key;
        return md5($prestr);
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param $para 需要拼接的数组
     * return 拼接完成以后的字符串
     */
    public function createLinkstringShan($para)
    {
        $arg  = '';
        while (list($key, $val) = each($para)) {
            $arg .= $key . '=' . $val . '&';
        }
        //去掉最后一个&字符
        $arg = substr($arg, 0, count($arg) - 2);

        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {
            $arg = stripslashes($arg);
        }

        return $arg;
    }
}

