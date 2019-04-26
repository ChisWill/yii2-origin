<?php

namespace admin\controllers;

use Yii;
use common\helpers\Hui;
use common\helpers\Html;
use common\helpers\Third;
use common\helpers\Inflector;
use common\helpers\FileHelper;
use common\helpers\ArrayHelper;
use common\helpers\StringHelper;
use common\modules\setting\models\Setting;
use common\modules\rbac\models\AuthItem;
use common\models\LogSqlTask;
use admin\models\Log;
use admin\models\Trace;
use admin\models\AdminMenu;
use admin\models\AdminAction;

/**
 * @author ChisWill
 */
class SystemController extends \admin\components\Controller
{
    /**
     * @authname 查看系统设置
     */
    public function actionSetting()
    {
        if (req()->isAjax) {
            return $this->ajaxDisplay();
        } else {
            return $this->render('setting', ['settings' => Setting::getWebSettings()]);
        }
    }

    protected function ajaxDisplay()
    {
        $settings = Setting::getWebSettings(true);

        $nowTopId = get('nowTopId');

        return success($this->renderPartial('_setting', compact('settings', 'nowTopId')));
    }

    /**
     * @authname 添加系统设置
     */
    public function actionAddSetting()
    {
        $setting = new Setting(['scenario' => post('addSetting') ? 'addSetting' : Setting::SCENARIO_DEFAULT]);
        $setting->load();
        if ($setting->validate()) {
            $setting->add();
            if (!empty($_POST['refresh'])) {
                return $this->ajaxDisplay();
            } else {
                return success($setting->id);
            }
        } else {
            return error($setting);
        }
    }

    /**
     * @authname 修改系统设置
     */
    public function actionSaveSetting()
    {
        $setting = new Setting;

        if ($setting->save()) {
            return success($setting->uploads);
        } else {
            return error($setting);
        }
    }

    /**
     * @authname 删除系统设置
     */
    public function actionDeleteSetting()
    {
        $setting = new Setting;

        $setting->delete(post('id'));

        return $this->ajaxDisplay();
    }

    /**
     * @authname 更改系统设置属性
     */
    public function actionUpdateSetting()
    {
        $setting = new Setting;

        if ($setting->updateSetting(post('id'), post('field'), post('value'))) {
            return $this->ajaxDisplay();
        } else {
            return error($setting);
        }
    }

    /**
     * @authname 系统菜单
     */
    public function actionMenu()
    {
        $query = AdminMenu::find();

        $html = $query->getLinkage([
            'id',
            'name' => ['type' => 'text'],
            'icon' => ['type' => 'text'],
            'url' => ['type' => 'text'],
            'is_show' => ['type' => 'toggle']
        ], [
            'maxLevel' => 2,
            'beforeAdd' => 'beforeAddMenuItem'
        ]);

        return $this->render('menu', compact('html'));
    }

    /**
     * @authname 系统日志
     */
    public function actionLogList()
    {
        // 日志表内容
        $query = (new Log)->logListQuery()->orderBy('id DESC');

        $html = $query->getTable([
            'id',
            'level',
            'category',
            'log_time' => function ($row) {
                $time = (int) $row['log_time'];
                return date('Y-m-d H:i:s', $time);
            },
            'prefix' => '额外信息（[Method] [Url] [Ip] [Uid] [SessionId]）',
            ['type' => ['view' => 'logDetail']]
        ], [
            'searchColumns' => [
                'level' => 'select',
                'category',
                'prefix' => ['header' => '额外信息'],
                'time' => 'dateRange'
            ]
        ]);
        // 日志文件内容
        $dirs = [
            '@base' => [
                'suffix' => 'log',
                'dir' => false
            ],
            '@frontend/runtime/logs' => [
                'suffix' => 'log',
                'dir' => true
            ],
            '@console/runtime/logs' => [
                'suffix' => 'log',
                'dir' => true
            ]
        ];
        $data = [];
        $getRow = function ($path) {
            return [
                'name' => basename($path),
                'dir' => ltrim(str_replace([path('@base'), '\\'], ['', '/'], dirname($path)), '/'),
                'size' => FileHelper::formatFileSize(filesize($path)),
                'editTime' => date('Y-m-d H:i:s', filemtime($path))
            ];
        };
        foreach ($dirs as $path => $pattern) {
            FileHelper::findFiles(path($path), [
                'filter' => function ($path) use ($pattern, $getRow, &$data) {
                    if (!is_dir($path) && preg_match('#.*\.' . $pattern['suffix'] . '#', $path)) {
                        $data[] = $getRow($path);
                    }
                    if ($pattern['dir'] === true && is_dir($path)) {
                        FileHelper::findFiles($path, ['filter' => function ($path) use ($pattern, $getRow, &$data) {
                            if (!is_dir($path) && preg_match('#.*\.' . $pattern['suffix'] . '#', $path)) {
                                $data[] = $getRow($path);
                            }
                        }]);
                    }
                },
                'recursive' => false
            ]);
        }
        $i = 1;
        $fileHtml = self::getTable($data, [
            ['header' => '序号', 'value' => function ($row) use (&$i) {
                return $i++;
            }],
            ['header' => '文件名称', 'value' => function ($row) {
                return Html::a($row['name'], ['fileDetail', 'file' => $row['dir'] . '/' . $row['name']], ['class' => ['view-fancybox', 'fancybox.iframe']]);
            }],
            'dir' => '路径',
            'size' => '大小',
            'editTime' => '最后修改时间',
            ['type' => [], 'width' => '100px', 'value' => function ($row) {
                return Hui::dangerBtn('清空', ['clearFile', 'file' => $row['dir'] . '/' . $row['name']], ['class' => 'clearFileBtn', 'data' => ['file' => $row['name']]]);
            }]
        ]);

        return $this->render('log', compact('html', 'fileHtml'));
    }

