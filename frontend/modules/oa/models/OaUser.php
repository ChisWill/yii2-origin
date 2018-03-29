<?php

namespace oa\models;

use Yii;
use common\helpers\Url;
use common\helpers\Curl;
use \common\helpers\ArrayHelper;

/**
 * 这是表 `oa_user` 的模型
 */
class OaUser extends \oa\components\Model
{
    const TYPE_COMMON = 1;
    const TYPE_SPECIAL = 2;
    const TYPE_DEVELOP = 3;
    const TYPE_SUPPLIER = 4;
    const TYPE_CHANNEL = 5;

    const LEVEL_NEW = 1;
    const LEVEL_POTENTIAL = 2;
    const LEVEL_PRE = 3;
    const LEVEL_DEAL = 4;
    const LEVEL_VIP = 5;
    const LEVEL_YET = -1;
    const LEVEL_SPY = -2;
    const LEVEL_GIVEUP = -3;

    const IS_CHAT_YES = 1;
    const IS_CHAT_NO = -1;

    public $tipsType = OaTips::TYPE_USER;

    protected static $_productIds;
    protected static $_uids;
    protected static $_tips;

    public function rules()
    {
        return [
            [['type', 'product_id', 'level', 'is_chat', 'source', 'created_by', 'updated_by'], 'integer'],
            [['amount'], 'number'],
            [['requirement'], 'default', 'value' => ''],
            [['contact_time', 'created_at', 'updated_at'], 'safe'],
            [['name', 'tel', 'wechat_id'], 'string', 'max' => 50],
            [['qq'], 'string', 'max' => 20]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '序号',
            'name' => '姓名',
            'type' => '客户类型：1普通客户，2专属客户，3开发商，4供货商，5渠道商',
            'product_id' => '产品ID',
            'level' => '客户等级：1新客户，2潜在客户，3准成交客户，4成交客户，5VIP客户，-1暂无意向客户，-2刺探型客户，-3已放弃客户',
            'amount' => '项目金额',
            'is_chat' => '是否正在洽谈',
            'tel' => '联系电话',
            'wechat_id' => '微信号',
            'qq' => 'QQ',
            'source' => '客户来源',
            'requirement' => '需求',
            'contact_time' => '最后联系时间',
            'created_at' => '创建时间',
            'created_by' => '联系人',
            'updated_at' => '最后更新时间',
            'updated_by' => '最后修改人',
        ];
    }

    /****************************** 以下为设置关联模型的方法 ******************************/

    public function getAdminUser()
    {
        return $this->hasOne(AdminUser::className(), ['id' => 'created_by'])->select(['id', 'username', 'realname', 'power', 'state']);
    }

    public function getProduct()
    {
        return $this->hasOne(OaProduct::className(), ['id' => 'product_id']);
    }

    /****************************** 以下为公共显示条件的方法 ******************************/

