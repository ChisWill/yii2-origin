<?php

namespace oa\models;

use Yii;

/**
 * 这是表 `oa_tips` 的模型
 */
class OaTips extends \common\components\ARModel
{
    const READ_STATE_WAIT = -1;
    const READ_STATE_DONE = 1;

    const TYPE_APP = 1;
    const TYPE_USER = 2;

    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'target_id', 'type', 'read_state'], 'integer'],
            [['updated_at'], 'safe'],
            [['field'], 'string', 'max' => 20],
            [['user_id', 'target_id', 'field', 'type'], 'unique', 'targetAttribute' => ['user_id', 'target_id', 'field', 'type'], 'message' => 'The combination of User ID, Target ID, Field and 提示类别：1项目信息，2客户信息 has already been taken.']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'target_id' => 'Target ID',
            'field' => 'Field',
            'type' => '提示类别：1项目信息，2客户信息',
            'read_state' => 'Read State',
            'updated_at' => 'Updated At',
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
                'oaTips.id' => $this->id,
                'oaTips.user_id' => $this->user_id,
                'oaTips.target_id' => $this->target_id,
                'oaTips.type' => $this->type,
                'oaTips.read_state' => $this->read_state,
            ])
            ->andFilterWhere(['like', 'oaTips.field', $this->field])
            ->andFilterWhere(['like', 'oaTips.updated_at', $this->updated_at])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/
}
