<?php

namespace oa\models;

use Yii;

/**
 * 这是表 `oa_user_contact` 的模型
 */
class OaUserContact extends \common\components\ARModel
{
    public function rules()
    {
        return [
            [['user_id', 'content'], 'required'],
            [['user_id', 'created_by'], 'integer'],
            [['content'], 'default', 'value' => ''],
            [['created_at'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'content' => '联系内容',
            'created_at' => '联系时间',
            'created_by' => '联系人',
        ];
    }

    /****************************** 以下为设置关联模型的方法 ******************************/

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
                'oaUserContact.id' => $this->id,
                'oaUserContact.user_id' => $this->user_id,
                'oaUserContact.created_by' => $this->created_by,
            ])
            ->andFilterWhere(['like', 'oaUserContact.content', $this->content])
            ->andFilterWhere(['like', 'oaUserContact.created_at', $this->created_at])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/
}
