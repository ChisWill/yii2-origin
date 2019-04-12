<?php

namespace common\helpers;

use Yii;
use yii\imagine\Image as Imagine;

/**
 * 图片类操作的助手方法
 * 
 * @author ChisWill
 */
class Image
{
    /**
     * 二维码中间加上LOGO
     *
     * @param  string $qrPath    二维码图片的物理路径
     * @param  string $logoPath  LOGO图片的物理路径
     * @return string            合成后的图片网络路径
     */
    public static function mergeQrLogo($qrPath, $logoPath)
    {
        $qrPath = Yii::getAlias($qrPath);
        $logoPath = Yii::getAlias($logoPath);

        $qrInfo = getimagesize($qrPath);
        $logoInfo = getimagesize($logoPath);
        $left = ($qrInfo[0] - $logoInfo[0]) / 2;
        $top = ($qrInfo[1] - $logoInfo[1]) / 2;

        $pathInfo = pathinfo($qrPath);
        $newPath = Yii::getAlias(sprintf('%s/%s.%s', $pathInfo['dirname'], $pathInfo['filename'] . '-logo', $pathInfo['extension']));

        Imagine::watermark($qrPath, $logoPath, [$left, $top])->save($newPath, ['quality' => 100]);
        return self::getWebPath($newPath);
    }

    /**
     * 生成缩略图
     * 
     * @param  string $imagePath 图片物理路径
     * @param  int    $size      尺寸
     * @return string            缩略图网络路径
     */
    public static function thumbnail($imagePath, $size)
    {
        $pathInfo = pathinfo($imagePath);
        $newPath = Yii::getAlias(sprintf('%s/%s.%s', $pathInfo['dirname'], $pathInfo['filename'] . '-' . $size, $pathInfo['extension']));

        $imagine = Imagine::getImagine();
        $size = new \Imagine\Image\Box($size, $size);
        $mode = \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;

        $image = $imagine
            ->open($imagePath)
            ->thumbnail($size, $mode)
            ->save($newPath);
        return self::getWebPath($newPath);
    }

    /**
     * 物理路径转换为网络路径
     * 
     * @param  string $root 物理路径
     * @return string       网络路径
     */
    public static function getWebPath($root)
    {
        return str_replace([Yii::getAlias('@webroot'), '\\'], ['', '/'], $root);
    }
}
