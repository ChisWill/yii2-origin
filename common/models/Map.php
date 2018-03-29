<?php

namespace common\models;

use Yii;

/**
 * 这是表 `map` 的模型
 */
class Map extends \common\components\ARModel
{
    const TYPE_OA_PLATFORM = 1000;
    const TYPE_OA_ACCOUNT = 1001;

    const TYPE_OA_SOURCE = 2000;

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['type'], 'integer'],
            [['name'], 'string', 'max' => 250]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'type' => '映射类型',
        ];
    }

    /****************************** 以下为设置关联模型的方法 ******************************/

    // public function getRelation()
    // {
    //     return $this->hasOne(Class::className(), ['foreign_key' => 'primary_key']);
    // }

    /****************************** 以下为公共显示条件的方法 ******************************/

    public function search()
    {
        $this->setSearchParams();

        return self::find()
            ->filterWhere([
                'map.id' => $this->id,
                'map.type' => $this->type,
            ])
            ->andFilterWhere(['like', 'map.name', $this->name])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/
}
