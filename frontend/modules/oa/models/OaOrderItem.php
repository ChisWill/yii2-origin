<?php

namespace oa\models;

use Yii;

/**
 * 这是表 `oa_order_item` 的模型
 */
class OaOrderItem extends \common\components\ARModel
{
    public function rules()
    {
        return [
            [['order_id', 'item_id'], 'required'],
            [['order_id', 'item_id', 'num'], 'integer'],
            [['amount'], 'number']
        ];
    }

    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'item_id' => 'Item ID',
            'num' => 'Num',
            'amount' => 'Amount',
        ];
    }

    /****************************** 以下为设置关联模型的方法 ******************************/

    public function getItem()
    {
        return $this->hasOne(OaItem::className(), ['id' => 'item_id']);
    }

    /****************************** 以下为公共显示条件的方法 ******************************/

    public function search()
    {
        $this->setSearchParams();

        return self::find()
            ->filterWhere([
                'oaOrderItem.order_id' => $this->order_id,
                'oaOrderItem.item_id' => $this->item_id,
                'oaOrderItem.num' => $this->num,
                'oaOrderItem.amount' => $this->amount,
            ])
            ->andTableSearch();
    }

    /****************************** 以下为公共操作的方法 ******************************/



    /****************************** 以下为字段的映射方法和格式化方法 ******************************/
}
