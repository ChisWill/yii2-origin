<?php

namespace oa\models;

use Yii;

/**
 * 这是表 `oa_task` 的模型
 */
class OaTask extends \common\components\ARModel
{
    const TASK_STATE_WAIT = 1;
    const TASK_STATE_ING = 2;
    const TASK_STATE_OVER = 3;
    const TASK_STATE_DONE = 4;

    const URGENCY_LEVEL_LOW = 1;
    const URGENCY_LEVEL_NORMAL = 2;
    const URGENCY_LEVEL_HIGH = 3;

    public function rules()
    {
        return [
            [['app_id', 'content', 'hour'], 'required'],
            [['app_id', 'user_id', 'urgency_level', 'task_state', 'created_by', 'updated_by'], 'integer'],
            [['content'], 'default', 'value' => ''],
            [['hour'], 'number'],
            [['created_at', 'updated_at'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_id' => '项目ID',
            'content' => '任务内容',
            'hour' => '天数',
            'user_id' => '处理人',
            'urgency_level' => '紧急度',
            'task_state' => '任务状态：1未处理，2处理中，3已完成',
            'created_at' => '发布时间',
            'created_by' => '发布人',
            'updated_at' => '处理时间',
            'updated_by' => '更新人',
        ];
    }

    /****************************** 以下为设置关联模型的方法 ******************************/

    public function getUser()
    {
        return $this->hasOne(AdminUser::className(), ['id' => 'user_id']);
    }

    public function getApp()
    {
        return $this->hasOne(OaApp::className(), ['id' => 'app_id']);
    }

    public function getPublish()
    {
        return $this->hasOne(AdminUser::className(), ['id' => 'created_by']);
    }

    /****************************** 以下为公共显示条件的方法 ******************************/

    public function search()
    {
        $this->setSearchParams();

        return self::find()
            ->filterWhere([
                'oaTask.id' => $this->id,
                'oaTask.app_id' => $this->app_id,
                'oaTask.hour' => $this->hour,
                'oaTask.user_id' => $this->user_id,
                'oaTask.urgency_level' => $this->urgency_level,
                'oaTask.task_state' => $this->task_state,
                'oaTask.created_by' => $this->created_by,
                'oaTask.updated_by' => $this->updated_by,
            ])
            ->andFilterWhere(['like', 'oaTask.content', $this->content])
            ->andFilterWhere(['like', 'oaTask.created_at', $this->created_at])
            ->andFilterWhere(['like', 'oaTask.updated_at', $this->updated_at])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    public function taskListQuery()
    {
        return $this
            ->search()
            ->joinWith(['user', 'app', 'publish'])
            ->active()
            ->orderBy(self::dbExpression('task_state ASC, DATE_ADD(oaTask.created_at, INTERVAL `hour` DAY) ASC, urgency_level DESC'));
    }

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/

    // Map method of field `task_state`
    public static function getTaskStateMap($prepend = false)
    {
        $map = [
            self::TASK_STATE_WAIT => '等待中',
            self::TASK_STATE_ING => '处理中',
            self::TASK_STATE_OVER => '标记完成',
            self::TASK_STATE_DONE => '结束'
        ];

        return self::resetMap($map, $prepend);
    }

    // Format method of field `task_state`
    public function getTaskStateValue($value = null)
    {
        return $this->resetValue($value);
    }

    // Map method of field `urgency_level`
    public static function getUrgencyLevelMap($prepend = false)
    {
        $map = [
            self::URGENCY_LEVEL_LOW => '低',
            self::URGENCY_LEVEL_NORMAL => '中',
            self::URGENCY_LEVEL_HIGH => '高'
        ];

        return self::resetMap($map, $prepend);
    }

    // Format method of field `urgency_level`
    public function getUrgencyLevelValue($value = null)
    {
        return $this->resetValue($value);
    }

    // Map method of field `app_id`
    public static function getAppIdMap($prepend = false)
    {
        $map = OaApp::map('id', 'code');

        return self::resetMap($map, $prepend);
    }

    // Format method of field `app_id`
    public function getAppIdValue($value = null)
    {
        return $this->resetValue($value);
    }

    // Map method of field `user_id`
    public static function getUserIdMap($prepend = false)
    {
        $map = AdminUser::find()->active()->map('id', 'realname');
        $map = ['' => '选择处理人员'] + $map;

        return self::resetMap($map, $prepend);
    }

    // Format method of field `user_id`
    public function getUserIdValue($value = null)
    {
        return $this->resetValue($value);
    }
}
