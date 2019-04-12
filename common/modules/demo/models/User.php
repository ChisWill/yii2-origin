<?php

namespace common\modules\demo\models;

use Yii;

class User extends \common\models\User
{
    public $file;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['file'], 'file', 'maxFiles' => 10, 'extensions' => 'jpg,png,gif', 'maxSize' => 2048 * 1000] // 多文件上传，必须包含'maxFiles'配置项
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
            // 'field1' => 'description1',
            // 'field2' => 'description2',
        ]);
    }
}
