<?php

namespace common\helpers;

use Yii;

class Security
{
    private static $_privateKey = '-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEA1ptDUc6ONv/y4q/XPzt1ROGlSnfKoNo22fDtwKi0br6MgnCa
MX+Y+Vbxw+gnHhdn6dvghEmdPcEczuU6mtLrd5IPvcDEBjrqf02Qg+Lso3WYiSp0
f/4VQFuyJLp5NvEGo5GJHz5aysn7GaS8dUfsRrsoei2pKyOklLXOEd+QIoGWSwuu
p9s5B/NkjCwEoWcEWix1G6+znlQftRejipMOZLE0xyrPrR7MxA4LV33oCrvI5JJ/
MOqfkaSMEMrH6TZXhVWLfKZU4gNgcXkmYp++k2KHCOcKbyHZhmK8p1MoEXglmyYM
u3KWNoeb0GRuLf2OCgPenLab1gZVCSaU0L4mVwIDAQABAoIBAQC5lKBdgPKIa0M9
dnP7sU4Sl9KZGAg4mxtt0iO3Q+xsDAlzzy33+fBuRhphoEMbiYAJwmNf4kzAl1tz
KtVIB3cp+jv0ilq5TH92QLvk4PpjzBBxJS6DdFQqSGE/06V4tmtYTUU3qHNZA9tJ
bKSwAtc2vygcp5mvvW3YT3N5D0wUGUbYrGNqx3V8lmk9pbN6MpNGyKvSMlNPDkc9
KvTbcBQi0A/eZkY7ewr3tUpQUfcsSBUKAXMnu4sSAi5vY0htsKT3E/uDo+Yec5W0
XniCiNY52QbdrfQfAIzTCRG8y/Ihpl+RLNkhJtZHQHJ0ZF7mrc7FHUTXi9tYRKRj
qNte0TcBAoGBAP+Pp46kVhOv93GDaR/DmCq/OYG9wgLHoilxekMr5R3Y06nlg5Qp
5lHsX7W5wIJ4KllJNuqzE11D81LVPU+9c++ojhWZvdeKvneyUwJbDs9EkID2MPzY
3GYIBuwqkhEaec3BuNRovIBqr1VAJaF3V+kdlZBk+k76hIGl7DbpMczXAoGBANb5
msqFhP24F2MiyOowJpfm3UZQ13bSwhGat8m+c8LBroZnI4Au66QoTBmcugr2SZJA
wVUcWB7Sx5qfyOXURowXVciv8aNOTvon7F32PcjJgT2N0CiWDlMhUKYoZL7/9Ai4
VjHb62vu6aLnUrNr+JFh/BzE9yHJMv+mVDtZjcKBAoGAW95BRpau1r7v/Z9Wv/Np
FRzOyGP1hVhMZAeGAvWZlGQomq+F4FI7mIGXWlVe7cfSuWwHUNF1CZbutn0vYCXj
smhuQzeUNhKLK64wBu3C4iFsrN9TduFiQU9rZaRcA8f3t06HadwIv0UaqFO581Ra
htN6u4CBNrj5vz1tL8QegdsCgYBY2dt3Xw4ji4XnDIZ7/KDG4b2NBXa4fs2FVywl
LxlLYp/OnyxWG457T8h8QYHHbBnmX+tIUSm+u6GSL/5pt33dAvwKsWWu+Hu9+ug/
wbmxlk9bcJSbYcHT7A4YTtOs+b8UJ5RAkUPRXXKlSPSuga6/7/6yqVn3VeBFN6rS
yOXFgQKBgQDsUc1ixj7CB7HzSctTixpah5g1nqbDeO1/gyUZwGyk7YJPt9z0uzhE
Lx5osDfRS/fCdJY/lL1fQTH7yX5mR0ii2jCm9aRdS6Yx1VNfWI+HzYk8mhsRcRPX
HgJUIOi0KOwhMZfVC6G5QXCcLF5ePhjBI+4f1yVHomLLtL7YyaJMpg==
-----END RSA PRIVATE KEY-----';

