<?php

namespace common\models;

use Yii;

/**
 * 这是表 `api_app` 的模型
 */
class ApiApp extends \common\components\ARModel
{
    public function rules()
    {
        return [
            [['user_id', 'app_name', 'key'], 'required'],
            [['user_id', 'total', 'rest', 'allowance', 'allowance_updated_at', 'state'], 'integer'],
            [['rate_limit'], 'number'],
            [['created_at'], 'safe'],
            [['app_name', 'key', 'ip'], 'string', 'max' => 100],
            [['auth_date'], 'string', 'max' => 50],
            [['key'], 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '序号',
            'user_id' => 'User ID',
            'app_name' => '项目名',
            'key' => '秘钥',
            'rate_limit' => '每秒最大请求次数',
            'total' => '总调用次数',
            'rest' => '剩余调用次数',
            'ip' => 'IP',
            'allowance' => '当前剩余次数',
            'allowance_updated_at' => '最后请求时间',
            'state' => '状态',
            'auth_date' => '授权日期',
            'created_at' => '创建日期',
        ];
    }

    /****************************** 以下为设置关联模型的方法 ******************************/

    public function getUser()
    {
        return $this->hasOne(ApiUser::className(), ['id' => 'user_id']);
    }

    /****************************** 以下为公共显示条件的方法 ******************************/

    public function search()
    {
        $this->setSearchParams();

        return self::find()
            ->filterWhere([
                'apiApp.id' => $this->id,
                'apiApp.user_id' => $this->user_id,
                'apiApp.rate_limit' => $this->rate_limit,
                'apiApp.total' => $this->total,
                'apiApp.rest' => $this->rest,
                'apiApp.allowance' => $this->allowance,
                'apiApp.allowance_updated_at' => $this->allowance_updated_at,
                'apiApp.state' => $this->state,
            ])
            ->andFilterWhere(['like', 'apiApp.app_name', $this->app_name])
            ->andFilterWhere(['like', 'apiApp.key', $this->key])
            ->andFilterWhere(['like', 'apiApp.ip', $this->ip])
            ->andFilterWhere(['like', 'apiApp.auth_date', $this->auth_date])
            ->andFilterWhere(['like', 'apiApp.created_at', $this->created_at])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/

    // Map method of field `user_id`
    public static function getUserIdMap($prepend = false)
    {
        $map = ApiUser::map('id', 'nickname');

        return self::resetMap($map, $prepend);
    }

    // Format method of field `user_id`
    public function getUserIdValue($value = null)
    {
        return $this->resetValue($value);
    }
}
