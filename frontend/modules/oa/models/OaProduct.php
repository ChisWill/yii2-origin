<?php

namespace oa\models;

use Yii;

/**
 * 这是表 `oa_product` 的模型
 */
class OaProduct extends \common\components\ARModel
{
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['desc'], 'string', 'max' => 900],
            [['version'], 'string', 'max' => 30]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '产品名称',
            'desc' => '描述',
            'version' => '版本',
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
                'oaProduct.id' => $this->id,
            ])
            ->andFilterWhere(['like', 'oaProduct.name', $this->name])
            ->andFilterWhere(['like', 'oaProduct.desc', $this->desc])
            ->andFilterWhere(['like', 'oaProduct.version', $this->version])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/
}
