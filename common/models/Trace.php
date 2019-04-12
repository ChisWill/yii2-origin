<?php

namespace common\models;

use Yii;

/**
 * 这是表 `trace` 的模型
 */
class Trace extends \common\components\ARModel
{
    public function rules()
    {
        return [
            [['user_id', 'page_name', 'ip'], 'required'],
            [['user_id', 'duration'], 'integer'],
            [['created_at'], 'safe'],
            [['page_name', 'ip', 'page_title'], 'string', 'max' => 100],
            [['referrer'], 'string', 'max' => 255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '访问用户',
            'page_name' => '页面路径',
            'ip' => 'IP',
            'page_title' => '页面标题',
            'referrer' => '访问来源',
            'duration' => '访问时长',
            'created_at' => '记录时间',
        ];
    }

    /****************************** 以下为设置关联模型的方法 ******************************/

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
                'trace.id' => $this->id,
                'trace.user_id' => $this->user_id,
                'trace.duration' => $this->duration,
            ])
            ->andFilterWhere(['like', 'trace.page_name', $this->page_name])
            ->andFilterWhere(['like', 'trace.ip', $this->ip])
            ->andFilterWhere(['like', 'trace.page_title', $this->page_title])
            ->andFilterWhere(['like', 'trace.referrer', $this->referrer])
            ->andFilterWhere(['like', 'trace.created_at', $this->created_at])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/
}
