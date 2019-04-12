<?php

namespace common\models;

use Yii;

/**
 * 这是表 `user_account` 的模型
 */
class UserAccount extends \common\components\ARModel
{
    public function rules()
    {
        return [
            [['user_id', 'bank_name', 'bank_card', 'bank_user', 'id_number', 'bank_mobile', 'bank_address'], 'required'],
            [['user_id', 'account_type', 'owner_type', 'state'], 'integer'],
            [['created_at'], 'safe'],
            [['bank_name', 'bank_card', 'bank_user', 'id_number'], 'string', 'max' => 50],
            [['bank_mobile'], 'string', 'max' => 11],
            [['bank_address'], 'string', 'max' => 100]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户ID',
            'bank_name' => '银行名称',
            'bank_card' => '银行卡号',
            'bank_user' => '持卡人姓名',
            'id_number' => '身份证号',
            'bank_mobile' => '银行预留手机号',
            'bank_address' => '开户行地址',
            'account_type' => '账户类型：1提现账户，2充值账户',
            'owner_type' => '所属用户类型：1普通用户，2管理员',
            'state' => '有效状态',
            'created_at' => 'Created At',
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
                'userAccount.id' => $this->id,
                'userAccount.user_id' => $this->user_id,
                'userAccount.account_type' => $this->account_type,
                'userAccount.owner_type' => $this->owner_type,
                'userAccount.state' => $this->state,
            ])
            ->andFilterWhere(['like', 'userAccount.bank_name', $this->bank_name])
            ->andFilterWhere(['like', 'userAccount.bank_card', $this->bank_card])
            ->andFilterWhere(['like', 'userAccount.bank_user', $this->bank_user])
            ->andFilterWhere(['like', 'userAccount.id_number', $this->id_number])
            ->andFilterWhere(['like', 'userAccount.bank_mobile', $this->bank_mobile])
            ->andFilterWhere(['like', 'userAccount.bank_address', $this->bank_address])
            ->andFilterWhere(['like', 'userAccount.created_at', $this->created_at])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/
}
