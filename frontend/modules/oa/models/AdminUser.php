<?php

namespace oa\models;

use Yii;

class AdminUser extends \admin\models\AdminUser
{
    public function rules()
    {
        return array_merge(parent::rules(), [
        ]);
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
        ]);
    }

    public static function getAllUserMap()
    {
        return AdminUser::find()->active()->map('id', 'realname');
    }

    public static function getDevUserMap()
    {
        return AdminUser::find()->where(['position' => self::POSITION_DEV])->active()->map('id', 'realname');
    }

    public static function getSaleUserMap()
    {
        return AdminUser::find()->where(['position' => self::POSITION_SALE])->active()->map('id', 'realname');
    }
}
