<?php

namespace frontend\models;

use Yii;

class Article extends \common\models\Article
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
            // 'field1' => 'description1',
            // 'field2' => 'description2',
        ]);
    }

    public static function getAllArticleQuery($url)
    {
        return self::find()
            ->joinWith(['menu.parent'])
            ->where(['parent.url' => $url])
            ->orderBy('article.id desc')
            ->active();
    }

    public static function getAllArticles($url)
    {
        return self::getAllArticleQuery($url)
            ->asArray()
            ->all();
    }

    public static function getArticleQuery($url)
    {
        return self::find()
            ->joinWith(['menu'])
            ->where(['menu.url' => $url])
            ->orderBy('article.id desc')
            ->active();
    }

    public static function getArticles($url)
    {
        return self::getArticleQuery($url)
            ->asArray()
            ->all();
    }
}
