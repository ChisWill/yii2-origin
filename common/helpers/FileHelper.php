<?php

namespace common\helpers;

use Yii;

class FileHelper extends \yii\helpers\BaseFileHelper
{
    /**
     * 格式化文件大小描述
     *
     * @param  int    文件字节大小，一般是根据`filesize()`获得
     * @return string 更适合人阅读的文件大小描述
     */
    public static function formatFileSize($size)
    {
        if ($size == 0) {
            return 0;
        }
        $units = ['B', 'K', 'M', 'G', 'T'];
        $unit = array_shift($units);
        while ($size > 1024) {
            $size = round($size / 1024, 1);
            $unit = array_shift($units);
        }
        return $size . $unit;
    }

    /**
     * 根据文件名获取后缀
     *
     * @param  string 文件名
     * @return string 文件名后缀
     */
    public static function getExt($filename)
    {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }

    /**
     * 获取当前项目名称
     * 
     * @return string 当前的项目名称
     */
    public static function getCurrentApp()
    {
        return basename(Yii::$app->basePath);
    }

    /**
     * 获取当前框架内的所有项目名称
     *
     * @param  array $except 需要排除的项目
     * @return array         项目名称数组
     */
    public static function getApps($except = [])
    {
        preg_match_all('/Yii::setAlias\(\'@?(\w*)\',/U', file_get_contents(Yii::getAlias('@common/config/bootstrap.php')), $matches);

        return array_diff($matches[1], $except);
    }

    /**
     * 可递归的创建文件夹，在yii助手方法的基础上，简化调用名字，并更改了默认权限
     * 
     * @param  string  $dir  文件路径
     * @param  integer $mode 权限
     * @return boolean       是否创建成功
     */
    public static function mkdir($dir, $mode = 0775)
    {
        return parent::createDirectory($dir, $mode);
    }

    /**
     * 获取指定目录下的所有文件夹（不递归获取）
     * 
     * @param  string $dir 文件路径
     * @return array       包含文件夹的数组
     */
    public static function getDirs($dir)
    {
        $handle = opendir($dir);
        if ($handle === false) {
            throw new \yii\base\InvalidParamException("Unable to open directory: $dir");
        }
        $list = [];
        while (($file = readdir($handle)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            if (is_dir($file)) {
                $list[] = $file;
            }
        }
        closedir($handle);

        return $list;
    }
    
    /**
     * 获取图片的base64编码
     * 
     * @param  string $imgPath 可以使用路径别名表示的图片路径
     * @return string          base64编码后的图片
     */
    public static function getBase64Img($imgPath)
    {
        $filePath = Yii::getAlias($imgPath);
        $mimeType = static::getMimeType($filePath);
        $fileContent = base64_encode(file_get_contents($filePath));
        
        return 'data:' . $mimeType . ';base64,' . $fileContent;
    }
}
