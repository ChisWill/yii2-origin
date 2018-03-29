<?php

namespace oa\models;

use Yii;
use common\helpers\Hui;
use common\helpers\Html;
use common\helpers\Url;
use common\helpers\Curl;
use common\helpers\ArrayHelper;

/**
 * 这是表 `oa_app` 的模型
 */
class OaApp extends \oa\components\Model
{
    public $tipsType = OaTips::TYPE_APP;

    protected static $_type;
    protected static $_tips = null;

    public function rules()
    {
        return [
            [['code'], 'required'],
            [['server_id', 'has_simulater', 'created_by', 'updated_by'], 'integer'],
            [['total_amount', 'rest_amount'], 'number'],
            [['server_info', 'wechat_info', 'pay_info', 'sms_info', 'requirement_info', 'process_info'], 'default', 'value' => ''],
            [['created_at', 'updated_at'], 'safe'],
            [['code', 'ip', 'type'], 'string', 'max' => 20],
            [['name', 'domain', 'ios_sign', 'monthly'], 'string', 'max' => 50],
            [['server_rent'], 'string', 'max' => 100]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => '项目代号',
            'name' => '项目名称',
            'server_id' => '所在服务器',
            'total_amount' => '项目金额',
            'rest_amount' => '余款',
            'domain' => '域名',
            'ip' => 'IP',
            'ios_sign' => 'IOS月费',
            'monthly' => '月费',
            'server_rent' => '服务器租赁信息',
            'type' => '项目类型',
            'has_simulater' => '是否包含模拟软件',
            'server_info' => '服务器信息',
            'wechat_info' => '微信信息',
            'pay_info' => '支付信息',
            'sms_info' => '短信接口信息',
            'requirement_info' => 'APP需求信息',
            'process_info' => '进度描述',
            'created_at' => '创建时间',
            'created_by' => '创建人',
            'updated_at' => '最后修改时间',
            'updated_by' => '最后修改人',
        ];
    }

    /****************************** 以下为设置关联模型的方法 ******************************/

    public function getServer()
    {
        return $this->hasOne(OaServer::className(), ['id' => 'server_id']);
    }

    /****************************** 以下为公共显示条件的方法 ******************************/

    public function search()
    {
        $this->setSearchParams();

        return self::find()
            ->filterWhere([
                'oaApp.id' => $this->id,
                'oaApp.server_id' => $this->server_id,
                'oaApp.total_amount' => $this->total_amount,
                'oaApp.rest_amount' => $this->rest_amount,
                'oaApp.has_simulater' => $this->has_simulater,
                'oaApp.created_by' => $this->created_by,
                'oaApp.updated_by' => $this->updated_by,
            ])
            ->andFilterWhere(['like', 'oaApp.code', $this->code])
            ->andFilterWhere(['like', 'oaApp.name', $this->name])
            ->andFilterWhere(['like', 'oaApp.domain', $this->domain])
            ->andFilterWhere(['like', 'oaApp.ip', $this->ip])
            ->andFilterWhere(['like', 'oaApp.ios_sign', $this->ios_sign])
            ->andFilterWhere(['like', 'oaApp.monthly', $this->monthly])
            ->andFilterWhere(['like', 'oaApp.server_rent', $this->server_rent])
            ->andFilterWhere(['like', 'oaApp.type', $this->type])
            ->andFilterWhere(['like', 'oaApp.server_info', $this->server_info])
            ->andFilterWhere(['like', 'oaApp.wechat_info', $this->wechat_info])
            ->andFilterWhere(['like', 'oaApp.pay_info', $this->pay_info])
            ->andFilterWhere(['like', 'oaApp.sms_info', $this->sms_info])
            ->andFilterWhere(['like', 'oaApp.requirement_info', $this->requirement_info])
            ->andFilterWhere(['like', 'oaApp.process_info', $this->process_info])
            ->andFilterWhere(['like', 'oaApp.created_at', $this->created_at])
            ->andFilterWhere(['like', 'oaApp.updated_at', $this->updated_at])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    public function advanceInfo($key, $field)
    {
        if (in_array($field, ['requirement_info', 'process_info'])) {
            $action = 'commonUpdate';
        } else {
            $action = 'advanceUpdate';
        }
        if (u()->can('app/advanceUpdate') || $action !== 'advanceUpdate') {
            if ($this->$field) {
                $tips = $this->isNewTips($field) ? '<i class="tips"></i>' : '';
                return Hui::warningBtn('更新', [$action, 'type' => 'update', 'id' => $key, 'field' => $field], ['class' => 'info-fancybox fancybox.iframe']) . '&nbsp;&nbsp;' . Hui::primaryBtn('查看', [$action, 'type' => 'view', 'id' => $key, 'field' => $field], ['class' => 'info-fancybox fancybox.ajax']) . $tips;
            } else {
                return Hui::successBtn('新建', [$action, 'type' => 'update', 'id' => $key, 'field' => $field], ['class' => 'info-fancybox fancybox.iframe']);
            }
        } else {
            return '***';
        }
    }

    public function notify($field = '')
    {
        $query = AdminUser::find()
            ->where(['state' => AdminUser::STATE_VALID])
            ->andWhere(['<>', 'id', u()->id])
            ->select('id');
        if ($field && $field != 'process_info') {
            $query->andWhere(['>=', 'power', 9999]);
        }
        $uids = $query->column();
        if ($field) {
            $this->tips($uids, $field);
        }

        $pushApiUrl = config('webDomain') . ':' . config('httpPushPort');

        $data = [
            'info' => $field ? u()->realname . '更新了' . $this->code . '的' . $this->label($field) : u()->realname . '创建了 ' . $this->code . ' 项目',
            'uids' => implode(',', $uids),
            'url' => url(['app/list', 'search[code]' => $this->code], true),
            'event' => 'notify'
        ];

        Curl::get(Url::create($pushApiUrl, $data));
    }

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/

    // Map method of field `type`
    public static function getTypeMap($prepend = false)
    {
        if (self::$_type === null) {
            self::$_type = OaProduct::map('id', 'name');
        }

        return self::resetMap(self::$_type, $prepend);
    }

    // Format method of field `type`
    public function getTypeValue($value = null)
    {
        return $this->resetValue($value);
    }

    // Map method of field `server_id`
    public static function getServerIdMap($prepend = false)
    {
        $map = OaServer::map('id', 'server_name');

        return self::resetMap($map, $prepend);
    }

    // Format method of field `server_id`
    public function getServerIdValue($value = null)
    {
        return $this->resetValue($value);
    }
}
