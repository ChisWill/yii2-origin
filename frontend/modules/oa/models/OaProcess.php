<?php

namespace oa\models;

use Yii;

/**
 * 这是表 `oa_process` 的模型
 */
class OaProcess extends \common\components\ARModel
{
    const TYPE_APP = 1;
    const TYPE_TASK = 2;

    public function rules()
    {
        return [
            [['target_id', 'desc', 'type'], 'required'],
            [['target_id', 'type', 'created_by'], 'integer'],
            [['desc'], 'default', 'value' => ''],
            [['created_at'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'target_id' => 'Target ID',
            'desc' => '进度描述',
            'type' => 'Type',
            'created_at' => '发表时间',
            'created_by' => '发表人',
        ];
    }

    /****************************** 以下为设置关联模型的方法 ******************************/

    public function getAdminUser()
    {
        return $this->hasOne(AdminUser::className(), ['id' => 'created_by']);
    }

    /****************************** 以下为公共显示条件的方法 ******************************/

    public function search()
    {
        $this->setSearchParams();

        return self::find()
            ->filterWhere([
                'oaProcess.id' => $this->id,
                'oaProcess.target_id' => $this->target_id,
                'oaProcess.type' => $this->type,
                'oaProcess.created_by' => $this->created_by,
            ])
            ->andFilterWhere(['like', 'oaProcess.desc', $this->desc])
            ->andFilterWhere(['like', 'oaProcess.created_at', $this->created_at])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    public static function getList($id, $type)
    {
        return OaProcess::find()
            ->with(['adminUser'])
            ->where(['target_id' => $id, 'type' => $type])
            ->asArray()
            ->all()
            ;
    }

    public static function append($key, $desc, $type)
    {
        $model = new static;
        $model->target_id = $key;
        $model->desc = $desc;
        $model->type = $type;
        if ($model->insert()) {
            return true;
        } else {
            return $model->errors;
        }
    }

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/
}
