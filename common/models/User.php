<?php

namespace common\models;

use Yii;

/**
 * 这是表 `user` 的模型
 */
class User extends \common\components\ARModel
{
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['account'], 'number'],
            [['sex', 'vip', 'state', 'created_by', 'updated_by'], 'integer'],
            [['desc'], 'default', 'value' => ''],
            [['created_at', 'updated_at'], 'safe'],
            [['username', 'nickname'], 'string', 'max' => 20],
            [['password', 'old_pass', 'face'], 'string', 'max' => 80],
            [['mobile'], 'string', 'max' => 11],
            [['area'], 'string', 'max' => 100],
            [['username'], 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'password' => '密码',
            'old_pass' => '旧密码',
            'mobile' => '手机号',
            'nickname' => '昵称',
            'account' => '账户余额',
            'sex' => '性别：1男，2女',
            'area' => '地区',
            'desc' => '个人介绍',
            'vip' => 'VIP等级',
            'face' => '头像',
            'state' => '状态',
            'created_at' => '注册时间',
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
                'user.id' => $this->id,
                'user.account' => $this->account,
                'user.sex' => $this->sex,
                'user.vip' => $this->vip,
                'user.state' => $this->state,
                'user.created_by' => $this->created_by,
                'user.updated_by' => $this->updated_by,
            ])
            ->andFilterWhere(['like', 'user.username', $this->username])
            ->andFilterWhere(['like', 'user.password', $this->password])
            ->andFilterWhere(['like', 'user.old_pass', $this->old_pass])
            ->andFilterWhere(['like', 'user.mobile', $this->mobile])
            ->andFilterWhere(['like', 'user.nickname', $this->nickname])
            ->andFilterWhere(['like', 'user.area', $this->area])
            ->andFilterWhere(['like', 'user.desc', $this->desc])
            ->andFilterWhere(['like', 'user.face', $this->face])
            ->andFilterWhere(['like', 'user.created_at', $this->created_at])
            ->andFilterWhere(['like', 'user.updated_at', $this->updated_at])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    public function hashPassword()
    {
        $this->password = Yii::$app->security->generatePasswordHash($this->password);

        return $this;
    }

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/
}
