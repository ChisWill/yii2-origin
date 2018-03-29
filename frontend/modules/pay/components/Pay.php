<?php

namespace pay\components;

use Yii;
use common\models\User;

class Pay extends \common\models\UserCharge
{
    const CHANNEL_YTF = 1; // 云通付
    const CHANNEL_QTB = 2; // 钱通宝

    public $code;
    public static $amountList = [5000, 3000, 2000, 1000, 500, 300, 200, 100, 50, 30, 0.1];

    public static function tableName()
    {
        return '{{%user_charge}}';
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            ['charge_type', 'in', 'range' => [self::CHANNEL_YTF, self::CHANNEL_QTB]],
            ['amount', 'compare', 'operator' => '>=', 'compareValue' => end(self::$amountList), 'message' => '{attribute}必须大于' . end(self::$amountList)],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'charge_type' => '充值渠道'
        ]);
    }

    public static function getChargeTypeMap($prepend = false)
    {
        $map = [
            self::CHANNEL_QTB => '钱通宝',
            self::CHANNEL_YTF => '支付宝',
        ];

        return self::resetMap($map, $prepend);
    }

    public static function getChargeTypeClass()
    {
        return [
            self::CHANNEL_QTB => 'pay\models\QianTongBao',
            self::CHANNEL_YTF => 'pay\models\YunTongFu',
        ];
    }

    public static function getChargeTypeImage()
    {
        return [
            self::CHANNEL_YTF => 'alipay.png',
        ];
    }

    public function getNotifyUrl()
    {
        return url(['site/notify', 'type' => $this->charge_type], true);
    }

    public function getReturnUrl()
    {
        return url(['site/return', 'type' => $this->charge_type], true);
    }

    public function ready()
    {
        $class = self::getChargeTypeClass()[$this->charge_type];
        $model = new $class;
        $model->amount = $this->amount;
        $model->charge_type = $this->charge_type;
        $model->trade_no = $this->trade_no;
        $this->code = $model->getCode();
        return $model->ready();
    }

    public static function saveCharge($tradeNo)
    {
        $userCharge = (new static)->findOne([
            'trade_no' => $tradeNo,
            'charge_state' => ['in', static::CHARGE_STATE_WAIT, static::CHARGE_STATE_FAIL]
        ]);
        if ($userCharge) {
            $user = User::findOne($userCharge->user_id);
            $user->account += ($userCharge->amount * (100 - $userCharge->fee) / 100);
            $user->update(false);
            $userCharge->charge_state = static::CHARGE_STATE_PASS;
            $userCharge->update(false);
        }
    }

    public static function notify($type)
    {
        try {
            $class = self::getChargeTypeClass()[$type];
            return $class::charge();
        } catch (\yii\base\ErrorException $e) {
            l($e, 'pay');
            throwex('ACCESS DENY');
        }
    }

    public function validate($attributeNames = null, $clearErrors = true)
    {
        $this->user_id = u()->id;
        $this->trade_no = u()->id . date('YmdHis');
        $this->rest_amount = $this->amount;
        $this->fee = config('charge_fee', 0);
        if (parent::validate($attributeNames, $clearErrors)) {
            return $this->insert(false);
        } else {
            return false;
        }
    }
}
