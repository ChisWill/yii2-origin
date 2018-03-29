<?php

namespace admin\models;

use Yii;

class ApiApp extends \common\models\ApiApp
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            // [['field1', 'field2'], 'required', 'message' => '{attribute} is required'],
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
            'user_id' => '客户名称',
            // 'field2' => 'description2',
        ]);
    }

    public function listQuery()
    {
        return $this->search()
            ->joinWith(['user'])
            ->orderBy('apiApp.id DESC');
    }
}
