<?php

namespace common\models;

use Yii;

/**
 * 这是表 `user_withdraw` 的模型
 */
class UserWithdraw extends \common\components\ARModel
{
    public function rules()
    {
        return [
            [['user_id', 'account_id', 'amount', 'rest_amount'], 'required'],
            [['user_id', 'account_id', 'op_state', 'updated_by'], 'integer'],
            [['amount', 'rest_amount', 'fee'], 'number'],
            [['created_at', 'updated_at'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户ID',
            'account_id' => '出金账号ID',
            'amount' => '出金金额',
            'rest_amount' => '余额',
            'fee' => '手续费',
            'op_state' => '出金状态：1待审核，2已通过，-1驳回',
            'created_at' => '申请时间',
            'updated_at' => '审核时间',
            'updated_by' => '审核人',
        ];
    }

    /****************************** 以下为设置关联模型的方法 ******************************/

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getUserAccount()
    {
        return $this->hasOne(UserAccount::className(), ['id' => 'account_id']);
    }

    /****************************** 以下为公共显示条件的方法 ******************************/

    public function search()
    {
        $this->setSearchParams();

        return self::find()
            ->filterWhere([
                'userWithdraw.id' => $this->id,
                'userWithdraw.user_id' => $this->user_id,
                'userWithdraw.account_id' => $this->account_id,
                'userWithdraw.amount' => $this->amount,
                'userWithdraw.rest_amount' => $this->rest_amount,
                'userWithdraw.fee' => $this->fee,
                'userWithdraw.op_state' => $this->op_state,
                'userWithdraw.updated_by' => $this->updated_by,
            ])
            ->andFilterWhere(['like', 'userWithdraw.created_at', $this->created_at])
            ->andFilterWhere(['like', 'userWithdraw.updated_at', $this->updated_at])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/
}
