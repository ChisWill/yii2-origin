<?php

namespace common\models;

use Yii;

/**
 * 这是表 `log_sql_list` 的模型
 */
class LogSqlList extends \common\components\ARModel
{
    public function rules()
    {
        return [
            [['task_id'], 'required'],
            [['task_id'], 'integer'],
            [['sql'], 'default', 'value' => ''],
            [['diff', 'time'], 'number'],
            [['category'], 'string', 'max' => 50],
            [['trace'], 'string', 'max' => 100]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'sql' => 'Sql',
            'category' => '语句类型',
            'diff' => '间隔时长(ms)',
            'time' => '总时长(ms)',
            'trace' => '执行文件',
        ];
    }

    /****************************** 以下为设置关联模型的方法 ******************************/

    public function getTask()
    {
        return $this->hasOne(LogSqlTask::className(), ['id' => 'task_id']);
    }


    /****************************** 以下为公共显示条件的方法 ******************************/

    public function search()
    {
        $this->setSearchParams();

        return self::find()
            ->filterWhere([
                'logSqlList.id' => $this->id,
                'logSqlList.task_id' => $this->task_id,
                'logSqlList.diff' => $this->diff,
                'logSqlList.time' => $this->time,
            ])
            ->andFilterWhere(['like', 'logSqlList.sql', $this->sql])
            ->andFilterWhere(['like', 'logSqlList.category', $this->category])
            ->andFilterWhere(['like', 'logSqlList.trace', $this->trace])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/
}
