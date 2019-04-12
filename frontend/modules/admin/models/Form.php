<?php

namespace admin\models;

use Yii;
use common\helpers\Html;
use common\helpers\ArrayHelper;

class Form extends \common\models\Form
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            // [['field1', 'field2'], 'required', 'message' => '{attribute} is required'],
        ]);
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            // 'scenario' => ['field1', 'field2'],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            // 'field1' => 'description1',
            // 'field2' => 'description2',
        ]);
    }

    public static function getTableColumns($params = [])
    {
        $getColumn = function ($column, $header) {
            return [
                'header' => $header,
                'value' => function ($row) use ($column) {
                    $map = ArrayHelper::map($row->items, 'name', 'value');
                    if (isset($map[$column])) {
                        return $map[$column];
                    } else {
                        return '';
                    }
                }
            ];
        };
        $columns = ['id'];
        foreach ($params as $item) {
            $columns[] = $getColumn(key($item), current($item));
        }
        $columns['is_read'] = function ($row) {
            if ($row->is_read > 0) {
                return Html::successSpan($row->isReadValue);
            } else {
                return Html::errorSpan($row->isReadValue);
            }
        };
        $columns['created_at'] = ['header' => '日期', 'value' => function ($row) {
            return date('Y-m-d H:i', strtotime($row->created_at));
        }];
        return $columns;
    }

    public static function getTypeQuery($type)
    {
        return self::find()
            ->with('items')
            ->where(['type' => $type])
            ->orderBy('id DESC');
    }
}
