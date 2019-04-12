<?php

namespace common\modules\manual\components;

use Yii;

/**
 * 用户认证类
 */
class WebUser extends \frontend\components\WebUser
{
    public function canRead()
    {
        if ($this->getIsMe()) {
            return true;
        } else {
            return $this->vip >= 1;
        }
    }

    public function canWrite()
    {
        if ($this->getIsMe()) {
            return true;
        } else {
            return $this->vip >= 2;
        }
    }
}
