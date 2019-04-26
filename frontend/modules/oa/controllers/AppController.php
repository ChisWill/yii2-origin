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
            'id' => ['header' => '项目代号', 'width' => '60px', 'value' => function ($row) {
                return $row->code;
            }],
            'name' => ['type' => 'text', 'width' => '250px'],
            'domain' => ['type' => 'text', 'width' => '250px'],
            'type' => ['type' => 'select', 'width' => '100px'],
            'server_id' => ['type' => 'select', 'width' => '120px', 'value' => function ($row) {
                if ($row->server_id == 0) {
                    return '无';
                } else {
                    return $row->server['server_name'];
                }
            }],
            ['header' => '最新进展', 'value' => function ($row) {
                return $row->process_info;
            }],
            u()->can('app/advanceUpdate') ? 'server_info' : null => ['width' => '95px', 'options' => ['style' => ['position' => 'relative']], 'value' => 'advanceInfo'],
            u()->can('app/advanceUpdate') ? 'third_info' : null => ['width' => '95px', 'options' => ['style' => ['position' => 'relative']], 'value' => 'advanceInfo'],
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
            $serializeFields = ['server_info', 'third_info', 'requirement_info'];
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

        $totalAmount = $query->sum('total_amount') ?: 0;
        $totalRest = $query->sum('rest_amount') ?: 0;
        $html = $query->getTable([
            'code',
            'name' => ['type' => 'text'],
            'server_rent' => ['type' => 'text', 'width' => '150px', 'header' => '服务器月租费'],
            'monthly' => ['type' => 'text', 'width' => '150px', 'header' => '维护费'],
            'ios_sign' => ['type' => 'text', 'header' => 'IOS月费'],
            'total_amount' => ['type' => 'text', 'value' => function ($row) {
                return $row->total_amount > 0 ? $row->total_amount : '';
            }],
            'rest_amount' => ['type' => 'text', 'value' => function ($row) {
                return $row->total_amount > 0 ? $row->rest_amount : '';
            }],
            'created_at' => ['header' => '立项日期', 'width' => '80px', 'value' => function ($row) {
                return substr($row->created_at, 0, 10);
            }]
        ], [
            'showFooter' => true,
            'ajaxUpdateAction' => 'ajaxUpdateApp',
            'paging' => false,
            'ajaxReturn' => compact('totalAmount', 'totalRest'),
            'searchColumns' => [
                'code',
                'name',
                'type' => 'select'
            ]
        ]);

        return $this->render('feeList', compact('html', 'totalAmount', 'totalRest'));
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
        if ($field == 'requirement_info') {
            $app = OaApp::findModel($id);
            $title = $app->code . ' - ' . $app->label($field);
            $info = unserialize($app->$field) ?: [];
            if ($type == 'view') {
                $app->readTips($field);
                return $this->renderPartial('requirementView', compact('title', 'info'));
            } else {
                if (req()->isPost) {
                    $upload = self::getUpload(['uploadName' => 'file', 'checkExtensionByMimeType' => false, 'extensions' => 'txt, doc, docs, xls, xlsx, ppt, pptx, rar', 'message' => '请选择要上传的文件']);
                    if ($upload->move()) {
                        $data = [
                            'username' => u()->realname,
                            'filePath' => $upload->filePath,
                            'fileName' => $upload->originName,
                            'time' => self::$time
                        ];
                        $info[] = $data;
                        $app->$field = serialize($info);
                        if ($app->save()) {
                            $app->notify($field);
                            return success();
                        } else {
                            return error($app);
                        }
                    } else {
                        return error($upload);
                    }
                }
                return $this->render('requirementUpdate', compact('title', 'info'));
            }
        } else {
            return $this->actionAdvanceUpdate($id, $field, $type);
        }
    }

    /**
     * @authname 私密信息修改
     */
    public function actionAdvanceUpdate($id, $field, $type)
    {
        $app = OaApp::findModel($id);
        if ($field == 'process_info') {
            $title = $app->code . ' - ' . $app->label('process_info');
            $processes = OaProcess::getList($id, OaProcess::TYPE_APP);
            if ($type == 'view') {
                $app->readTips('process_info');
                return $this->renderPartial('processView', compact('title', 'processes'));
            } elseif ($type == 'update') {
                $process = new OaProcess;
                if ($process->load()) {
                    $process->target_id = $app->id;
                    $process->type = OaProcess::TYPE_APP;
                    if ($process->save()) {
                        $app->process_info = $process->desc;
                        $app->update();
                        $app->notify($field);
                        return success();
                    } else {
                        return error($process);
                    }
                }
                return $this->render('processUpdate', compact('process', 'title', 'processes'));
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
     * @authname 下载文件
     */
    public function actionDownload($path, $name)
    {
        return $this->download(Yii::getAlias('@webroot' . $path), $name);
    }

    /**
     * @authname 任务列表
     */
    public function actionTaskList()
    {
        $query = (new OaTask)->taskListQuery();
        $waitCount = (new OaTask)->taskListQuery()->andWhere(['<>', 'task_state', OaTask::TASK_STATE_DONE])->count();

        $operationWidth = '130';
        if (u()->can('app/verifyTask')) {
            $operationWidth += 70;
        }
        if (u()->can('app/delayTask')) {
            $operationWidth += 70;
        }

        $html = $query->getTable([
            'id' => '序号',
            'app.code',
            'user.realname' => ['header' => '处理人', 'value' => function ($row) {
                if ($row->user_id) {
                    return $row->user->realname;
                } else {
                    return '无';
                }
            }],
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
            'hour' => ['width' => '100px'],
            'task_state' => ['header' => '任务状态', 'value' => function ($row) {
                switch ($row->task_state) {
                    case OaTask::TASK_STATE_WAIT:
                        return Html::errorSpan($row->taskStateValue);
                    case OaTask::TASK_STATE_ING:
                        return Html::warningSpan($row->taskStateValue);
                    case OaTask::TASK_STATE_OVER:
                        return Html::successSpan($row->taskStateValue);
                    case OaTask::TASK_STATE_DONE:
                        return Html::finishSpan($row->taskStateValue);
                }
            }],
            'updated_at' => function ($row) {
                return substr($row->updated_at, 5, 11);
            },
            'publish.realname' => '发布人',
            'created_at' => function ($row) {
                return substr($row->created_at, 5, 11);
            },
            ['type' => ['edit' => 'saveTask', 'delete' => 'deleteTask'], 'width' => $operationWidth . 'px', 'value' => function ($row) {
                $btns = [Hui::successBtn('历史', ['viewHistory', 'id' => $row->id], ['class' => 'view-fancybox fancybox.iframe view-btn'])];
                if ($row->task_state == OaTask::TASK_STATE_ING && $row->user_id == u()->id) {
                    $btns[] = Hui::secondaryBtn('标记完成', ['overTask', 'id' => $row->id], ['class' => 'overBtn', 'data' => ['info' => '确认将 ' . $row->id . ' 号任务标记完成？']]);
                } else if ($row->task_state == OaTask::TASK_STATE_OVER && u()->can('app/verifyTask')) {
                    $btns[] = Hui::secondaryBtn('审核', ['verifyTask', 'id' => $row->id], ['class' => 'verifyBtn', 'data' => ['info' => '确认 ' . $row->id . ' 号任务已完成？']]);
                }
                if ($row->task_state == OaTask::TASK_STATE_ING && u()->can('app/delayTask')) {
                    $btns[] = Hui::dangerBtn('延期', ['delayTask', 'id' => $row->id], ['class' => 'delayBtn', 'data' => ['info' => '请输入' . $row->id . ' 号任务延期天数']]);
                }
                return implode('&nbsp;&nbsp;', $btns);
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
                'user.realname' => ['header' => '处理人'],
                'urgency_level' => 'select',
                'task_state' => 'select'
            ]
        ]);

        return $this->render('taskList', compact('html', 'waitCount'));
    }

    /**
     * @authname 项目延期
     */
    public function actionDelayTask($id)
    {
        $task = OaTask::findModel($id);
        $day = post('day');
        $reason = post('reason');
        $task->hour += $day;
        if ($task->update()) {
            OaProcess::append($id, sprintf('%s延期了项目%d天，理由：%s', u()->realname, $day, $reason), OaProcess::TYPE_TASK);
            return success();
        } else {
            return error($task);
        }
    }

    /**
     * @authname 查看任务历史进度
     */
    public function actionViewHistory($id)
    {
        $task = OaTask::findModel($id);
        $title = $task->content;
        $processes = OaProcess::getList($id, OaProcess::TYPE_TASK);

        return $this->render('processView', compact('title', 'processes'));
    }

    /**
     * @authname 删除任务
     */
    public function actionDeleteTask()
    {
        return parent::actionDelete();
    }

    /**
     * @authname 任务标记完成
     */
    public function actionOverTask($id)
    {
        $task = OaTask::findModel($id);
        $task->task_state = OaTask::TASK_STATE_OVER;
        if ($task->update()) {
            OaProcess::append($id, u()->realname . '提交了任务完成申请', OaProcess::TYPE_TASK);
            return success();
        } else {
            return error($task);
        }
    }

    /**
     * @authname 任务审核
     */
    public function actionVerifyTask($id)
    {
        $task = OaTask::findModel($id);
        if (post('state') == 1) {
            $desc = u()->realname . '确认完成任务';
            $state = OaTask::TASK_STATE_DONE;
        } else {
            $desc = u()->realname . '驳回了申请，理由：' . post('info');
            $state = OaTask::TASK_STATE_ING;
        }
        $task->task_state = $state;
        if ($task->update()) {
            OaProcess::append($id, $desc, OaProcess::TYPE_TASK);
            return success();
        } else {
            return error($task);
        }
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
            if ($model->isNewRecord) {
                $desc = u()->realname . '创建了任务';
            } else {
                $desc = u()->realname . '编辑了任务内容';
            }
            if ($model->save()) {
                OaProcess::append($model->id, $desc, OaProcess::TYPE_TASK);
                return success();
            } else {
                return error($model);
            }
        }

        return $this->render('saveTask', compact('model'));
    }
}
