<?php

namespace oa\models;

use Yii;

/**
 * 这是表 `oa_finance_category` 的模型
 */
class OaFinanceCategory extends \common\components\ARModel
{
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['type', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 100]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'type' => '收支类型',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
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
                'oaFinanceCategory.id' => $this->id,
                'oaFinanceCategory.type' => $this->type,
                'oaFinanceCategory.created_by' => $this->created_by,
                'oaFinanceCategory.updated_by' => $this->updated_by,
            ])
            ->andFilterWhere(['like', 'oaFinanceCategory.name', $this->name])
            ->andFilterWhere(['like', 'oaFinanceCategory.created_at', $this->created_at])
            ->andFilterWhere(['like', 'oaFinanceCategory.updated_at', $this->updated_at])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/

    // Map method of field `type`
    public static function getTypeMap($prepend = false)
    {
        $map = [
            OaFinance::TYPE_INCOME => '收入',
            OaFinance::TYPE_SPEND => '支出',
        ];

        return self::resetMap($map, $prepend);
    }

    // Format method of field `type`
    public function getTypeValue($value = null)
    {
        return $this->resetValue($value);
    }
}
