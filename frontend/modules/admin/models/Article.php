<?php

namespace admin\models;

use Yii;

class Article extends \common\models\Article
{
    public $file;
    public $categoryType;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['file'], 'file', 'skipOnEmpty' => $this->categoryType != 1, 'uploadRequired' => '新闻类必须上传{attribute}', 'extensions' => 'jpg,png,gif', 'maxSize' => 2048 * 1000]
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
            ->andWhere(['article.state' => self::STATE_VALID])
            ->orderBy('article.id DESC');
    }
}
