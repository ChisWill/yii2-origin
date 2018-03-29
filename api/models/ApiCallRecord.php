<?php

namespace api\models;

use Yii;

/**
 * 这是表 `api_call_record` 的模型
 */
class ApiCallRecord extends \common\components\ARModel
{
    public function rules()
    {
        return [
            [['app_id'], 'required'],
            [['app_id'], 'integer'],
            [['post_data'], 'default', 'value' => ''],
            [['created_at'], 'safe'],
            [['method'], 'string', 'max' => 10],
            [['url'], 'string', 'max' => 255],
            [['ip'], 'string', 'max' => 100]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_id' => 'App ID',
            'method' => '方式',
            'url' => '链接',
            'ip' => 'IP',
            'post_data' => '提交数据',
            'created_at' => '调用日期',
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
                'apiCallRecord.id' => $this->id,
                'apiCallRecord.app_id' => $this->app_id,
            ])
            ->andFilterWhere(['like', 'apiCallRecord.method', $this->method])
            ->andFilterWhere(['like', 'apiCallRecord.url', $this->url])
            ->andFilterWhere(['like', 'apiCallRecord.ip', $this->ip])
            ->andFilterWhere(['like', 'apiCallRecord.post_data', $this->post_data])
            ->andFilterWhere(['like', 'apiCallRecord.created_at', $this->created_at])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/
}
