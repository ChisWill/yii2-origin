<?php

namespace admin\models;

use Yii;
use common\helpers\Html;

/**
 * 这是表 `admin_action` 的模型
 */
class AdminAction extends \common\components\ARModel
{
    public $start_created_at;
    public $end_created_at;

    const TYPE_INSERT = 1;
    const TYPE_UPDATE = 2;
    const TYPE_DELETE = 3;

    public function rules()
    {
        return [
            [['key', 'action'], 'required'],
            [['key', 'type', 'created_by'], 'integer'],
            [['value'], 'default', 'value' => ''],
            [['created_at'], 'safe'],
            [['table_name', 'action'], 'string', 'max' => 100],
            [['field'], 'string', 'max' => 500]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'table_name' => '表名',
            'key' => '主键',
            'action' => '动作',
            'field' => '被修改的字段',
            'value' => '被修改的值',
            'type' => '操作类型：1更新，2插入，3删除',
            'created_at' => '操作时间',
            'created_by' => '操作者ID',
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
                'adminAction.id' => $this->id,
                'adminAction.key' => $this->key,
                'adminAction.type' => $this->type,
                'adminAction.created_by' => $this->created_by,
            ])
            ->andFilterWhere(['like', 'adminAction.table_name', $this->table_name])
            ->andFilterWhere(['like', 'adminAction.action', $this->action])
            ->andFilterWhere(['like', 'adminAction.field', $this->field])
            ->andFilterWhere(['like', 'adminAction.value', $this->value])
            ->andFilterWhere(['like', 'adminAction.created_at', $this->created_at])
            ->andTableSearch()
        ;
    }

    public function listQuery()
    {
        return $this->search()
                    ->andFilterWhere(['>=', 'created_at', $this->start_created_at])
                    ->andFilterWhere(['<=', 'created_at', $this->end_created_at]);
    }

    /****************************** 以下为公共操作的方法 ******************************/

    public static function add($type, $tableName, $key, $field = '')
    {
        if (is_array($field)) {
            $value = current($field);
            $field = key($field);
        } else {
            $value = '';
        }
        if (Yii::$app->controller) {
            self::dbInsert('admin_action', [
                'table_name' => $tableName,
                'key' => $key,
                'action' => Yii::$app->controller->module->id . '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id,
                'field' => $field,
                'value' => $value,
                'type' => $type,
                'created_at' => self::$time,
                'created_by' => u()->id
            ]);
        }
    }

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/

    // Map method of field `type`
    public static function getTypeMap($prepend = false)
    {
        $map = [
            self::TYPE_INSERT => 'INSERT',
            self::TYPE_UPDATE => 'UPDATE',
            self::TYPE_DELETE => 'DELETE'
        ];

        return self::resetMap($map, $prepend);
    }

    // Format method of field `type`
    public function getTypeValue($value = null)
    {
        $html = $this->resetValue($value);
        switch ($value) {
            case self::TYPE_INSERT:
                return Html::successSpan($html);
            case self::TYPE_UPDATE:
                return Html::warningSpan($html);
            case self::TYPE_DELETE:
                return Html::errorSpan($html);
        }
    }
}
