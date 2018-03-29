<?php

namespace pay\models;

use Yii;
use common\helpers\Curl;

class QianTongBao extends \pay\components\Pay
{
    const URL = 'http://api.qtbzf.com/bank/index.aspx';
    const PARTER = '1637';
    const KEY = '3b04e7bf8bc9456c8c40ae3b030c62ce';

    public function ready()
    {
        $params = [
            'parter' => self::PARTER,
            'type' => '1004',
            'value' => $this->amount,
            'orderid' => $this->trade_no,
            'callbackurl' => $this->notifyUrl
        ];
        $params['sign'] = self::getSign($params);
        $url = self::URL;
        $d = '?';
        foreach ($params as $key => $value) {
            $url .= $d . $key . '=' . $value;
            $d = '&';
        }
        $result = json_decode(Curl::get($url), true);
        if (isset($result['codeUrl'])) {
            return '<form action="' . $result['codeUrl'] . '" id="chargeForm" method="get"></form>';
        } else {
            l($result, 'QianTongBao');
            throwex('该通道维护中，请稍后再试.');
        }
    }

    public function getCode()
    {
        return <<<JS
$("form:eq(0)").after(msg.info);
$("#chargeForm").submit();
JS;
    }

    public static function charge()
    {
        $orderid = get('orderid');
        $opstate = get('opstate');
        $ovalue = get('ovalue');
        $params = [
            'orderid' => $orderid,
            'opstate' => $opstate,
            'ovalue' => $ovalue,
        ];
        $sign = self::getSign($params);
        if ($sign == get('sign') && $opstate == 0) {
            self::saveCharge($orderid);
            return 'opstate=0';
        } else {
            return 'opstate=-2';
        }
    }

    public static function getSign($params)
    {
        $string = $d = '';
        foreach ($params as $key => $value) {
            $string .= $d . $key . '=' . $value;
            $d = '&';
        }
        $string .= self::KEY;
 
        return md5($string);
    }
}

