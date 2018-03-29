<?php

namespace api\models;

use Yii;

/**
 * 这是表 `api_app` 的模型
 */
class ApiApp extends \common\components\ARModel
{
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'total', 'allowance', 'allowance_updated_at', 'state'], 'integer'],
            [['rate_limit'], 'number'],
            [['key'], 'string', 'max' => 100]
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'key' => '秘钥',
            'rate_limit' => '每秒最大请求次数',
            'total' => '总调用次数',
            'allowance' => '当前请求次数',
            'allowance_updated_at' => '最后请求时间',
            'state' => 'State',
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
                'apiApp.user_id' => $this->user_id,
                'apiApp.rate_limit' => $this->rate_limit,
                'apiApp.total' => $this->total,
                'apiApp.allowance' => $this->allowance,
                'apiApp.allowance_updated_at' => $this->allowance_updated_at,
                'apiApp.state' => $this->state,
            ])
            ->andFilterWhere(['like', 'apiApp.key', $this->key])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/
}