    /**
     * @authname 清空日志文件
     */
    public function actionClearFile($file)
    {
        file_put_contents(path('@base/' . $file), '');

        return success();
    }

    /**
     * @authname 查看文件日志详情
     */
    public function actionFileDetail($file)
    {
        $message = explode("\n", file_get_contents(path('@base/' . $file)));
        return $this->renderPartial('fileDetail', compact('message'));
    }

    /**
     * @authname 查看日志详情
     */
    public function actionLogDetail($id)
    {
        $log = Log::findOne($id);

        $message = explode("\n", $log->message);
        $reason = array_shift($message);
        array_shift($message);

        return $this->renderPartial('logDetail', compact('log', 'reason', 'message'));
    }

    /**
     * @authname 查看操作日志
     */
    public function actionActionList()
    {
        $query = (new AdminAction)->listQuery()->orderBy('adminAction.id DESC');
        $map = [];
        $permissionData = AuthItem::getGroupPermissionData();

        $html = $query->getTable([
            'admin.username' => ['header' => '操作者', 'search' => true, 'width' => '60px'],
            'table_name' => ['header' => '表名', 'width' => '75px', 'value' => function ($row) use (&$map) {
                if (!isset($map[$row['table_name']])) {
                    $namespace = AuthItem::getNamespaceByAction($row['action']);
                    $className = $namespace . '\\models\\' . Inflector::id2camel($row['table_name'], '_');
                    $map[$row['table_name']] = new $className;
                }
                return $row['table_name'];
            }],
            'key' => ['header' => '对象ID', 'search' => true, 'width' => '40px'],
            'action' => ['header' => '操作描述', 'width' => '80px', 'value' => function ($row) use ($permissionData) {
                list($module, $controller, $action) = explode('/', $row['action']);
                if (in_array($action, ['ajax-update', 'toggle', 'login', 'password'])) {
                    switch ($action) {
                        case 'password':
                            return '修改密码';
                        case 'login':
                            return '登录后台';
                        case 'toggle':
                            return '状态值切换';
                        case 'ajax-update':
                            return '快捷更新';
                    }
                }
                if (isset($permissionData[$controller])) {
                    $name = lcfirst(Inflector::id2camel(Inflector::id2camel($row['action'], '/'), '-'));
                    if (isset($permissionData[$controller][$name])) {
                        return $permissionData[$controller][$name];
                    } else {
                        return $row['action'];
                    }
                } else {
                    return $row['action'];
                }
            }],
            'value' => ['width' => '50%', 'value' => function ($row) use (&$map) {
                $fields = StringHelper::explode(',', $row->field);
                $values = unserialize($row->value);
                $message = [];
                switch ($row->type) {
                    case AdminAction::TYPE_INSERT:
                        break;
                    case AdminAction::TYPE_UPDATE:
                        foreach ($fields as $key => $field) {
                            $method = 'get' . Inflector::camelize($field) . 'Map';
                            $field = $map[$row['table_name']]->label($field);
                            if (method_exists($map[$row['table_name']], $method)) {
                                try {
                                    $v1 = $map[$row['table_name']]::$method()[$values[$key][0]];
                                    $v2 = $map[$row['table_name']]::$method()[$values[$key][1]];
                                } catch (\Exception $e) {
                                    $v1 = $values[$key][0];
                                    $v2 = $values[$key][1];
                                }
                            } else {
                                $v1 = $values[$key][0];
                                $v2 = $values[$key][1];
                            }
                            if ($v1 != $v2) {
                                $message[] = sprintf('%s： %s 改为 %s', $field, Html::successSpan($v1), Html::errorSpan($v2));
                            }
                        }
                        break;
                    case AdminAction::TYPE_DELETE:
                        break;
                }
                return implode('<br>', $message);
            }],
            'ip' => ['search' => true, 'width' => '60px'],
            'created_at' => ['search' => 'dateRange', 'width' => '70px'],
        ], [
            'export' => '管理员操作记录'
        ]);

        return $this->render('actionList', compact('html'));
    }

