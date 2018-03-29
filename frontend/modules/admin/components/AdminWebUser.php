<?php

namespace admin\components;

use Yii;

/**
 * 后台用户认证类
 *
 * @author ChisWill
 */
class AdminWebUser extends \common\components\Identity
{
    public static function tableName()
    {
        return '{{%admin_user}}';
    }

    /**
     * @see common\components\Identity:can()
     */
    public function can($permissionName, $params = [], $allowCaching = true)
    {
        if ($this->isMe) {
            return true;
        } else {
            return parent::can($permissionName, $params, $allowCaching);
        }
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'state' => \common\components\ARModel::STATE_VALID]);
    }
}
