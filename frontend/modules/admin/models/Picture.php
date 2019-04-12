<?php

namespace admin\models;

use Yii;

class Picture extends \common\models\Picture
{
    public $file;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['file'], 'file', 'skipOnEmpty' => false, 'uploadRequired' => '必须上传{attribute}', 'extensions' => 'jpg,png,gif', 'maxSize' => 2048 * 1000]
        ]);
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            // 'scenario' => ['field1', 'field2'],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'file' => '图片',
            // 'field2' => 'description2',
        ]);
    }
}
