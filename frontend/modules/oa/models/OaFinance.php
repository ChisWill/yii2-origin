<?php

namespace oa\models;

use Yii;
use common\helpers\Html;

/**
 * 这是表 `oa_finance` 的模型
 */
class OaFinance extends \common\components\ARModel
{
    const TYPE_INCOME = 1;
    const TYPE_SPEND = 2;

    const CATEGORY_HUMAN = 4;
    const CATEGORY_SHARE = 9;

    public $month;

    public function rules()
    {
        return [
            [['category_id', 'amount'], 'required'],
            [['category_id', 'type', 'created_by', 'updated_by'], 'integer'],
            [['amount'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['remark'], 'string', 'max' => 200]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => '分类',
            'amount' => '金额',
            'type' => '收支类型',
            'remark' => '备注',
            'created_at' => '时间',
            'created_by' => '录入人',
            'updated_at' => '更新时间',
            'updated_by' => 'Updated By',
        ];
    }

    /****************************** 以下为设置关联模型的方法 ******************************/

    public function getCategory()
    {
        return $this->hasOne(OaFinanceCategory::className(), ['id' => 'category_id']);
    }

    public function getAdminUser()
    {
        return $this->hasOne(AdminUser::className(), ['id' => 'created_by']);
    }

    /****************************** 以下为公共显示条件的方法 ******************************/

    public function search()
    {
        $this->setSearchParams();

        return self::find()
            ->filterWhere([
                'oaFinance.id' => $this->id,
                'oaFinance.category_id' => $this->category_id,
                'oaFinance.amount' => $this->amount,
                'oaFinance.type' => $this->type,
                'oaFinance.created_by' => $this->created_by,
                'oaFinance.updated_by' => $this->updated_by,
            ])
            ->andFilterWhere(['like', 'oaFinance.remark', $this->remark])
            ->andFilterWhere(['like', 'oaFinance.created_at', $this->created_at])
            ->andFilterWhere(['like', 'oaFinance.updated_at', $this->updated_at])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    public function accountListQuery()
    {
        $query = $this->search();
        if ($this->month) {
            $endMonth = $this->month . '-31 23:59:59';
        } else {
            $endMonth = null;
        }
        return $query
            ->joinWith(['category', 'adminUser'])
            ->andFilterWhere(['>=', 'oaFinance.created_at', $this->month])
            ->andFilterWhere(['<=', 'oaFinance.created_at', $endMonth])
            ->andFilterWhere(['>=', 'oaFinance.created_at', $this->start_date])
            ->andFilterWhere(['<', 'oaFinance.created_at', $this->end_date ? date('Y-m-d', strtotime($this->end_date) + 3600 * 24) : null])
            ->orderBy('oaFinance.created_at DESC, oaFinance.id DESC');
    }

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/

    // Map method of field `type`
    public static function getTypeMap($prepend = false)
    {
        $map = [
            self::TYPE_INCOME => '收入',
            self::TYPE_SPEND => '支出'
        ];

        return self::resetMap($map, $prepend);
    }

    // Format method of field `type`
    public function getTypeValue($value = null)
    {
        $text = $this->resetValue($value);

        switch ($value) {
            case self::TYPE_INCOME:
                $text = Html::successSpan($text);
                break;
            case self::TYPE_SPEND:
                $text = Html::errorSpan($text);
                break;
        }
        return $text;
    }

    // Map method of field `category_id`
    public static function getCategoryIdMap($prepend = false)
    {
        $map = OaFinanceCategory::find()->map('id', 'name');

        return self::resetMap($map, $prepend);
    }

    // Format method of field `category_id`
    public function getCategoryIdValue($value = null)
    {
        return $this->resetValue($value);
    }
}