    /**
     * @authname 数据库日志
     */
    public function actionLogSqlList()
    {
        $query = (new LogSqlTask)->listQuery()->orderBy('id DESC');

        $html = $query->getTable([
            'id' => ['width' => '50px'],
            'user_id' => ['search' => true, 'width' => '60px'],
            'method' => ['search' => true],
            'url' => ['search' => true],
            'request' => ['search' => ['type' => 'select', 'items' => ['' => '请求类型', 'GET' => 'GET', 'POST' => 'POST']], 'width' => '90px'],
            'ip' => ['search' => true, 'width' => '80px'],
            'created_at' => ['width' => '120px'],
        ], [
            'searchColumns' => [
                'time' => 'timeRange',
                'search_duration' => ['header' => '最少执行时间（ms）', 'type' => 'text'],
                'search_sql' => ['header' => 'SQL语句', 'type' => 'text']
            ],
            'rowOptions' => function ($v, $k) {
                return [
                    'class' => 'task-row',
                    'data-extra' => json_encode($v->list)
                ];
            },
        ]);

        return $this->render('logSqlList', compact('html'));
    }

    /**
     * @authname 用户行为
     */
    public function actionUserTrace()
    {
        // 实时访问
        $realtimeVisitQuery = (new Trace)->realtimeVisitQuery();
        $rank = 1;
        $realtimeVisitHtml = $realtimeVisitQuery->getTable([
            ['header' => '序号', 'value' => function () use (&$rank) {
                return (get('p', 1) - 1) * PAGE_SIZE + $rank++;
            }],
            'created_at' => '访问时间',
            'page_name' => ['width' => '200px'],
            'ip',
            ['header' => '访客位置', 'value' => function ($row) {
                if ($row->ip) {
                    return Third::getIpInfo($row->ip);
                } else {
                    return '-';
                }
            }],
            'referrer' => ['width' => '500px'],
            'user_id' => ['header' => '访客信息', 'width' => '200px', 'value' => function ($row) {
                if ($row->user_id == 0) {
                    return '游客';
                } else {
                    return $row->user->username;
                }
            }]
        ], [
            'searchColumns' => [
                'ip',
                'page_name',
                'referrer',
                'date' => 'dateRange'
            ],
        ]);
        // 访问人数
        $visitUsers = (new Trace)->visitUsers();
        // 浏览量
        $visitNumbers = (new Trace)->visitNumbers();
        // 平均停留时间
        $avgDuration = (new Trace)->avgDuration();
        // 人均浏览量
        $avgVisitNumbers = $visitUsers > 0 ? round($visitNumbers / $visitUsers, 2) : 0;
        // 跳失率
        $missRate = (new Trace)->missRate($visitUsers);
        // 每日访问人数
        $visitData = (new Trace)->visitData();
        // 页面访问排行
        $pageRankQuery = (new Trace)->pageRankQuery();
        $rank = 1;
        $pageRankHtml = $pageRankQuery->getTable([
            ['header' => '排名', 'value' => function () use (&$rank) {
                return (get('p', 1) - 1) * PAGE_SIZE + $rank++;
            }],
            'page_title',
            'page_name',
            'count' => '浏览量',
            'duration' => '平均停留时间'
        ], [
            'showCount' => false,
            'paging' => false
        ]);

        return $this->render('userTrace', compact('visitUsers', 'visitNumbers', 'avgDuration', 'avgVisitNumbers', 'missRate', 'visitData', 'pageRankHtml', 'realtimeVisitHtml'));
    }
}
