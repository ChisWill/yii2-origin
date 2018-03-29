<?php

namespace oa\models;

use Yii;

/**
 * 这是表 `oa_process` 的模型
 */
class OaProcess extends \common\components\ARModel
{
    public function rules()
    {
        return [
            [['app_id', 'desc'], 'required'],
            [['app_id', 'created_by'], 'integer'],
            [['desc'], 'default', 'value' => ''],
            [['created_at'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_id' => 'App ID',
            'desc' => '进度描述',
            'created_at' => '发表时间',
            'created_by' => '发表人',
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
                'oaProcess.id' => $this->id,
                'oaProcess.app_id' => $this->app_id,
                'oaProcess.created_by' => $this->created_by,
            ])
            ->andFilterWhere(['like', 'oaProcess.desc', $this->desc])
            ->andFilterWhere(['like', 'oaProcess.created_at', $this->created_at])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/
}
