<?php

namespace common\models;

use Yii;

/**
 * 这是表 `log_sql_task` 的模型
 */
class LogSqlTask extends \common\components\ARModel
{
    public $start_time;
    public $end_time;
    public $search_duration;
    public $search_sql;

    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['method'], 'string', 'max' => 100],
            [['url'], 'string', 'max' => 300],
            [['request'], 'string', 'max' => 10],
            [['ip'], 'string', 'max' => 20]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'method' => '调用方法',
            'url' => '请求链接',
            'request' => '请求类型',
            'ip' => 'IP',
            'user_id' => '用户ID',
            'created_at' => '记录时间',
        ];
    }

    /****************************** 以下为设置关联模型的方法 ******************************/

    public function getList()
    {
        return $this->hasMany(LogSqlList::className(), ['task_id' => 'id'])->asArray();
    }

    /****************************** 以下为公共显示条件的方法 ******************************/

    public function search()
    {
        $this->setSearchParams();

        return self::find()
            ->filterWhere([
                'logSqlTask.id' => $this->id,
                'logSqlTask.user_id' => $this->user_id,
            ])
            ->andFilterWhere(['like', 'logSqlTask.method', $this->method])
            ->andFilterWhere(['like', 'logSqlTask.url', $this->url])
            ->andFilterWhere(['like', 'logSqlTask.request', $this->request])
            ->andFilterWhere(['like', 'logSqlTask.ip', $this->ip])
            ->andFilterWhere(['like', 'logSqlTask.created_at', $this->created_at])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    public function listQuery()
    {
        $query = $this->search();

        if ($this->search_duration || $this->search_sql) {
            $ids = LogSqlList::find()
                ->select('task_id')
                ->andFilterWhere(['>=', 'duration', $this->search_duration])
                ->andFilterWhere(['like', 'logSqlList.sql', $this->search_sql])
                ->asArray()
                ->map('task_id', 'task_id');
            $query->andWhere(['id' => $ids]);
        }

        return $query
                ->with('list')
                ->andFilterWhere(['>=', 'created_at', $this->start_time])
                ->andFilterWhere(['<=', 'created_at', $this->end_time ? date('Y-m-d', strtotime($this->end_time) + 3600 * 24) : null]);
                ;
    }

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/
}
