<?php

namespace oa\controllers;

use Yii;
use common\helpers\Hui;
use common\helpers\Html;
use common\helpers\ArrayHelper;
use oa\models\OaApp;
use oa\models\OaTask;
use oa\models\OaProcess;
use oa\models\OaServer;

/**
 * @author ChisWill
 */
class AppController extends \oa\components\Controller
{
    /**
     * @authname 项目列表
     */
    public function actionList()
    {
        $this->view->title = '项目列表';

        $query = (new OaApp)->search()->joinWith(['server']);

        $html = $query->getTable([
            'id' => ['header' => '项目代号', 'value' => function ($row) {
                return $row->code;
            }],
            'name' => ['type' => 'text'],
            'domain' => ['type' => 'text'],
            'server_id' => ['type' => 'select', 'value' => function ($row) {
                if ($row->server_id == 0) {
                    return '无';
                } else {
                    return $row->server['server_name'];
                }
            }],
            'type' => ['type' => 'select'],
            ['header' => '最新进展', 'width' => '105px', 'value' => function ($row) {
                return $row->process_info;
            }],
            // 'server_info' => ['width' => '95px', 'options' => ['style' => ['position' => 'relative']], 'value' => 'advanceInfo'],
            'wechat_info' => ['width' => '95px', 'options' => ['style' => ['position' => 'relative']], 'value' => 'advanceInfo'],
            'pay_info' => ['width' => '95px', 'options' => ['style' => ['position' => 'relative']], 'value' => 'advanceInfo'],
            'sms_info' => ['width' => '95px', 'options' => ['style' => ['position' => 'relative']], 'value' => 'advanceInfo'],
            'requirement_info' => ['width' => '95px', 'options' => ['style' => ['position' => 'relative']], 'value' => 'advanceInfo'],
            'process_info' => ['width' => '95px', 'options' => ['style' => ['position' => 'relative']], 'value' => 'advanceInfo'],
            'created_at' => ['header' => '立项日期', 'width' => '75px', 'value' => function ($row) {
                return substr($row->created_at, 0, 10);
            }]
        ], [
            'showFooter' => true,
            'addBtn' => ['saveApp' => '创建新项目'],
            'ajaxUpdateAction' => 'ajaxUpdateApp',
            'paging' => false,
            'searchColumns' => [
                'code',
                'name',
                'type' => 'select'
            ]
        ]);

        return $this->render('list', compact('html'));
    }

    /**
     * @authname 创建/编辑项目
     */
    public function actionSaveApp($id = null)
    {
        $model = OaApp::findModel($id);

        if ($model->load()) {
            $serializeFields = ['server_info', 'wechat_info', 'pay_info', 'sms_info', 'requirement_info'];
            foreach ($serializeFields as $field) {
                if ($model->$field) {
                    $value = [
                        'text' => $model->$field,
                        'updated_at' => self::$time,
                        'updated_by' => u()->realname
                    ];
                    $model->$field = serialize($value);
                }
            }
            if ($model->save()) {
                $model->notify();
                return success();
            } else {
                return error($model);
            }
        }
        return $this->render('saveApp', compact('model'));
    }

    /**
     * @authname 项目费用列表
     */
    public function actionFeeList()
    {
        $query = (new OaApp)->search();

        $html = $query->getTable([
            'code',
            'name' => ['type' => 'text'],
            'server_rent' => ['type' => 'text', 'width' => '150px', 'header' => '服务器月租费'],
            'monthly' => ['type' => 'text', 'width' => '150px', 'header' => '维护费'],
            'ios_sign' => ['type' => 'text', 'header' => 'IOS月费'],
            'total_amount' => ['type' => 'text'],
            'rest_amount' => ['type' => 'text'],
            'created_at' => ['header' => '立项日期', 'width' => '80px', 'value' => function ($row) {
                return substr($row->created_at, 0, 10);
            }]
        ], [
            'showFooter' => true,
            'ajaxUpdateAction' => 'ajaxUpdateApp',
            'paging' => false,
            'searchColumns' => [
                'code',
                'name',
                'type' => 'select'
            ]
        ]);

        return $this->render('feeList', compact('html'));
    }

    /**
     * @authname 服务器列表
     */
    public function actionServerList()
    {
        $query = (new OaServer)->search();

        $html = $query->getTable([
            'server_name' => ['type' => 'text'],
            'server_ip' => ['type' => 'text'],
            'quoted_price' => ['type' => 'text'],
            'discount_price' => ['type' => 'text'],
            'platform_id' => ['type' => 'select'],
            'account_id' => ['type' => 'select'],
            'remark' => ['type' => 'text'],
            'use_state' => ['type' => 'select'],
            ['type' => ['delete']]
        ], [
            'addBtn' => ['addServer' => '添加服务器'],
            'ajaxUpdateAction' => 'ajaxUpdateApp',
            'paging' => false,
        ]);

        return $this->render('serverList', compact('html'));
    }

    /**
     * @authname 添加服务器
     */
    public function actionAddServer()
    {
        $model = new OaServer;

        if ($model->load()) {
            if ($model->save()) {
                return success();
            } else {
                return error($model);
            }
        }

        return $this->render('addServer', compact('model'));
    }

