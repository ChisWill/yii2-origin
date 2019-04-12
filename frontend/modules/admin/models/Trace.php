<?php

namespace admin\models;

use Yii;

class Trace extends \common\models\Trace
{
    public $start_date;
    public $end_date;
    public $count;

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

    public function visitUsers()
    {
        return $this
            ->search()
            ->andDateRange($this->start_date, $this->end_date)
            ->groupBy('ip')
            ->count() ?: 0;
    }

    public function visitNumbers()
    {
        return $this
            ->search()
            ->andDateRange($this->start_date, $this->end_date)
            ->count() ?: 0;
    }

    public function avgDuration()
    {
        return $this
            ->search()
            ->andDateRange($this->start_date, $this->end_date)
            ->average('duration') ?: 0;
    }

    public function missRate($all)
    {
        if ($all === 0) {
            return 0;
        }
        return ($this
            ->search()
            ->andDateRange($this->start_date, $this->end_date)
            ->groupBy('ip')
            ->having(['count(1)' => 1])
            ->count() ?: 0) / $all;
    }

    public function visitData()
    {
        $subQuery = $this
            ->search()
            ->select(['count(1) AS count', self::dbExpression('DATE_FORMAT(created_at, "%Y-%m-%d") AS created_at')])
            ->andDateRange($this->start_date, $this->end_date)
            ->groupBy(self::dbExpression('ip, DATE_FORMAT(created_at, "%Y-%m-%d")'));
        return self::dbQuery()
            ->select(['count(1) AS count', 'created_at'])
            ->from(['sub' => $subQuery])
            ->groupBy('created_at')
            ->orderBy('created_at ASC')
            ->all()
            ;
    }

    public function realtimeVisitQuery()
    {
        return $this
            ->search()
            ->select(['page_name', 'trace.created_at', 'ip', 'user_id', 'referrer'])
            ->joinWith('user')
            ->andDateRange($this->start_date, $this->end_date, 'trace.created_at')
            ->orderBy('trace.id DESC')
        ;
    }

    public function pageRankQuery()
    {
        return $this
            ->search()
            ->select(['page_title', 'page_name', 'count(1) AS count', 'avg(duration) AS duration'])
            ->andDateRange($this->start_date, $this->end_date)
            ->groupBy('page_name')
            ->orderBy('count DESC, duration DESC')
            ;
    }
}
