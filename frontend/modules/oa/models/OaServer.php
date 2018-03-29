<?php

namespace oa\models;

use Yii;

/**
 * 这是表 `oa_server` 的模型
 */
class OaServer extends \common\components\ARModel
{
    const USE_STATE_YES = 1;
    const USE_STATE_NO = -1;

    public function rules()
    {
        return [
            [['server_name', 'server_ip', 'quoted_price', 'discount_price', 'platform_id', 'account_id'], 'required'],
            [['quoted_price', 'discount_price'], 'number'],
            [['platform_id', 'account_id', 'use_state'], 'integer'],
            [['remark'], 'default', 'value' => ''],
            [['created_at', 'updated_at'], 'safe'],
            [['server_name', 'server_ip'], 'string', 'max' => 100]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'server_name' => '服务器名称',
            'server_ip' => '服务器IP',
            'quoted_price' => '报价',
            'discount_price' => '折扣价',
            'platform_id' => '所属平台',
            'account_id' => '账号名',
            'remark' => '备注信息',
            'use_state' => '使用状态',
            'created_at' => '创建日期',
            'updated_at' => '更新日期',
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
                'oaServer.id' => $this->id,
                'oaServer.quoted_price' => $this->quoted_price,
                'oaServer.discount_price' => $this->discount_price,
                'oaServer.platform_id' => $this->platform_id,
                'oaServer.account_id' => $this->account_id,
                'oaServer.use_state' => $this->use_state,
            ])
            ->andFilterWhere(['like', 'oaServer.server_name', $this->server_name])
            ->andFilterWhere(['like', 'oaServer.server_ip', $this->server_ip])
            ->andFilterWhere(['like', 'oaServer.remark', $this->remark])
            ->andFilterWhere(['like', 'oaServer.created_at', $this->created_at])
            ->andFilterWhere(['like', 'oaServer.updated_at', $this->updated_at])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/

    // Map method of field `platform_id`
    public static function getPlatformIdMap($prepend = false)
    {
        $map = Map::find()->where(['type' => Map::TYPE_OA_PLATFORM])->map('id', 'name');

        return self::resetMap($map, $prepend);
    }

    // Format method of field `platform_id`
    public function getPlatformIdValue($value = null)
    {
        return $this->resetValue($value);
    }

    // Map method of field `account_id`
    public static function getAccountIdMap($prepend = false)
    {
        $map = Map::find()->where(['type' => Map::TYPE_OA_ACCOUNT])->map('id', 'name');

        return self::resetMap($map, $prepend);
    }

    // Format method of field `account_id`
    public function getAccountIdValue($value = null)
    {
        return $this->resetValue($value);
    }

    // Map method of field `use_state`
    public static function getUseStateMap($prepend = false)
    {
        $map = [
            self::USE_STATE_YES => '使用中',
            self::USE_STATE_NO => '已废弃',
        ];

        return self::resetMap($map, $prepend);
    }

    // Format method of field `use_state`
    public function getUseStateValue($value = null)
    {
        return $this->resetValue($value);
    }
}
