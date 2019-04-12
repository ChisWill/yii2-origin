<?php

namespace common\modules\manual;

use Yii;

/**
 * 手册模块
 *
 * @author ChisWill
 */
class Module extends \common\components\Module
{
    public function init()
    {
        parent::init();

        self::moduleInit();
    }

    public static function moduleInit()
    {
        // 修改用户组件配置
        Yii::$app->user->identityClass = 'common\modules\manual\components\WebUser';

        if (user()->isGuest || !u()->canRead()) {
            Yii::$app->response->redirect(['site/index'])->send();
        }
    }
}
