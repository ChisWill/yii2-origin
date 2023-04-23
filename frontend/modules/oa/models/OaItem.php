<?php

namespace oa\models;

use Yii;

/**
 * 这是表 `oa_item` 的模型
 */
class OaItem extends \common\components\ARModel
{
    public function rules()
    {
        return [
            [['pid', 'name'], 'required'],
            [['pid', 'state'], 'integer'],
            [['amount'], 'number'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => '父类',
            'name' => '物品名称',
            'amount' => '金额',
            'state' => '是否启用',
            'created_at' => '创建时间',
        ];
    }

    /****************************** 以下为设置关联模型的方法 ******************************/

    public function getParent()
    {
        return $this->hasOne(OaItem::className(), ['id' => 'pid'])->select(['id', 'name']);
    }

    /****************************** 以下为公共显示条件的方法 ******************************/

    public function search()
    {
        $this->setSearchParams();

        return self::find()
            ->filterWhere([
                'oaItem.id' => $this->id,
                'oaItem.pid' => $this->pid,
                'oaItem.amount' => $this->amount,
                'oaItem.state' => $this->state,
            ])
            ->andFilterWhere(['like', 'oaItem.name', $this->name])
            ->andFilterWhere(['like', 'oaItem.created_at', $this->created_at])
            ->andTableSearch();
    }

    /****************************** 以下为公共操作的方法 ******************************/



    /****************************** 以下为字段的映射方法和格式化方法 ******************************/

    // Map method of field `pid`
    public static function getPidMap($prepend = false)
    {
        $map = self::find()->where(['pid' => 0, 'state' => self::STATE_VALID])->map('id', 'name');

        return self::resetMap($map, $prepend);
    }

    // Format method of field `pid`
    public function getPidValue($value = null)
    {
        return $this->resetValue($value);
    }

    // Map method of field `state`
    public static function getStateMap($prepend = false)
    {
        $map = [
            self::STATE_VALID => '启用',
            self::STATE_INVALID => '禁用',
        ];

        return self::resetMap($map, $prepend);
    }

    // Format method of field `state`
    public function getStateValue($value = null)
    {
        return $this->resetValue($value);
    }
}
