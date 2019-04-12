<?php

namespace common\models;

use Yii;
use yii\base\UnknownPropertyException;

/**
 * 这是表 `form` 的模型
 */
class Form extends \common\components\ARModel
{
    const TYPE_MESSAGE = 1;

    const IS_READ_NO = -1;
    const IS_READ_YES = 1;

    private $_fields = [];

    public function __get($name)
    {
        try {
            return parent::__get($name);
        } catch (UnknownPropertyException $e) {
            if (isset($this->_fields[$name])) {
                return $this->_fields[$name];
            } else {
                return null;
            }
        }
    }

    public function __set($name, $value)
    {
        try {
            parent::__set($name, $value);
        } catch (UnknownPropertyException $e) {
            $this->_fields[$name] = $value;
        }
    }

    public function load($data = null, $formName = 'Form')
    {
        if ($data === null) {
            $data = post($formName);
        }
        if ($data) {
            foreach ($data as $key => $value) {
                $this->$key = $value;
            }
            $this->fields = json_encode(array_keys($this->_fields));
            return true;
        } else {
            return false;
        }
    }

    public function add()
    {
        if (!empty($this->_fields)) {
            $transaction = self::dbTransaction();
            try {
                if (!$this->insert()) {
                    throwex();
                }
                $item = new FormItem;
                foreach ($this->_fields as $name => $value) {
                    $item->isNewRecord = true;
                    $item->form_id = $this->id;
                    $item->name = $name;
                    if (is_array($value)) {
                        $item->value = json_encode($value);
                    } else {
                        $item->value = $value;
                    }
                    if (!$item->insert()) {
                        throwex();
                    }
                }
                $transaction->commit();
                return true;
            } catch (\Exception $e) {
                $this->addError('fields', $e->getMessage());
                $transaction->rollBack();
                return false;
            }
        }
    }

    public function rules()
    {
        return [
            [['fields'], 'required'],
            [['type', 'is_read'], 'integer'],
            [['created_at'], 'safe'],
            [['fields'], 'string', 'max' => 500]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fields' => 'Fields',
            'type' => '表单类型',
            'is_read' => '阅读状态',
            'created_at' => 'Created At',
        ];
    }

    /****************************** 以下为设置关联模型的方法 ******************************/

    public function getItems()
    {
        return $this->hasMany(FormItem::className(), ['form_id' => 'id']);
    }

    /****************************** 以下为公共显示条件的方法 ******************************/

    public function search()
    {
        $this->setSearchParams();

        return self::find()
            ->filterWhere([
                'form.id' => $this->id,
                'form.type' => $this->type,
                'form.is_read' => $this->is_read,
            ])
            ->andFilterWhere(['like', 'form.fields', $this->fields])
            ->andFilterWhere(['like', 'form.created_at', $this->created_at])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/

    // Map method of field `type`
    public static function getTypeMap($prepend = false)
    {
        $map = [
            self::TYPE_MESSAGE => '留言',
        ];

        return self::resetMap($map, $prepend);
    }

    // Format method of field `type`
    public function getTypeValue($value = null)
    {
        return $this->resetValue($value);
    }

    // Map method of field `is_read`
    public static function getIsReadMap($prepend = false)
    {
        $map = [
            self::IS_READ_YES => '已读',
            self::IS_READ_NO => '未读',
        ];

        return self::resetMap($map, $prepend);
    }

    // Format method of field `is_read`
    public function getIsReadValue($value = null)
    {
        return $this->resetValue($value);
    }
}