    public function search()
    {
        $this->setSearchParams();

        return self::find()
            ->filterWhere([
                'oaUser.id' => $this->id,
                'oaUser.type' => $this->type,
                'oaUser.product_id' => $this->product_id,
                'oaUser.level' => $this->level,
                'oaUser.amount' => $this->amount,
                'oaUser.is_chat' => $this->is_chat,
                'oaUser.source' => $this->source,
                'oaUser.created_by' => $this->created_by,
                'oaUser.updated_by' => $this->updated_by,
            ])
            ->andFilterWhere(['like', 'oaUser.name', $this->name])
            ->andFilterWhere(['like', 'oaUser.tel', $this->tel])
            ->andFilterWhere(['like', 'oaUser.wechat_id', $this->wechat_id])
            ->andFilterWhere(['like', 'oaUser.qq', $this->qq])
            ->andFilterWhere(['like', 'oaUser.requirement', $this->requirement])
            ->andFilterWhere(['like', 'oaUser.contact_time', $this->contact_time])
            ->andFilterWhere(['like', 'oaUser.created_at', $this->created_at])
            ->andFilterWhere(['like', 'oaUser.updated_at', $this->updated_at])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    public function notify($info = '')
    {
        $uids = AdminUser::find()
            ->where(['state' => AdminUser::STATE_VALID])
            ->andWhere(['<>', 'id', u()->id])
            ->select('id')
            ->column();
        $pushApiUrl = config('webDomain') . ':' . config('httpPushPort');

        $data = [
           'info' => $info,
           'uids' => implode(',', $uids),
           'event' => 'notify'
        ];

        Curl::get(Url::create($pushApiUrl, $data));

        return $uids;
    }

    public function listQuery()
    {
        return $this->search()
                    ->andWhere(u()->isMaster ?: ['oaUser.created_by' => u()->id])
                    ->joinWith('adminUser')
                    ->orderBy('oaUser.id DESC');
    }

    public function statisticsQuery()
    {
        return $this->search()
                    ->joinWith(['adminUser', 'product'])
                    ->select(['oaUser.id', 'type', 'is_chat', 'product_id', 'level', 'oaUser.created_by'])
                    ->andFilterWhere(['>=', 'oaUser.created_at', $this->start_date])
                    ->andFilterWhere(['<', 'oaUser.created_at', $this->end_date ? date('Y-m-d', strtotime($this->end_date) + 3600 * 24) : null])
                    ->asArray();
    }

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/

    // Map method of field `type`
    public static function getTypeMap($prepend = false)
    {
        $map = [
            self::TYPE_COMMON => '普通客户',
            self::TYPE_SPECIAL => '专属客户',
            self::TYPE_DEVELOP => '开发商',
            self::TYPE_SUPPLIER => '供货商',
            self::TYPE_CHANNEL => '渠道商'
        ];

        return self::resetMap($map, $prepend);
    }

    // Format method of field `type`
    public function getTypeValue($value = null)
    {
        return $this->resetValue($value);
    }

    // Map method of field `product_id`
    public static function getProductIdMap($prepend = false)
    {
        if (self::$_productIds === null) {
            self::$_productIds = OaProduct::map('id', 'name');
        }

        return self::resetMap(self::$_productIds, $prepend);
    }

    // Format method of field `product_id`
    public function getProductIdValue($value = null)
    {
        return $this->resetValue($value);
    }

    // Map method of field `is_chat`
    public static function getIsChatMap($prepend = false)
    {
        $map = [
            self::IS_CHAT_YES => '是',
            self::IS_CHAT_NO => '否',
        ];

        return self::resetMap($map, $prepend);
    }

    // Format method of field `is_chat`
    public function getIsChatValue($value = null)
    {
        return $this->resetValue($value);
    }

    // Map method of field `source`
    public static function getSourceMap($prepend = false)
    {
        $map = Map::find()->where(['type' => Map::TYPE_OA_SOURCE])->map('id', 'name');

        return self::resetMap($map, $prepend);
    }

    // Format method of field `source`
    public function getSourceValue($value = null)
    {
        return $this->resetValue($value);
    }

    // Map method of field `level`
    public static function getLevelMap($prepend = false, $level = 0)
    {
        $map = [
            self::LEVEL_NEW => '新客户',
            self::LEVEL_POTENTIAL => '潜在客户',
            self::LEVEL_PRE => '准成交客户',
            self::LEVEL_DEAL => '已成交客户',
            self::LEVEL_VIP => 'VIP客户',
            self::LEVEL_YET => '暂无意向客户',
            self::LEVEL_SPY => '刺探型客户',
            self::LEVEL_GIVEUP => '已放弃客户'
        ];
        if ($level) {
            $filterKeys = array_flip(array_filter(array_keys($map), function ($value) use ($level) {
                if ($level > 0) {
                    return $value >= $level;
                } else {
                    return $value <= $level;
                }
            }));
            $map = array_intersect_key($map, $filterKeys);
        }

        return self::resetMap($map, $prepend);
    }

    // Format method of field `level`
    public function getLevelValue($value = null)
    {
        return $this->resetValue($value);
    }

    // Map method of field `created_by`
    public static function getCreatedByMap($prepend = false)
    {
        if (self::$_uids === null) {
            foreach (OaUser::find()->joinWith('adminUser')->groupBy('oaUser.created_by')->select('oaUser.created_by')->asArray()->all() as $value) {
                self::$_uids[$value['adminUser']['id']] = $value['adminUser']['realname'];
            }
        }

        return self::resetMap(self::$_uids, $prepend);
    }

    // Format method of field `created_by`
    public function getCreatedByValue($value = null)
    {
        return $this->resetValue($value);
    }
}
