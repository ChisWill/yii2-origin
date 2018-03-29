<?php

namespace oa\components;

use Yii;
use oa\models\OaTips;
use common\helpers\ArrayHelper;

/**
 * @author ChisWill
 */
class Model extends \common\components\ARModel
{
    public function tips($uids, $field)
    {
        self::dbDelete('oa_tips', ['user_id' => $uids, 'target_id' => $this->id, 'field' => $field, 'type' => $this->tipsType, 'read_state' => OaTips::READ_STATE_DONE]);
        foreach ($uids as $uid) {
            try {
                self::dbInsert('oa_tips', ['user_id' => $uid, 'target_id' => $this->id, 'field' => $field, 'type' => $this->tipsType]);
            } catch (\yii\db\IntegrityException $e) {
                // do nothing...
            }
        }
    }

    public function readTips($field)
    {
        self::dbUpdate('oa_tips', ['read_state' => OaTips::READ_STATE_DONE], ['user_id' => u()->id, 'target_id' => $this->id, 'field' => $field, 'type' => $this->tipsType, 'read_state' => OaTips::READ_STATE_WAIT]);
    }

    public function isNewTips($field)
    {
        if (static::$_tips === null) {
            $ret = OaTips::find()->where(['user_id' => u()->id, 'read_state' => OaTips::READ_STATE_WAIT, 'type' => $this->tipsType])->asArray()->all();
            static::$_tips = ArrayHelper::map($ret, 'id', 'field', 'target_id');
        }
        return isset(static::$_tips[$this->id]) && in_array($field, static::$_tips[$this->id]);
    }
}
