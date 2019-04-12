<?php

namespace common\models;

use Yii;

/**
 * 这是表 `article` 的模型
 */
class Article extends \common\components\ARModel
{
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['menu_id', 'count', 'state', 'created_by', 'updated_by'], 'integer'],
            [['content'], 'default', 'value' => ''],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'template', 'cover'], 'string', 'max' => 100],
            [['summary'], 'string', 'max' => 200]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'menu_id' => 'Menu ID',
            'summary' => '摘要',
            'template' => '模板',
            'content' => '内容',
            'cover' => '封面图',
            'count' => '浏览次数',
            'state' => '状态',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /****************************** 以下为设置关联模型的方法 ******************************/

    public function getMenu()
    {
        return $this->hasOne(ArticleMenu::className(), ['id' => 'menu_id']);
    }

    /****************************** 以下为公共显示条件的方法 ******************************/

    public function search()
    {
        $this->setSearchParams();

        return self::find()
            ->filterWhere([
                'article.id' => $this->id,
                'article.menu_id' => $this->menu_id,
                'article.count' => $this->count,
                'article.state' => $this->state,
                'article.created_by' => $this->created_by,
                'article.updated_by' => $this->updated_by,
            ])
            ->andFilterWhere(['like', 'article.title', $this->title])
            ->andFilterWhere(['like', 'article.summary', $this->summary])
            ->andFilterWhere(['like', 'article.template', $this->template])
            ->andFilterWhere(['like', 'article.content', $this->content])
            ->andFilterWhere(['like', 'article.cover', $this->cover])
            ->andFilterWhere(['like', 'article.created_at', $this->created_at])
            ->andFilterWhere(['like', 'article.updated_at', $this->updated_at])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/



    /****************************** 以下为字段的映射方法和格式化方法 ******************************/

    protected static $_menuIdMap = null;
    // Map method of field `menu_id`
    public static function getMenuIdMap($prepend = false)
    {
        if (self::$_menuIdMap === null) {
            self::$_menuIdMap = ArticleMenu::find()->where(['pid' => 0])->map('id', 'name');
        }

        return self::resetMap(self::$_menuIdMap, $prepend);
    }

    // Format method of field `menu_id`
    public function getMenuIdValue($value = null)
    {
        return $this->resetValue($value);
    }
}