    /**
     * @authname 快速修改
     */
    public function actionAjaxUpdateApp()
    {
        return parent::actionAjaxUpdate();
    }

    /**
     * @authname 公共信息修改
     */
    public function actionCommonUpdate($id, $field, $type)
    {
        return $this->actionAdvanceUpdate($id, $field, $type);
    }

    /**
     * @authname 私密信息修改
     */
    public function actionAdvanceUpdate($id, $field, $type)
    {
        $app = OaApp::findModel($id);
        if ($field == 'process_info') {
            $processes = OaProcess::find()->with(['adminUser'])->where(['app_id' => $id])->asArray()->all();
            if ($type == 'view') {
                $app->readTips('process_info');
                return $this->renderPartial('processView', compact('app', 'processes'));
            } elseif ($type == 'update') {
                $process = new OaProcess;
                if ($process->load()) {
                    $process->app_id = $app->id;
                    if ($process->save()) {
                        $app->process_info = $process->desc;
                        $app->update();
                        $app->notify($field);
                        return success();
                    } else {
                        return error($process);
                    }
                }
                return $this->render('processUpdate', compact('process', 'app', 'processes'));
            }
        } else {
            $value = unserialize($app->$field);
            if ($type == 'view') {
                $app->readTips($field);
                return $this->renderPartial('advanceView', compact('app', 'value', 'field'));
            } elseif ($type == 'update') {
                if ($app->load()) {
                    $value = [
                        'text' => $app->$field,
                        'updated_at' => self::$time,
                        'updated_by' => u()->realname
                    ];
                    $app->$field = serialize($value);
                    if ($app->save()) {
                        $app->notify($field);
                        return success();
                    } else {
                        return error($app);
                    }
                }
                return $this->render('advanceUpdate', compact('app', 'value', 'field'));
            }
        }
    }

    /**
     * @authname 任务列表
     */
    public function actionTaskList()
    {
        $query = (new OaTask)->taskListQuery();

        $waitCount = (new OaTask)->taskListQuery()->andWhere(['<>', 'task_state', OaTask::TASK_STATE_OVER])->count();

        $html = $query->getTable([
            'id',
            'app.code',
            'content',
            'urgency_level' => function ($row) {
                switch ($row->urgency_level) {
                    case OaTask::URGENCY_LEVEL_LOW:
                        return Html::successSpan($row->urgencyLevelValue);
                    case OaTask::URGENCY_LEVEL_NORMAL:
                        return Html::warningSpan($row->urgencyLevelValue);
                    case OaTask::URGENCY_LEVEL_HIGH:
                        return Html::errorSpan($row->urgencyLevelValue);
                }
            },
            'hour',
            'publish.realname' => '发布人',
            'created_at' => function ($row) {
                return substr($row->created_at, 5, 11);
            },
            'user.realname' => ['header' => '处理人', 'value' => function ($row) {
                if ($row->user_id) {
                    return $row->user->realname;
                } else {
                    return '无';
                }
            }],
            'updated_at' => function ($row) {
                return substr($row->updated_at, 5, 11);
            },
            'task_state' => ['header' => '任务状态', 'value' => function ($row) {
                switch ($row->task_state) {
                    case OaTask::TASK_STATE_WAIT:
                        return Html::errorSpan($row->taskStateValue);
                    case OaTask::TASK_STATE_ING:
                        return Html::warningSpan($row->taskStateValue);
                    case OaTask::TASK_STATE_OVER:
                        return Html::successSpan($row->taskStateValue);
                }
            }],
            ['type' => ['edit' => 'saveTask', 'delete'], 'width' => '100px', 'value' => function ($row) {
                if ($row->task_state == OaTask::TASK_STATE_ING) {
                    return Hui::secondaryBtn('完成', ['overTask', 'id' => $row->id], ['class' => 'overBtn', 'data' => ['info' => '确认将 ' . $row->id . ' 号任务标记完成？']]);
                }
            }]
        ], [
            'showFooter' => true,
            'addBtn' => ['saveTask' => '添加任务'],
            'ajaxReturn' => [
                'waitCount' => $waitCount
            ],
            'searchColumns' => [
                'app.code',
                'content',
                'user.realname',
                'urgency_level' => 'select',
                'task_state' => 'select'
            ]
        ]);

        return $this->render('taskList', compact('html', 'waitCount'));
    }

    /**
     * @authname 任务标记完成
     */
    public function actionOverTask($id)
    {
        $task = OaTask::findModel($id);
        $task->task_state = OaTask::TASK_STATE_OVER;
        $task->update();
        return success();
    }

    /**
     * @authname 添加/编辑任务
     */
    public function actionSaveTask($id = null)
    {
        $model = OaTask::findModel($id);

        if ($model->load()) {
            if ($model->user_id) {
                $model->task_state = OaTask::TASK_STATE_ING;
            }
            if ($model->save()) {
                return success();
            } else {
                return error($model);
            }
        }

        return $this->render('saveTask', compact('model'));
    }
}
