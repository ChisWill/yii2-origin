<?php

namespace api\models;

use Yii;

/**
 * 这是表 `api_user` 的模型
 */
class ApiUser extends \common\components\ARModel
{
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['state'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['username'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 100]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '账号',
            'password' => '密码',
            'state' => '状态',
            'created_at' => 'Created At',
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
                'apiUser.id' => $this->id,
                'apiUser.state' => $this->state,
            ])
            ->andFilterWhere(['like', 'apiUser.username', $this->username])
            ->andFilterWhere(['like', 'apiUser.password', $this->password])
            ->andFilterWhere(['like', 'apiUser.created_at', $this->created_at])
            ->andFilterWhere(['like', 'apiUser.updated_at', $this->updated_at])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/
}
