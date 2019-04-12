<?php

namespace common\modules\wizard\controllers;

use Yii;
use common\helpers\Url;
use common\helpers\Html;
use common\helpers\Cookie;
use common\helpers\System;

/**
 * @author ChisWill
 */
class MigrateController extends \common\components\WebController
{
    public $layout = 'migrateMain';

    public $menu = [
        'list' => ['name' => '历史版本', 'url' => 'history-list'],
        'create' => ['name' => '创建迁移', 'url' => 'create-migration'],
        'cache' => ['name' => '缓存管理', 'url' => 'cache'],
        'data' => ['name' => '数据同步', 'url' => 'sync-data']
    ];

    public $action;

    protected $userCookieName = 'wizardCommitUser';

    protected $model;

    public function init()
    {
        parent::init();

        self::offEvent();

        $module = $this->module;
        $this->model = Yii::createObject($module->generators['migrate']);

        if (u('id') != 1) {
            unset($this->menu['cache']);
        }
        if (!$this->isLocalEnv()) {
            unset($this->menu['create']);
        }
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            return YII_ENV_PROD !== true || u('id') == 1;
        } else {
            return false;
        }
    }

    public function actionIndex()
    {
        return $this->redirect(['historyList']);
    }

    public function actionSyncData()
    {
        $model = $this->model;
        $files = $model->getDataFiles();

        if (post('action') === 'migrateData') {
            try {
                $model->syncData($files);
                return success('数据同步成功');
            } catch (\Exception $e) {
                return error($e->getMessage());
            }
        }

        $model->scenario = 'data';
        $user = Cookie::get($this->userCookieName);
        $model->commitUser = $user ?: null;

        if ($model->load()) {
            if ($model->validate()) {
                Cookie::set($this->userCookieName, $model->commitUser);
                try {
                    $model->recordData();
                    return success('数据保存成功');
                } catch (\Exception $e) {
                    return error($e->getMessage());
                }
            } else {
                return error($model);
            }
        }

        return $this->render('syncData', compact('files', 'model'));
    }

    public function actionDeleteData($file)
    {
        if (!$this->isLocalEnv()) {
            return error('禁止删除！');
        }

        $model = $this->model;

        if ($model->deleteData($file)) {
            return success('成功删除！');
        } else {
            return error('删除失败！');
        }
    }

    public function actionCache()
    {
        $model = $this->model;

        if (req()->isPost) {
            $model->deleteCache();
            return success('清除完毕！');
        }
        $cache = $model->getCache();

        return $this->render('cache', compact('cache'));
    }

    public function actionInfo($file)
    {
        $model = $this->model;

        $data = $model->getFileByName($file);

        return $this->renderPartial('previewSql', ['sql' => $data['sql']]);
    }

    public function actionSync($file)
    {
        $model = $this->model;

        if ($model->sync($file)) {
            return success(Html::successSpan('Success'));
        } else {
            return error($model->syncErrors);
        }
    }

    public function actionDeleteVersion($file)
    {
        if (!$this->isLocalEnv()) {
            return error('禁止删除！');
        }

        $model = $this->model;

        if ($model->delete($file)) {
            return success('成功删除！');
        } else {
            return error('删除失败！');
        }
    }

    public function actionHistoryList($key = '')
    {
        $model = $this->model;

        $files = $model->getFiles();
        // 最新的排在最前面
        rsort($files);

        $history = $model->getHistory();
        $user = Cookie::get($this->userCookieName);

        if (req()->isAjax) {
            return $this->renderAjax('_historyList', compact('files', 'history', 'model', 'key', 'user'));
        }

        return $this->render('historyList', compact('files', 'history', 'model', 'key', 'user'));
    }

    public function actionCreateMigration()
    {
        $model = $this->model;
        $model->scenario = 'migrate';
        $user = Cookie::get($this->userCookieName);
        $model->commitUser = $user ?: null;

        if ($model->load()) {
            if (!$this->isLocalEnv()) {
                return error('禁止创建！');
            }
            if ($model->validate()) {
                Cookie::set($this->userCookieName, $model->commitUser);
                if ($model->save()) {
                    return success('创建成功！');
                } else {
                    return error('迁移记录文件写入失败！');
                }
            } else {
                return error($model);
            }
        }

        return $this->render('createMigration', compact('model'));
    }

    public function actionEdit($file)
    {
        if (!$this->isLocalEnv()) {
            die('禁止修改！');
        }
        $model = $this->model;
        $data = $model->getFileByName($file);

        if ($model->load()) {
            $model->commitUser = $data['user'];
            if ($model->validate()) {
                if ($model->update($file)) {
                    return success('修改成功！', ['isEdit' => true]);
                } else {
                    return error('迁移记录文件写入失败！');
                }
            } else {
                return error($model);
            }
        } else {
            $model->inputSql = $data['sql'];
            $model->description = $data['desc'];
        }

        return $this->renderAjax('editMigration', compact('model'));
    }

    public function isLocalEnv()
    {
        return YII_ENV_DEV;
    }
}
