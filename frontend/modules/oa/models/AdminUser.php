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
}
