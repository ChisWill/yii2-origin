<?php

namespace common\modules\manual\models;

use Yii;

/**
 * 这是表 `manual_collection` 的模型
 */
class Collection extends \common\components\ARModel
{
    public static function tableName()
    {
        return '{{%manual_collection}}';
    }

    public function rules()
    {
        return [
            [['user_id', 'menu_id'], 'required'],
            [['user_id', 'menu_id', 'state', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_id'], 'unique', 'targetAttribute' => ['user_id', 'menu_id'], 'message' => '您已经收藏过了~！']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户ID',
            'menu_id' => '收藏的菜单ID',
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
                'collection.id' => $this->id,
                'collection.user_id' => $this->user_id,
                'collection.menu_id' => $this->menu_id,
                'collection.state' => $this->state,
                'collection.created_by' => $this->created_by,
                'collection.updated_by' => $this->updated_by,
                ])
            ->andFilterWhere(['like', 'collection.created_at', $this->created_at])
            ->andFilterWhere(['like', 'collection.updated_at', $this->updated_at])
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/
}
