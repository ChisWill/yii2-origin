<?php

namespace frontend\models;

use Yii;

class ArticleMenu extends \common\models\ArticleMenu
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

    public static function getTopUrl($url)
    {
        return preg_split('/[-#]/', $url)[0];
    }

    private static $_menus = null;
    public static function getMenus()
    {
        if (self::$_menus === null) {
            self::$_menus = [];
            $data = ArticleMenu::find()->where(['pid' => 0, 'state' => ArticleMenu::STATE_VALID])->orderBy('sort')->asArray()->all();
            foreach ($data as $row) {
                self::$_menus[$row['url']] = $row;
            }
        }
        return self::$_menus;
    }

    public static function getSubMenus($pid)
    {
        $query = ArticleMenu::find()
            ->joinWith('parent')
            ->where(['articleMenu.state' => ArticleMenu::STATE_VALID])
            ->orderBy('articleMenu.sort')
            ->asArray();
        if (is_numeric($pid)) {
            $query->andWhere(['articleMenu.pid' => $pid]);
        } elseif (is_string($pid)) {
            $query->andWhere(['parent.url' => $pid]);
        }
        return $query->all();
    }
}
