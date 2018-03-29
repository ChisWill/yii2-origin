<?php

namespace common\components;

use Yii;
use common\helpers\FileHelper;

/**
 * 前端资源包的基类
 *
 * @author ChisWill
 */
class AssetBundle extends \yii\web\AssetBundle
{
    use \common\traits\FuncTrait;

    public function init()
    {
        parent::init();

        // 当存在页面临时加载的css文件时
        $view = Yii::$app->view;
        if ($view->depends) {
            $this->depends = array_merge($this->depends, $view->depends);
            $view->depends = null;
        }
        if ($view->regCss) {
            $this->css = array_merge($this->css, $view->regCss);
            $view->regCss = null;
        }
        if ($view->regJs) {
            $this->js = array_merge($this->js, $view->regJs);
            $view->regJs = null;
        }
        // 当静态资源放在web可见的目录时，自动在文件末尾添加版本号
        if (!$this->sourcePath) {
            $basePath = Yii::getAlias('@' . FileHelper::getCurrentApp() . '/web/');
            $themePath = THEME_NAME === null ? '' : Yii::getAlias('@web/themes/' . THEME_NAME . '/');
            // js文件
            foreach ($this->js as $key => $js) {
                $jsFile = $basePath . ltrim($themePath, '/') . $js;
                if (!file_exists($jsFile)) {
                    $jsPath = $js;
                    $jsFile = $basePath . $js;
                } else {
                    $jsPath = $themePath . $js;
                }
                $this->js[$key] = $jsPath . '?v=' . filemtime($jsFile);
            }
            // css文件
            foreach ($this->css as $key => $css) {
                $cssFile = $basePath . ltrim($themePath, '/') . $css;
                if (!file_exists($cssFile)) {
                    $cssPath = $css;
                    $cssFile = $basePath . $css;
                } else {
                    $cssPath = $themePath . $css;
                }
                $this->css[$key] = $cssPath . '?v=' . filemtime($cssFile);
            }
        }
    }
}
