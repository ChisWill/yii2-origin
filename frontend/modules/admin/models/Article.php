<?php

namespace admin\models;

use Yii;

class Article extends \common\models\Article
{
    public $categories = null;
    public $file;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['file'], 'file', 'extensions' => 'jpg,png,gif', 'maxSize' => 2048 * 1000]
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
            'file' => '封面图',
        ]);
    }

    public function listQuery()
    {
        return $this->search()
            ->joinWith(['menu'])
            ->andFilterWhere(['menu_id' => $this->categories])
            ->orderBy('article.id DESC');
    }
}
