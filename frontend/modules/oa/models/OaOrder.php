<?php

namespace oa\models;

use common\models\User;
use Yii;

/**
 * 这是表 `oa_order` 的模型
 */
class OaOrder extends \common\components\ARModel
{
    public $start_created_at;
    public $end_created_at;

    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['origin_amount', 'bonus_amount', 'freight_amount'], 'number'],
            [['arrive_date', 'created_at', 'updated_at'], 'safe'],
            [['freight_type'], 'string', 'max' => 20]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'origin_amount' => '订单原始金额',
            'bonus_amount' => '优惠金额',
            'arrive_date' => '期望到货时间',
            'freight_type' => '派送类型',
            'freight_amount' => '运费',
            'order_state' => '订单状态',
            'created_at' => '下单时间',
            'updated_at' => '修改时间',
        ];
    }

    /****************************** 以下为设置关联模型的方法 ******************************/

    public function getItems()
    {
        return $this->hasMany(OaOrderItem::className(), ['order_id' => 'id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /****************************** 以下为公共显示条件的方法 ******************************/

    public function search()
    {
        $this->setSearchParams();

        return self::find()
            ->filterWhere([
                'oaOrder.id' => $this->id,
                'oaOrder.user_id' => $this->user_id,
                'oaOrder.origin_amount' => $this->origin_amount,
                'oaOrder.bonus_amount' => $this->bonus_amount,
                'oaOrder.freight_amount' => $this->freight_amount,
            ])
            ->andFilterWhere(['like', 'oaOrder.arrive_date', $this->arrive_date])
            ->andFilterWhere(['like', 'oaOrder.freight_type', $this->freight_type])
            ->andFilterWhere(['like', 'oaOrder.created_at', $this->created_at])
            ->andFilterWhere(['like', 'oaOrder.updated_at', $this->updated_at])
            ->andTableSearch();
    }

    /****************************** 以下为公共操作的方法 ******************************/

    public function listQuery()
    {
        return $this->search()
            ->joinWith(['items', 'user'])
            ->andWhere(['oaOrder.state' => self::STATE_VALID])
            ->andFilterWhere(['>=', 'oaOrder.created_at', $this->start_created_at])
            ->andFilterWhere(['<', 'oaOrder.created_at', $this->end_created_at ? date('Y-m-d', strtotime($this->end_created_at) + 3600 * 24) : null])
            ->orderBy('oaOrder.id desc');
    }

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/

    // Map method of field `freight_type`
    public static function getFreightTypeMap($prepend = false)
    {
        $map = alter('freight_type');

        return self::resetMap($map, $prepend);
    }

    // Format method of field `freight_type`
    public function getFreightTypeValue($value = null)
    {
        return $this->resetValue($value) ?: $value;
    }

    // Map method of field `order_state`
    public static function getOrderStateMap($prepend = false)
    {
        $map = alter('order_state');

        return self::resetMap($map, $prepend);
    }

    // Format method of field `order_state`
    public function getOrderStateValue($value = null)
    {
        return $this->resetValue($value) ?: $value;
    }
}
