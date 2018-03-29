<?php

namespace common\modules\manual\models;

use Yii;

/**
 * 这是表 `hsh_manual_article` 的模型
 */
class Article extends \common\components\ARModel
{
    public static function tableName()
    {
        return '{{%manual_article}}';
    }

    public function rules()
    {
        return [
            [['menu_id'], 'required', 'message' => '该菜单已删除~！'],
            [['menu_id', 'state', 'created_by', 'updated_by'], 'integer'],
            [['content'], 'string'],
            [['created_at', 'updated_at'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'menu_id' => '菜单ID',
            'content' => '文章内容',
            'state' => 'State',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /****************************** 以下为设置关联模型的方法 ******************************/

    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'menu_id'])->select(['id', 'name', 'pid', 'level', 'sort']);
    }

    /****************************** 以下为公共显示条件的方法 ******************************/

    public function search()
    {
        $this->setSearchParams();

        return self::find()
            ->filterWhere([
                'article.id' => $this->id,
                'article.menu_id' => $this->menu_id,
                'article.state' => $this->state,
                'article.created_by' => $this->created_by,
                'article.updated_by' => $this->updated_by,
                ])
            ->andFilterWhere(['like', 'article.content', $this->content])
            ->andFilterWhere(['like', 'article.created_at', $this->created_at])
            ->andFilterWhere(['like', 'article.updated_at', $this->updated_at])
        ;
    }

    public function keywordSearch($keyword)
    {
        return self::search()->joinWith('menu')->andWhere(['like', 'article.content', $keyword])->orWhere(['like', 'menu.name', $keyword]);
    }

    /****************************** 以下为公共操作的方法 ******************************/

    public function saveArticle()
    {
        if ($this->save()) {
            (new Version)->trigger(Version::EVENT_CREATE_VERSION, self::getEvent(['menuId' => $this->menu_id, 'content' => $this->content, 'action' => Version::ACTION_UPDATE]));

            return true;
        } else {
            return false;
        }
    }

    public function revertArticle($toVersion)
    {
        if ($this->update()) {
            (new Version)->trigger(Version::EVENT_CREATE_VERSION, self::getEvent(['menuId' => $toVersion->menu_id, 'content' => $toVersion->content, 'action' => Version::ACTION_REVERT]));

            return true;
        } else {
            return false;
        }
    }

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/
}
