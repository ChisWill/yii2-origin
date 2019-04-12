<?php

namespace common\models;

use Yii;

/**
 * 这是表 `form_item` 的模型
 */
class FormItem extends \common\components\ARModel
{
    public function rules()
    {
        return [
            [['form_id', 'name'], 'required'],
            [['form_id'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['value'], 'string', 'max' => 1000]
        ];
    }

    public function attributeLabels()
    {
        return [
            'form_id' => 'Form ID',
            'name' => 'Name',
            'value' => 'Value',
        ];
    }

    /****************************** 以下为设置关联模型的方法 ******************************/

    public function getForm()
    {
        return $this->hasOne(Form::className(), ['id' => 'form_id']);
    }

    /****************************** 以下为公共显示条件的方法 ******************************/

    public function search()
    {
        $this->setSearchParams();

        return self::find()
            ->filterWhere([
                'formItem.form_id' => $this->form_id,
            ])
            ->andFilterWhere(['like', 'formItem.name', $this->name])
            ->andFilterWhere(['like', 'formItem.value', $this->value])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/
}