    private static $_publicKey = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA1ptDUc6ONv/y4q/XPzt1
ROGlSnfKoNo22fDtwKi0br6MgnCaMX+Y+Vbxw+gnHhdn6dvghEmdPcEczuU6mtLr
d5IPvcDEBjrqf02Qg+Lso3WYiSp0f/4VQFuyJLp5NvEGo5GJHz5aysn7GaS8dUfs
Rrsoei2pKyOklLXOEd+QIoGWSwuup9s5B/NkjCwEoWcEWix1G6+znlQftRejipMO
ZLE0xyrPrR7MxA4LV33oCrvI5JJ/MOqfkaSMEMrH6TZXhVWLfKZU4gNgcXkmYp++
k2KHCOcKbyHZhmK8p1MoEXglmyYMu3KWNoeb0GRuLf2OCgPenLab1gZVCSaU0L4m
VwIDAQAB
-----END PUBLIC KEY-----';

    public static function privateEncrypt($data)
    {
        $result = '';
        foreach (str_split($data, 117) as $chunk) {
            openssl_private_encrypt($chunk, $encrypted, openssl_pkey_get_private(self::$_privateKey));
            $result .= $encrypted;
        }
        return $result;
    }

    public static function publicEncrypt($data)
    {
        $result = '';
        foreach (str_split($data, 117) as $chunk) {
            openssl_public_encrypt($chunk, $encrypted, openssl_pkey_get_public(self::$_publicKey));
            $result .= $encrypted;
        }
        return $result;
    }

    public static function privateDecrypt($encrypted, $privateKey)
    {
        $result = '';
        foreach (str_split($encrypted, 128) as $chunk) {
            openssl_private_decrypt($chunk, $decrypted, openssl_pkey_get_private($privateKey));
            $result .= $decrypted;
        }
        return $result;
    }

    public static function publicDecrypt($encrypted, $publicKey)
    {
        $result = '';
        foreach (str_split($encrypted, 128) as $chunk) {
            openssl_public_decrypt($chunk, $decrypted, openssl_pkey_get_public($publicKey));
            $result .= $decrypted;
        }
        return $result;
    }

    /**
     * 生成哈希密码
     * 
     * @see yii\base\Security::generatePasswordHash()
     */
    public static function generatePasswordHash($password, $cost = null)
    {
        return Yii::$app->getSecurity()->generatePasswordHash($password, $cost);
    }

    /**
     * 验证哈希密码
     * 
     * @see yii\base\Security::validatePassword()
     */
    public static function validatePassword($password, $hash)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $hash);
    }

    /**
     * 异或加密，特点为执行一次加密，再执行一次为解密
     */
    public static function xorEncrypt($data, $secretKey = SECRET_KEY)
    {
        $key = sha1($secretKey);
        $dataLen = strlen($data);
        $keyLen = strlen($key);
        $mod = $dataLen % $keyLen;
        if ($mod > 0) {
            $preLen = floor($dataLen / $keyLen) * $keyLen;
            return (substr($data, 0, $preLen) ^ str_repeat($key, $preLen)) . (substr($data, $preLen) ^ substr($key, 0, $mod));
        } else {
            return $data ^ str_repeat($key, $dataLen / $keyLen);
        }
    }

    /**
     * 可用于页面间传输的加密
     */
    public static function base64encrypt($data)
    {
        return Url::base64encode(static::xorEncrypt($data));
    }

    /**
     * base64encrypt 对应的解密
     */
    public static function base64decrypt($base64encryptedData)
    {
        return static::xorEncrypt(Url::base64decode($base64encryptedData));
    }

    /**
     * 加密
     * 
     * @see yii\base\Security::encryptByPassword()
     */
    public static function encrypt($data, $secretKey = SECRET_KEY)
    {
        return Yii::$app->getSecurity()->encryptByKey($data, $secretKey);
    }

    /**
     * 解密
     * 
     * @see yii\base\Security::decryptByPassword()
     */
    public static function decrypt($encryptedData, $secretKey = SECRET_KEY)
    {
        return Yii::$app->getSecurity()->decryptByKey($encryptedData, $secretKey);
    }

    /**
     * 将由安全秘钥和数据生成的哈希串前缀加到数据上
     * 
     * @see yii\base\Security::hashData()
     */
    public static function hashData($genuineData, $secretKey = SECRET_KEY)
    {
        return Yii::$app->getSecurity()->hashData($genuineData, $secretKey);
    }

    /**
     * 验证数据的完整性
     * 
     * @see yii\base\Security::validateData()
     */
    public static function validateData($data, $secretKey = SECRET_KEY)
    {
        return Yii::$app->getSecurity()->validateData($data, $secretKey);
    }
}
