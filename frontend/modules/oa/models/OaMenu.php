<?php

namespace oa\models;

use Yii;

/**
 * 这是表 `oa_menu` 的模型
 */
class OaMenu extends \admin\models\AdminMenu
{
    public static function tableName()
    {
        return '{{%oa_menu}}';
    }

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
}
