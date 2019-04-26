<?php

namespace common\helpers;

use Yii;

class Security
{
    private static $_privateKey = '-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQCs/kwbwWWyjLjgMFcoAfJS/nw2ZWuagqscQjS6WLDXgx/3o1Ml
BosdDV5cuV+peErYz0bh2YF188Fj+eiWKyzjNg/jiGfFWFQ916nkpLCDRAIHibTr
wPAd8H0WnGYiSWbANf3kHbrt4rUWXHm5Me70kXpj2fDe/3UqTLqoP11QAQIDAQAB
AoGAMepWT5PoIjLUjWulWlflcekYMiHlgHm/obHAwRwiOq0NJkksDnzq2wEmfx7F
6YJaJmD4lOZLopso7A4J/HqMwrEPapCe9+mjHD+9NJdc8c/E17jPGUaR4gYoGQXb
SwPGXsrznZfmkpy7KiQZ4Vo8Hcz6JEbl1x8hud5rv3tPKvkCQQDeaw2l2agrbhLN
hqnWmr4mWixhKlA3BPiASmCq1p/QP78x4ccMztwXRTVJWp6hiexLU1ez9XOkWJQD
aepBnKk3AkEAxxzd5hu9Q+01lmx96vQ1F0i4IJHxpMn4dxLak9j7KXM20xIBx8nk
pFfFJID2Y7My/AweCAH/m9110htL03qMhwJAMsf4ZrxqI/hOvLQZRNExxunhphGW
HOm6nvfcWEUGWfKkAYyN+MOmBn4bq3LQMwudcplFteW9kHFU4e6luHM/QwJAU+xn
3wBcItBNoOxzml96LSk6aof5KPL0JgQtWtm+6zajqg1R8Mq480gHUR6GO3mhiLj2
w3tMKH8MlRVqPWXO6wJBAJl1GbPhD0r7qZpEhnnDtF1sJNgUYFsiSA+3Rq5cquS6
1qaBKc76+HfN0f5jGlUuuHVCC+AIPQHux/oRNl5i76o=
-----END RSA PRIVATE KEY-----';

    private static $_publicKey = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCs/kwbwWWyjLjgMFcoAfJS/nw2
ZWuagqscQjS6WLDXgx/3o1MlBosdDV5cuV+peErYz0bh2YF188Fj+eiWKyzjNg/j
iGfFWFQ916nkpLCDRAIHibTrwPAd8H0WnGYiSWbANf3kHbrt4rUWXHm5Me70kXpj
2fDe/3UqTLqoP11QAQIDAQAB
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
