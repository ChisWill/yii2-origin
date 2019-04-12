<?php

namespace php\models;

use common\helpers\FileHelper;
/**
 * 1. 复制 `XR::exec()`、`XR::getMacAddress()`和`XR::encode()` 到 BaseYii.php 中
 * 2. 将 `XR::run()` 方法的内容，与 `BaseYii::autoload()` 中的 `include($classFile)` 替换
 * 3. 将 `views/layouts/check.php` 复制到 web 根目录下
 * 4. 将 `views/layouts/sign.php` 发给客户，这是单独生成激活码的文件
 * 5. 使用 `XR::batchEncode()` 批量加密所需的文件，并覆盖源文件
 *
 * 需要加密的文件列表：
 * common\components\*
 * common\models\*
 * frontend\controllers\*
 * frontend\models\*
 * Yii
 * yii\BaseYii
 * yii\base\Application
 * yii\base\Controller
 * yii\base\InlineAction
 * yii\base\Module
 * yii\web\Application
 * yii\web\Controller
 */
class XR
{
    public static function run($classFile)
    {
        $content = file_get_contents($classFile);
        if ($content[0] != '<' && strpos($classFile, 'yiisoft') === false) {
            $keyFile = self::getAlias('@frontend/web/reg.key');
            $mac = self::getMacAddress();
            if (file_exists($keyFile)) {
                $key = file_get_contents($keyFile);
                if ($key != self::encode($mac)) {
                    header('Location: /check.php?e=1&c=' . $mac);
                    die;
                }
            } else {
                header('Location: /check.php?c=' . $mac);
                die;
            }
            eval(substr(self::exec($content), 5));
        } else {
            include($classFile);
        }
    }

    public static function exec($data)
    {
        $key = '82c616cc233a2b57881dac851e437bda86fd455eb8bev97bde76ea1f5324zfgh849ewr12sdf48nhgfbv3qw8fd12cvbxci23213nv3l50pa6exgv9e343t3i98l8w2wz2cv6nm7e10vb2d15sqn';
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

    public static function getMacAddress()
    {
        $file = self::getAlias('@frontend/runtime/cache/code.bin');
        if (!file_exists($file)) {
            $getMacAddress = function () {
                $isWindowsOs = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
                $forWindows = function () {
                    @exec('ipconfig /all', $result);
                    if ($result) {
                        return $result;
                    } else {
                        $ipconfig = $_SERVER["WINDIR"] . "\system32\ipconfig.exe";
                        if (is_file($ipconfig)) {
                            @exec($ipconfig . " /all", $result);
                        } else {
                            @exec($_SERVER["WINDIR"] . "\system\ipconfig.exe /all", $result);
                            return $result;
                        }
                    }
                };
                $forLinux = function () {
                    @exec('whereis ifconfig', $ret);
                    $command = explode(' ', $ret[0])[1];
                    @exec($command . " -a", $result);
                    return $result;
                };
                try {
                    if ($isWindowsOs) {
                        $result = $forWindows();
                    } else {
                        $result = $forLinux();
                    }
                    foreach ($result as $value) {
                        if (preg_match("/[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f]/i", $value, $match)) {
                            return $match[0];
                        }
                    }
                    return false;    
                } catch (\Exception $e) {
                    tes('获取网卡信息失败');
                    test($e->getMessage());
                }
            };
            $mac = md5($getMacAddress());
            file_put_contents($file, $mac);
        } else {
            $mac = file_get_contents($file);
        }
        return $mac;
    }

    public static function encode($string)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(self::exec($string)));
    }

    public static function batchEncode()
    {
        $output = path('@webroot' . config('uploadPath') . '/encode');
        FileHelper::mkdir($output);
        $files = [
            'common/components',
            'common/models',
            'frontend/controllers',
            'frontend/models'
        ];
        foreach ($files as $file) {
            $to = $output . '/' . $file;
            FileHelper::mkdir($to);
            phpEncipher(path('@' . $file), $to, ['mode' => 2]);
        }
        $files = [
            'yii/base',
            'yii/web',
        ];
        foreach ($files as $file) {
            $to = $output . '/' . $file;
            FileHelper::mkdir($to);
            phpEncipher(path('@' . $file), $to, ['mode' => 1]);
        }
        phpEncipher(path('@yii'), $output, ['mode' => 1, 'isRecursion' => false]);

        test('批量加密完毕，文件保存在：' . $output);
    }
}
